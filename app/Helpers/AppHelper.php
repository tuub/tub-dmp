<?php

class AppHelper {
    public static function varDump($var)
    {
        ob_start();
        var_dump($var);
        $output = ob_get_clean();

        // Add formatting
        $output = preg_replace("/\]\=\>\n(\s+)/m", "] => ", $output);

        $output = '<pre style="background: #FFFEEF; color: #000; border: 1px dashed #888; padding: 10px; margin: 10px 0; text-align: left;">'.$output.'</pre>';

        echo $output;
    }


    public static function isEmpty($value)
    {
        if (is_string($value)) {
            if (empty($value) || strlen($value) == 0) {
                return true;
            }
        }

        return false;
    }


    public static function isEmptyArray($array)
    {
        if (is_array($array)) {
            foreach($array as $key => $value) {
                if (!empty($value)) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function hasKeys($array, $keys)
    {
        if (is_array($array)) {
            foreach ($keys as $key) {
                if (!array_key_exists($key, $array)) {
                    return false;
                }
            }
        }

        return true;
    }

    public static function hasEmptyValues($array)
    {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (empty($value) || strlen($value) == 0) {
                    return true;
                }
            }
        }

        return false;
    }

    public static function camelCaseToWord(string $string)
    {
        $pattern = '/
              (?<=[a-z])
              (?=[A-Z])
            | (?<=[A-Z])
              (?=[A-Z][a-z])
            /x';

        return implode(' ', preg_split($pattern, $string));
    }
}