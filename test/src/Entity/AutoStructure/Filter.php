<?php
/**
 * This file has been automatically generated by Pomm's generator.
 * You MIGHT NOT edit this file as your changes will be lost at next
 * generation.
 */

declare(strict_types = 1);

namespace AppBundle\Entity\AutoStructure;

use PommProject\ModelManager\Model\RowStructure;

/**
 * Search
 *
 * Structure class for relation public.filter.
 *
 * Class and fields comments are inspected from table and fields comments.
 * Just add comments in your database and they will appear here.
 * @see http://www.postgresql.org/docs/9.0/static/sql-comment.html
 *
 *
 *
 * @see RowStructure
 */
class Filter extends RowStructure
{
    /**
     * __construct
     *
     * Structure definition.
     *
     * @access public
     */
    public function __construct()
    {
        $this
            ->setRelation('public.filter')
            ->setPrimaryKey(['name'])
            ->addField('name', 'varchar')
            ->addField('value', 'varchar')
            ->addField('value_partial', 'varchar')
            ->addField('value_start', 'varchar')
            ->addField('value_end', 'varchar')
            ->addField('value_word_start', 'varchar')
            ->addField('value_ipartial', 'varchar')
            ;
    }
}
