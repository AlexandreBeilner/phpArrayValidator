<?php

require __DIR__ . '/../vendor/autoload.php';

use AValidator\Schemas\TesteSchema;
use AValidator\Validator\SchemaValidator;

$schemaValidator = new SchemaValidator();

$data1 = [
    'id' => 'xandaoID',
    'data' => [
        'name' => 'Alexandre',
        'age'=> '20',
        'position' => [
            'lat' => '-12.00098',
            'lng' => '-179.00998'
        ],
        'email' => '',
    ]
];

$data2 = [
    'name' => 'Alexandre',
    'age'=> '20',
    'lat' => '-12.00098',
    'lng' => '-179.00998',
    'email' => 'ale@gmail.com',
];


try {
    $schemaValidator->validate($data1, TesteSchema::teste(), ['validateExtraKeys' => true]);
} catch (Exception $e) {
    print_r($e->getMessage());
}
try {
    $schemaValidator->validate($data2, TesteSchema::testeDois(), ['validateExtraKeys' => true]);
} catch (Exception $e) {
    print_r($e->getMessage());
}
