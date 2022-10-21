<?php
namespace App\Constants;


class BooleanConstants{

    public const OPERATOR_AND = "and";
    public const OPERATOR_OR = "or";
    public const OPERATOR_NOT = "not";


    public static function getOperators(){
        return [
            static::OPERATOR_AND,
            static::OPERATOR_OR,
            static::OPERATOR_NOT,
        ];
    }
}
