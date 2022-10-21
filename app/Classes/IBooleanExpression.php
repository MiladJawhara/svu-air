<?php
namespace App\Classes;

interface IBooleanExpression{
    function calculate(): array;
    function getKeywords(): array;
}
