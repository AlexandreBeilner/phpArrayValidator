<?php

namespace AValidator\Schemas;

abstract class AbstractSchema {
    protected static function string($required = false): array
    {
        return [
            'type' => 'string',
            'required' => $required
        ];
    }

    protected static function integer($required = false): array
    {
        return [
            'type' => 'integer',
            'required' => $required
        ];
    }

    protected static function numeric($required = false): array
    {
        return [
            'type' => 'numeric',
            'required' => $required
        ];
    }

    protected static function array($required, $values = []): array
    {
        return [
            'type' => 'array',
            'required' => $required,
            'values' => $values
        ];
    }

    protected static function numberInterval($required, $min, $max): array
    {
        return [
            'type' => 'numberInterval',
            'required' => $required,
            'props' => ['min' => $min, 'max' => $max ]
        ];
    }

}