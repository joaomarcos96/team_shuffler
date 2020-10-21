<?php

namespace App\Utils;

class ArrayUtils
{
    public static function shuffleEveryElement(array $value)
    {
        $newArray = [];

        foreach ($value as $key => $element) {
            $newArray[$key] = ArrayUtils::shuffle($element);
        }

        return $newArray;
    }

    public static function shuffle(array $value)
    {
        $keys = array_keys($value);

        shuffle($keys);

        $random = [];

        foreach ($keys as $key) {
            $random[$key] = $value[$key];
        }

        return $random;
    }
}
