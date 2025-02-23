<?php

namespace AValidator\Schemas;

class TesteSchema extends AbstractSchema {
    public static function teste()
    {
        return [
            'id' => self::string(true),
            'data' => self::array(true, [
                'name' => self::string(true),
                'age' => self::integer(true),
                'position' => self::array(true, [
                    'lat' => self::numberInterval(true, -80, 80),
                    'lng' => self::numberInterval(true, -180, 180)
                ]),
                'email' => self::string()
            ])
        ];
    }
}