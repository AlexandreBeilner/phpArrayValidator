<?php

namespace AValidator\Validator;

class SchemaValidator
{
    private array $validators = [
        'string' => 'stringValidate',
        'integer' => 'integerValidate',
        'numeric' => 'numericValidate',
        'array' => 'arrayValidate',
        'numberInterval' => 'numberIntervalValidate'
    ];

    private array $errors = [];

    /**
     * @param array $data
     * @param array $schema
     * @param array{validateExtraKeys?: boolean} $options
     * @return void
     * @throws \Exception
     */
    public function validate(array $data, array $schema, array $options = []): void
    {
        $this->validateData($data, $schema, $options);

        if (count($this->errors) > 0) {
            throw new \Exception(join(PHP_EOL, $this->errors));
        }
    }

    private function validateData(array $data, array $schema, array $options = []): void
    {
        if ($options['validateExtraKeys']) {
            $this->verifyExtraKeys($data, $schema);
        }

        foreach ($schema as $key => $item) {
            if (! $this->verifyIsRequired($data, $item, $key)) continue;

            if (! $item['required'] && empty($data[$key])) continue;

            if (! $this->verifyDataType($data[$key], $item, $key)) continue;

            if (key_exists('values', $item)) {
                $this->validateData($data[$key], $item['values'], $options);
            }
        }
    }

    private function verifyIsRequired($data, $item, $key): bool
    {
        if ((! key_exists($key, $data) || empty($data[$key])) && $item['required']) {
            $this->errors[] = "O valor de \"$key\" é obrigatório";
            return false;
        }
        return true;
    }

    private function verifyDataType($value, $schemaType, $key): bool
    {
        $method = $this->validators[$schemaType['type']];
        if (! $this->$method($value, $schemaType['props'] ?? '')) {
            $this->errors[] = "O campo \"$key\" está no formato errado, verefique a documentacao";
            return false;
        }

        return true;
    }

    private function verifyExtraKeys($data, $schema): void
    {
        $dataKeys = array_keys($data);
        $schemaKeys = array_keys($schema);

        $allKeys = $dataKeys + $schemaKeys;

        if (count($allKeys) > count($schemaKeys)) {
            $invalidKeys = join(', ', array_diff($allKeys, $schemaKeys));
            $this->errors[] = "A(s) chave(s) \"$invalidKeys\" nao devem estar presentes, verefique a documentacao.";
        }
    }


    private function stringValidate($value): bool
    {
        return is_string($value);
    }

    private function integerValidate($value): bool
    {
        return is_numeric($value) && is_integer(intval($value));
    }

    private function numericValidate($value): bool
    {
        return is_numeric($value);
    }

    private function arrayValidate($value): bool
    {
        return is_array($value);
    }

    private function numberIntervalValidate($value, $props): bool
    {
        if (
            (! is_numeric($value)) ||
            (! empty($props['min']) && floatval($value) < $props['min']) ||
            (! empty($props['max']) && floatval($value) > $props['max'])
        )
        {
            return false;
        }

        return true;
    }
}