<?php

namespace App\Classes;

class BooleanNode implements IBooleanExpression
{

    private array $questionsIds;
    private string $keyword;

    public function __construct(string $keyword, array $questionsIds)
    {
        $this->questionsIds = $questionsIds;
        $this->keyword = $keyword;
    }

    public function calculate(): array
    {
        return $this->questionsIds;
    }

    public function getKeywords(): array
    {
        return [$this->keyword];
    }
}
