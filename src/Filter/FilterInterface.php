<?php
/**
 * This file is part of the pomm-api-platform package.
 *
 */

declare(strict_types = 1);

namespace PommProject\ApiPlatform\Filter;

use ApiPlatform\Core\Api\FilterInterface as BaseFilterInterface;
use PommProject\Foundation\Where;

/**
 * @author Mikael Paris <stood86@gmail.com>
 */
interface FilterInterface extends BaseFilterInterface
{

    public function addClause(string $property, $value, string $resourceClass, Where $where): Where;
}