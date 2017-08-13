<?php

namespace AppBundle\Entity;

use PommProject\ModelManager\Model\Model;
use PommProject\ModelManager\Model\Projection;
use PommProject\ModelManager\Model\ModelTrait\WriteQueries;

use PommProject\Foundation\Where;

use AppBundle\Entity\AutoStructure\Config as ConfigStructure;
use AppBundle\Entity\Config;

/**
 * ConfigModel
 *
 * Model class for table config.
 *
 * @see Model
 */
class ConfigModel extends Model
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
        $this->structure = new ConfigStructure;
        $this->flexible_entity_class = '\AppBundle\Entity\Config';
    }
}
