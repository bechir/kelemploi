<?php

namespace App\Utils\CityFinder;

class Cities
{
    private static $cities;

    /**
     * @param string $country: The user's coutry
     *
     * @return array
     */
    public static function getCities(string $country = null): array
    {
        if (!self::$cities) {
            self::$cities = json_decode(file_get_contents(__DIR__ . '/cities.json'), true);
        }
        if (!$country || !isset(self::$cities[strtoupper($country)])) {
            return self::$cities;
        }

        return self::$cities[strtoupper($country)];
    }
}
