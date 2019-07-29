<?php

/*
 * This file is part of the Kelemploi application.
 *
 * (C) Bechir Ba <bechiirr71@gmail.com>
 */

namespace App\Util;

class CategoryCounter
{
    use AppDirectoriesTrait;

    private static $filename = __DIR__ . '/../../var/app/categories-count.txt';

    public static function count()
    {
        return self::getCategoriesSlugs();
    }

    public static function getCategoriesSlugs()
    {
        $file = fopen(self::$filename, 'r');

        $categories = [];

        while ($line = fgets($file)) {
            $category = explode(':', $line);
            $categories[trim($category[0])] = (int) (trim($category[1]));
        }

        fclose($file);

        return $categories;
    }

    public static function writeCategoriesSlugs($categories)
    {
        file_put_contents(self::$filename, '');
        $file = fopen(self::$filename, 'a+');

        foreach ($categories as $key => $value) {
            fwrite($file, "$key: $value\n");
        }
        fclose($file);
    }

    public static function increment($slug)
    {
        $data = self::getCategoriesSlugs();
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

        self::writeCategoriesSlugs($data);
    }

    public static function decrement($slug)
    {
        $data = self::getCategoriesSlugs();
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

        self::writeCategoriesSlugs($data);
    }
}
