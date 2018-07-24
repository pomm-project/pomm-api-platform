<?php
/**
 * This file is part of the pomm-api-platform package.
 *
 */

declare(strict_types = 1);

namespace PommProject\ApiPlatform\Filter;

/**
 * @author Mikael Paris <stood86@gmail.com>
 */
class PgType
{
    const ARRAY = [
        'array'
    ];

    const BOOLEAN = [
        'bool',
        'pg_catalog.bool',
        'boolean'
    ];

    const INTEGER = [
        'int2',
        'pg_catalog.int2',
        'int4',
        'pg_catalog.int4',
        'int',
        'integer',
        'int8',
        'pg_catalog.int8'
    ];

    const FLOAT = [
        'numeric',
        'pg_catalog.numeric',
        'float4',
        'pg_catalog.float4',
        'float',
        'float8',
        'pg_catalog.float8',
        'oid',
        'pg_catalog.oid'
    ];

    const STRING = [
        'varchar', 'pg_catalog.varchar',
        'char', 'pg_catalog.char',
        'text', 'pg_catalog.text',
        'uuid', 'pg_catalog.uuid',
        'tsvector', 'pg_catalog.tsvector',
        'xml', 'pg_catalog.xml',
        'bpchar', 'pg_catalog.bpchar',
        'name', 'pg_catalog.name',
        'character varying',
        'regclass', 'pg_catalog.regclass',
        'regproc', 'pg_catalog.regproc',
        'regprocedure', 'pg_catalog.regprocedure',
        'regoper', 'pg_catalog.regoper',
        'regoperator', 'pg_catalog.regoperator',
        'regtype', 'pg_catalog.regtype',
        'regrole', 'pg_catalog.regrole',
        'regnamespace', 'pg_catalog.regnamespace',
        'regconfig', 'pg_catalog.regconfig',
        'regdictionary', 'pg_catalog.regdictionary',
        'inet', 'pg_catalog.inet',
        'cidr', 'pg_catalog.cidr',
        'macaddr', 'pg_catalog.macaddr'
    ];

    const TIMESTAMP = [
        'timestamp', 'pg_catalog.timestamp',
        'date', 'pg_catalog.date',
        'time', 'pg_catalog.time',
        'timestamptz', 'pg_catalog.timestamptz'
    ];

    const INTERVAL = [
        'interval',
        'pg_catalog.interval'
    ];

    const BINARY = [
        'bytea',
        'pg_catalog.bytea'
    ];

    const POINT = [
        'point',
        'pg_catalog.point'
    ];

    const CIRCLE = [
        'circle',
        'pg_catalog.circle'
    ];

    const JSON = [
        'json',
        'jsonb',
        'pg_catalog.json',
        'pg_catalog.jsonb'
    ];

    const NUMBER_RANGE = [
        'int4range',
        'pg_catalog.int4range',
        'int8range',
        'pg_catalog.int8range',
        'numrange',
        'pg_catalog.numrange'
    ];

    const TS_RANGE = [
        'tsrange',
        'pg_catalog.tsrange',
        'daterange',
        'pg_catalog.daterange',
        'tstzrange',
        'pg_catalog.tstzrange'
    ];

    const ARRAY_TYPE = 'array';
    const BOOLEAN_TYPE = 'bool';
    const INTEGER_TYPE = 'int';
    const FLOAT_TYPE = 'float';
    const STRING_TYPE = 'string';
    const TIMESTAMP_TYPE = 'timestamp';
    const INTERVAL_TYPE = 'interval';
    const BINARY_TYPE = 'binary';
    const POINT_TYPE = 'point';
    const CIRCLE_TYPE = 'circle';
    const JSON_TYPE = 'json';
    const NUMBER_RANGE_TYPE = 'number_range';
    const TS_RANGE_TYPE = 'ts_range';

    const TYPE_AVAILABLE = [
        'ARRAY', 'BOOLEAN', 'INTEGER', 'FLOAT', 'STRING',
        'TIMESTAMP', 'INTERVAL', 'BINARY', 'POINT', 'CIRCLE',
        'JSON', 'NUMBER_RANGE', 'TS_RANGE'
    ];

    public static function getTypePhp(string $typePg): string
    {
        foreach (self::TYPE_AVAILABLE as $type) {
            $valuesType = constant('self::' . $type);

            if (in_array($typePg, $valuesType)) {
                return constant('self::' . $type . '_TYPE');
            }
        }

        return 'string';
    }

}