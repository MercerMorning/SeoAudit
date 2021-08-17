<?php
namespace App\Parsers;

class Parser extends AbstractParser
{
    public function getTitle(): string
    {
        if ($this->getDomElements('title')->length > 0) {
            return trim($this->getDomElements('title')->item(0)->nodeValue);
        }
        return '';
    }
}
