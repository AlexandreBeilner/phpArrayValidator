<?php

require __DIR__ . '/../vendor/autoload.php';

use AValidator\Schemas\TesteSchema;
use AValidator\Validator\SchemaValidator;

$schemaValidator = new SchemaValidator();

$data1 = [
    'id' => 'xandaoID',
    'data' => [
        'name' => 'Alexandre',
        'age'=> 20,
        'position' => [
            'lat' => '-12.00098',
            'lng' => '-179.00998'
        ]
    ]
];


$schemaValidator->validate($data1, TesteSchema::teste());
var_dump(true);exit;