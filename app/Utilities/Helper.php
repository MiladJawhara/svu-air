<?php

namespace App\Utilities;

use App\Constants\GlobalConstants;

class Helper
{
    public static function cleanStr($string)
    {
        $string = preg_replace("/[^A-Za-z\p{Arabic}\s0-9]/um", " ", $string);
        $string = preg_replace("/\p{M}/um", " ", $string);
        $string = preg_replace("/[؟،ـ؛]/um", " ", $string);
        $string = preg_replace("/(\s{2,})/um", " ", $string);

        return trim($string);
    }

    public static function cleanStrForExtractKeywords($string)
    {
        $string = static::cleanStr($string);
        $string = strtolower($string);

        $unwantedKeywords = array_map(function (string $item) {
            $item = strtolower($item);
            return "/\b$item\b/um";
        }, GlobalConstants::getUnwantedKeywords());

        $string = preg_replace($unwantedKeywords, " ", $string);
        $string = preg_replace("/\d/um", " ", $string);
        $string = preg_replace("/\b[^\s]{1,2}\b/um", " ", $string);

        return static::cleanStr($string);
    }


    public static function hilightWordInText(string $text, string $word)
    {
        return preg_replace("/\b$word\b/ui", "<span style='background-color: lightgray; color:black; padding: 0.2rem 0.5rem; border-radius: 99999rem'>$word</span>", $text);
    }
}
