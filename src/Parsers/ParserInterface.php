<?php
namespace App\Parsers;


interface ParserInterface
{
    public function getMeta(): array;

    public function getTitle(): string;
}
