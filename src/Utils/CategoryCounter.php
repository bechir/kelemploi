<?php

namespace App\Utils;

class CategoryCounter
{
    private static $filename = __DIR__ . '/../../var/categories-count.txt';

    public static function count()
    {
        return self::getCategoriesSlugs();
    }

    public static function getCategoriesSlugs()
    {
        $file = fopen(self::$filename, "r");

        $categories = [];

        while ($line = fgets($file)) {
            $category = explode(':', $line);
            $categories[trim($category[0])] = intval(trim($category[1]));
        }

        fclose($file);
        return $categories;
    }

    public static function writeCategoriesSlugs($categories)
    {
        file_put_contents(self::$filename, "");
        $file = fopen(self::$filename, "a+");

        foreach ($categories as $key => $value) {
            fputs($file, "$key: $value\n");
        }
        fclose($file);
    }

    public static function increment($slug)
    {
        $data = self::getCategoriesSlugs();
        $found = false;

        foreach ($data as $key => $value) {
            if ($slug == $key) {
                $data[$key]++;
                $found = true;
                break;
            }
        }

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
            if ($slug == $key) {
                $data[$key]--;
                if($data[$key] < 0) {
                  $data[$key] = 0;
                }
                $found = true;
                break;
            }
        }

        if (!$found) {
            throw new \Exception("Le slug $slug est introuvable.");
        }

        self::writeCategoriesSlugs($data);
    }
}
