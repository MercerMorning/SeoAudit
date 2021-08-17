<?php
namespace App\Parsers;

use DOMDocument;
use DOMElement;
use DOMNodeList;

abstract class AbstractParser implements ParserInterface
{
    protected $dom;

    public function __construct(string $html = null)
    {
        $this->dom = new DOMDocument();
        if (!empty($html)) {
            $this->setContent($html);
        }
    }

    protected function getDomElements(string $name): DOMNodeList
    {
        return $this->dom->getElementsByTagName($name);
    }

    public function setContent($html)
    {
        $internalErrors = libxml_use_internal_errors(true);
        $this->dom->loadHTML($html, LIBXML_NOWARNING);
        libxml_use_internal_errors($internalErrors);
    }
}
