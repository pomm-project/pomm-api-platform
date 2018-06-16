<?php

/*
 * This file is part of the API Platform project.
 *
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace PommProject\ApiPlatform;

use ApiPlatform\Core\Api\FilterLocatorTrait;
use ApiPlatform\Core\DataProvider\CollectionDataProviderInterface;
use ApiPlatform\Core\Exception\ResourceClassNotSupportedException;
use ApiPlatform\Core\Metadata\Resource\Factory\ResourceMetadataFactoryInterface;
use PommProject\ApiPlatform\Filter\FilterInterface;
use PommProject\Foundation\Pomm;
use PommProject\Foundation\Where;
use PommProject\ModelManager\Model\Model;
use Psr\Container\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class CollectionDataProvider implements CollectionDataProviderInterface
{
    const PAGE_PARAMETER_NAME_DEFAULT = 'page';

    use FilterLocatorTrait;

    private $pomm;
    private $requestStack;
    private $pagination;
    private $order;
    private $resourceMetadataFactory;

    public function __construct(
        Pomm $pomm,
        RequestStack $requestStack,
        ResourceMetadataFactoryInterface $resourceMetadataFactory,
        ContainerInterface $filterLocator,
        array $pagination,
        array $order
    ) {
        $this->pomm = $pomm;
        $this->requestStack = $requestStack;
        $this->pagination = $pagination;
        $this->order = $order;
        $this->resourceMetadataFactory = $resourceMetadataFactory;
        $this->setFilterLocator($filterLocator);
    }

    /**
     * {@inheritdoc}
     */
    public function getCollection(string $resourceClass, string $operationName = null)
    {
        $request = $this->requestStack->getCurrentRequest();
        if (null === $request) {
            return;
        }

        $modelName = "${resourceClass}Model";
        if (!class_exists($modelName)) {
            throw new ResourceClassNotSupportedException();
        }

        $session = $this->pomm->getDefaultSession();
        $model = $session->getModel($modelName);
        $paginator = $model->paginateFindWhere(
            $this->getWhere($request, $model, $resourceClass),
            $this->getItemsPerPage($request),
            $this->getCurrentPage($request),
            $this->getOrderSuffix($request)
        );

        return new Paginator($paginator);
    }

    private function getCurrentPage(Request $request): int
    {
        return $request->query->get(
            $this->pagination['page_parameter_name'] ?? static::PAGE_PARAMETER_NAME_DEFAULT,
            1
        );
    }

    private function getItemsPerPage(Request $request): int
    {
        return $request->query->get(
            $this->pagination['items_per_page_parameter_name'],
            $this->pagination['items_per_page']
        );
    }

    private function getOrderSuffix(Request $request): string
    {

        $properties = $request->query->get($this->order['order_parameter_name']);
        if ($properties === null) {
            return '';
        }

        $suffix = [];
        foreach ($properties as $property => $order) {
            $suffix[] = "$property $order";
        }

        return 'order by ' . implode(',', $suffix);
    }

    private function getWhere(Request $request, Model $model, string $resourceClass): Where
    {
        $properties = $model->getStructure()
            ->getFieldNames();
        $where = new Where();

        foreach ($properties as $property) {
            if ($request->query->has($property)) {
                $value = $request->query->get($property);
                $where = $this->getClauseFilter($resourceClass, 'get', $where, $property, $value);
            }
        }

        return $where;
    }

    private function getClauseFilter(string $resourceClass, string $operationName, Where $where, string $property, $value): Where
    {
        $resourceMetadata = $this->resourceMetadataFactory->create($resourceClass);
        $resourceFilters = $resourceMetadata->getCollectionOperationAttribute($operationName, 'filters', [], true);

        if (empty($resourceFilters)) {
            return $where;
        }

        foreach ($resourceFilters as $filterName) {
            $filter = $this->getFilter($filterName);
            if ($filter instanceof FilterInterface) {
                $where = $filter->addClause($property, $value, $resourceClass, $where);
            } elseif ($property === $filterName) {
                $where->andWhere("$property = \$*", [$value]);
            }
        }

        return $where;
    }
}
