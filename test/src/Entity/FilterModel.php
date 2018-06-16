<?php

declare(strict_types = 1);

namespace AppBundle\Entity;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use AppBundle\Entity\AutoStructure\Filter as FilterStructure;
use AppBundle\Entity\Filter;

/**
 * FilterModel
 *
 * Model class for table filter.
 *
 * @see Model
 */
class FilterModel extends Model
{
    use WriteQueries;

    /**
     * __construct()
     *
     * Model constructor
     *
     * @access public
     */
    public function __construct()
    {
        $this->structure = new FilterStructure;
        $this->flexible_entity_class = Filter::class;
    }
}
