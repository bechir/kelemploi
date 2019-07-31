<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Util;

class RegionCounter
{
    use AppDirectoriesTrait;

    private static $filename = __DIR__ . '/../../var/app/regions-count.txt';

    public static function count()
    {
        return self::getRegionsSlugs();
    }

    public static function getRegionsSlugs()
    {
        $file = fopen(self::$filename, 'r');

        $regions = [];

        while ($line = fgets($file)) {
            $region = explode(':', $line);
            $regions[trim($region[0])] = (int) (trim($region[1]));
        }

        fclose($file);

        return $regions;
    }

    public static function writeRegionsSlugs($regions)
    {
        file_put_contents(self::$filename, '');
        $file = fopen(self::$filename, 'a+');

        foreach ($regions as $key => $value) {
            fwrite($file, "$key: $value\n");
        }
        fclose($file);
    }

    public static function increment($slug)
    {
        $data = self::getRegionsSlugs();
        $found = false;

        foreach ($data as $key => $value) {
            if ($slug === $key) {
                ++$data[$key];
                $found = true;
                break;
            }
        }

        ++$data['all'];

        if (!$found) {
            throw new \Exception("Le slug $slug est introuvable.");
        }

        self::writeRegionsSlugs($data);
    }

    public static function decrement($slug)
    {
        $data = self::getRegionsSlugs();
        $found = false;

        foreach ($data as $key => $value) {
            if ($slug === $key || $key == 'all') {
                --$data[$key];
                if ($data[$key] < 0) {
                    $data[$key] = 0;
                }
                $found = true;

                break;
            }
        }

        --$data['all'];

        if (!$found) {
            throw new \Exception("Le slug $slug est introuvable.");
        }

        self::writeRegionsSlugs($data);
    }
}
