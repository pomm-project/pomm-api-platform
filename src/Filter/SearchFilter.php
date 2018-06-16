<?php

declare(strict_types = 1);

namespace PommProject\ApiPlatform\Filter;

use ApiPlatform\Core\Exception\InvalidArgumentException;
use PommProject\Foundation\Where;

/**
 * @author Mikael Paris <stood86@gmail.com>
 */
class SearchFilter extends Filter implements FilterInterface
{
    /**
     * @var string Exact matching
     */
    const STRATEGY_EXACT = 'exact';

    /**
     * @var string The value must be contained in the field
     */
    const STRATEGY_PARTIAL = 'partial';

    /**
     * @var string Finds fields that are starting with the value
     */
    const STRATEGY_START = 'start';

    /**
     * @var string Finds fields that are ending with the value
     */
    const STRATEGY_END = 'end';

    /**
     * @var string Finds fields that are starting with the word
     */
    const STRATEGY_WORD_START = 'word_start';

    public function getDescription(string $resourceClass): array
    {
        $description = [];

        foreach ($this->properties as $property => $strategy)
        {
            $description[$property] = [
                'property' => $property,
                'strategy' => $strategy,
                'type' => $this->getTypePhp($property, $resourceClass),
                'required' => false
            ];

            if (self::STRATEGY_EXACT === $strategy) {
                $description[$property.'[]'] = $description[$property];
            }
        }

        return $description;
    }

    public function addClause(string $property, $value, string $resourceClass, Where $where): Where
    {
        if (isset($this->properties[$property])) {
            $where = $this->andWhereByStrategy($property, $value, $this->properties[$property], $where);
        }

        return $where;
    }

    protected function andWhereByStrategy(string $property, $value, string $strategy = self::STRATEGY_EXACT, Where $where): Where
    {
        $operator = 'like';
        $caseSensitive = true;

        if (0 === strpos($strategy, 'i')) {
            $strategy = substr($strategy, 1);
            $operator = 'ilike';
            $caseSensitive = false;
        }

        switch ($strategy) {
            case self::STRATEGY_EXACT:
                if (!$caseSensitive) {
                    $property = sprintf('lower(%s)', $property);
                    $value    = sprintf('lower(%s)', $value);
                }

                if (is_array($value)) {
                    $whereIn = Where::createWhereIn($property, $value);
                    $where->andWhere($whereIn);
                } else {
                    $where->andWhere("$property = \$*", [$value]);
                }
                break;
            case self::STRATEGY_PARTIAL:
                $where->andWhere("$property $operator \$*", ["%$value%"]);
                break;
            case self::STRATEGY_START:
                $where->andWhere("$property $operator \$*", ["$value%"]);
                break;
            case self::STRATEGY_END:
                $where->andWhere("$property $operator \$*", ["%$value"]);
                break;
            case self::STRATEGY_WORD_START:
                $where->andWhere("$property $operator \$* or $property $operator \$*", ["$value%", "% $value%"]);
                break;
            default:
                throw new InvalidArgumentException(sprintf('strategy %s does not exist.', $strategy));
        }

        return $where;
    }

    private function getTypePhp(string $property, string $resourceClass): string
    {
        $typePg = $this->getTypePgForProperty($property, $resourceClass);

        return PgType::getTypePhp($typePg);
    }
}