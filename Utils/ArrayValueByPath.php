<?php
/**
 * Copyright © 2021 Studio Raz. All rights reserved.
 * See LICENSE.txt for license details.
 */

declare(strict_types=1);

namespace SR\Base\Utils;

class ArrayValueByPath
{
    /**
     * Returns a value in a nested array based on a path
     *
     * @param array  $array the array to modify
     * @param string $path the path in the array
     * @param string $delimiter the separator for the path
     *
     * @return mixed the value or null
     */
    public function get(array $array, string $path, string $delimiter = '/')
    {
        $pathParts           = explode($delimiter, $path);
        $currentArrayElement = $array;

        foreach ($pathParts as $key) {
            $currentArrayElement = &$currentArrayElement[$key];
        }

        return $currentArrayElement;
    }

    /**
     * Sets a value in a nested array based on a path
     *
     * @param array  $array the array to modify
     * @param string $path the path in the array
     * @param mixed  $value the value to set
     * @param string $delimiter the separator for the path
     */
    public function set(array &$array, string $path, $value, string $delimiter = '/')
    {
        $pathParts           = explode($delimiter, $path);
        $currentArrayElement = &$array;

        foreach ($pathParts as $key) {
            $currentArrayElement = &$currentArrayElement[$key];
        }

        $currentArrayElement = $value;
    }
}
