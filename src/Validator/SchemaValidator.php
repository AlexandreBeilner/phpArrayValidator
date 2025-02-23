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

    public function validate($data, $schema): void
    {
        foreach ($schema as $key => $item) {
            $this->verifyIsRequired($data, $item, $key);
            if (! $item['required'] && empty($data[$key])) {
                continue;
            }

            $this->verifyMethodExists($item['type']);

            $this->verifyDataType($data[$key], $item, $key);

            if (key_exists('values', $item)) {
                $this->validate($data[$key], $item['values']);
            }
        }
    }

    private function verifyIsRequired($data, $item, $key): void
    {
        if ((! key_exists($key, $data) || empty($data[$key])) && $item['required']) {
            print_r("O valor de $key é obrigatório");exit;
        }
    }

    private function verifyMethodExists(string $type): void
    {
        if (empty($this->validators[$type])) {
            print_r("O validador do tipo $type nao existe!");exit;
        }
    }

    private function verifyDataType($value, $schemaType, $key): void
    {
        $method = $this->validators[$schemaType['type']];
        if (! $this->$method($value, $schemaType['props'] ?? '')) {
            print_r("O campo $key está no formato errado, verefique a documentacao");
            exit;
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