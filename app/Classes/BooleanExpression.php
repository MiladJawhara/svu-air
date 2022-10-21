<?php

namespace App\Classes;

use App\Constants\BooleanConstants;
use App\Models\Question;

class BooleanExpression implements IBooleanExpression
{

    private IBooleanExpression $leftExpression;
    private IBooleanExpression $rightExpression;
    private string $operator;

    public function __construct(IBooleanExpression $leftExpression, string $operator, IBooleanExpression $rightExpression)
    {
        $this->leftExpression = $leftExpression;
        $this->operator = $operator;
        $this->rightExpression = $rightExpression;
    }


    public function calculate(): array
    {
        $leftRes = $this->leftExpression->calculate();
        $rightRes = $this->rightExpression->calculate();

        switch ($this->operator) {
            case BooleanConstants::OPERATOR_NOT:
                return $this->applyNot($leftRes, $rightRes);
            case BooleanConstants::OPERATOR_OR:
                return $this->applyOr($leftRes, $rightRes);
            default :
                return $this->applyAnd($leftRes, $rightRes);
        }
    }


    private function applyAnd(array $left, array $right): array
    {
        $res = [];

        $arrayToSearch = $right;
        $arrayToCheck = $left;

        if (count($left) <= count($right)) {
            $arrayToSearch = $left;
            $arrayToCheck = $right;
        }

        foreach ($arrayToSearch as $key => $value) {
            if (in_array($value, $arrayToCheck)) {
                $res[] = $value;
            }
        }

        return $res;
    }

    private function applyOr(array $left, array $right): array
    {
        $res = [...$left, ...$right];

        return array_unique($res);
    }

    private function applyNot(array $left, array $right): array
    {
        $res = array_map(function ($item) {
            return $item['id'];
        }, Question::whereNotIn('id', $right)->get('id')->toArray());

        return empty($left) ? $res : $this->applyAnd($left, $res);
    }

    function getKeywords(): array
    {
        if ($this->operator != BooleanConstants::OPERATOR_NOT) {
            return [...$this->leftExpression->getKeywords(), ...$this->rightExpression->getKeywords()];
        }

        return [...$this->leftExpression->getKeywords()];
    }
}
