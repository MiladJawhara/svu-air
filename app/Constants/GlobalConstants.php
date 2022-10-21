<?php
namespace App\Constants;
/**@var array $arabicExcludedWords */

class GlobalConstants
{
    const LANGUAGE_EN = 1;
    const LANGUAGE_AR = 2;
    static $arabicExcludedWords = [];
    static $englishExcludedWords = [];

    public static function getUnwantedKeywords()
    {

        static::$arabicExcludedWords = empty(static::$arabicExcludedWords)
        ? require(__DIR__ . "/../StaticFiles/wordsExcluded/arabic.php")
        : static::$arabicExcludedWords;

        static::$englishExcludedWords = empty(static::$englishExcludedWords)
        ? require(__DIR__ . "/../StaticFiles/wordsExcluded/english.php")
        : static::$englishExcludedWords;

        return [...static::$englishExcludedWords, ...static::$arabicExcludedWords];
    }
}

