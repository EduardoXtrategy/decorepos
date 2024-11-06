<?php

namespace Uzer\Core\Model;

class StringHelpers
{


    public static function startWith(string $text, string $start)
    {
        $firstChars = substr($text, 0, strlen($start));
        return strpos($firstChars, $start) === 0;
    }

    public static function replaceAccentMark(string $text)
    {
        return str_replace(array('á', 'é', 'í', 'ó', 'ú'), array('a', 'e', 'i', 'o', 'u'), $text);
    }
}
