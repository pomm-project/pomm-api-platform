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

use ApiPlatform\Core\DataProvider\PaginatorInterface;
use PommProject\Foundation\Pager;

/**
 * Extends the Pomm pager.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 */
final class Paginator implements \IteratorAggregate, PaginatorInterface
{
    private $paginator;

    public function __construct(Pager $paginator)
    {
        $this->paginator = $paginator;
    }

    public function getCurrentPage(): float
    {
        return $this->paginator->getPage();
    }

    public function getLastPage(): float
    {
        return $this->paginator->getLastPage();
    }

    public function getItemsPerPage(): float
    {
        return $this->paginator->getMaxPerPage();
    }

    public function getTotalItems(): float
    {
        return $this->paginator->getResultCount();
    }

    public function count()
    {
        return $this->paginator->getTotalItems();
    }

    public function getIterator()
    {
        return $this->paginator->getIterator();
    }
}
