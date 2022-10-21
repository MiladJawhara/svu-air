<?php

namespace App\Utilities;

use App\Classes\BooleanExpression;
use App\Classes\BooleanNode;
use App\Classes\IBooleanExpression;
use App\Constants\BooleanConstants;
use App\Models\Keyword;
use App\Models\KeywordQuestion;

class BooleanParser
{
    public static function pars(string $text): IBooleanExpression
    {
        $words = explode(" ", $text);

        for ($i = 0; $i < count($words); $i++) {
            $word = Helper::cleanStr($words[$i]);

            if (in_array($word, BooleanConstants::getOperators())) {
                $node = static::createBooleanNode(Helper::cleanStrForExtractKeywords($words[$i - 1] ?? ""));
                if (!empty($words[$i + 1])) {
                    $indexOfNext = strpos($text, $words[$i + 1]);
                    $newText = substr($text, $indexOfNext);
                    return new BooleanExpression($node, $word, static::pars($newText));
                }
            }
        }

        return static::createBooleanNode(Helper::cleanStrForExtractKeywords($text));

    }

    public static function getKeywords(string $text): array
    {

    }

    public static function createBooleanNode($keywordStr)
    {

        $keyword = Keyword::where(['text' => $keywordStr])->first();

        if (empty($keyword)) return new BooleanNode("", []);

        $questionsIds = [];

        if (!empty($keyword)) {
            $questionsIds = array_map(function ($item) {
                return $item['question_id'];
            }, KeywordQuestion::select(['question_id'])->where(['keyword_id' => $keyword->id])->get()->toArray());
        }
        return new BooleanNode($keyword->text, $questionsIds);
    }
}
