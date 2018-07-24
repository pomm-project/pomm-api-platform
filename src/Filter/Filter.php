<?php
/**
 * This file is part of the pomm-api-platform package.
 *
 */

declare(strict_types = 1);

namespace PommProject\ApiPlatform\Filter;

use PommProject\Foundation\Pomm;
use PommProject\ModelManager\Exception\ModelException;
use PommProject\ModelManager\Model\RowStructure;

/**
 * @author Mikael Paris <stood86@gmail.com>
 */
abstract class Filter
{
    protected $pomm;

    protected $properties;

    public function __construct(Pomm $pomm, array $properties = [])
    {
        $this->pomm = $pomm;
        $this->properties = $properties;
    }

    protected function getTypePgForProperty(string $property, string $resourceClass): string
    {
        $structure = $this->getStructureForResourceClass($resourceClass);

        try{
            return $structure->getTypeFor($property);
        }catch (ModelException $e) {
            return 'varchar';
        }
    }

    protected function getFieldNamesForResource(string $resourceClass): array
    {
        $structure = $this->getStructureForResourceClass($resourceClass);

        return $structure->getFieldNames();
    }

    private function getStructureForResourceClass(string $resourceClass): RowStructure
    {
        $modelName = "${resourceClass}Model";
        $session = $this->pomm->getDefaultSession();

        return $session->getModel($modelName)->getStructure();
    }
}