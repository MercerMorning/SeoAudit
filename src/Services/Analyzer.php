<?php
namespace App\Services;

use App\Parsers\Page;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;

class Analyzer
{
    private $locale = 'en_GB';
    private $client;
    private $metrics = [];

    public function __construct($page = null, ClientInterface $client = null)
    {
        $this->client = $client;
        if (empty($client)) {
            $this->client = new Client();
        }

        if (!empty($page)) {
            $this->page = $page;
        }
    }

    public function analyzeUrl(string $url, string $keyword = null, string $locale = null): array
    {
        if (!empty($locale)) {
            $this->locale = $locale;
        }
        $this->page = new Page($url, $locale, $this->client);
        if (!empty($keyword)) {
            $this->page->keyword = $keyword;
        }
        return $this->analyze();
    }

    /**
     * Starts analysis of a Page.
     *
     * @return array
     * @throws ReflectionException
     */
    public function analyze()
    {
        if (empty($this->page)) {
            throw new \Exception();
//            throw new InvalidArgumentException('No Page to analyze');
        }

        if (empty($this->metrics)) {
            $this->metrics = $this->getMetrics();
        }
        $results = [];
//        dd($this->metrics);
//        dd($this->metrics);
        foreach ($this->metrics as $metric) {
            if ($analysisResult = $metric->analyze()) {
                dd($analysisResult);
                $results[$metric->name] = $this->formatResults($metric, $analysisResult);
            }
        }
        return $results;
    }

    public function getMetrics(): array
    {
        return array_merge($this->page->getMetrics());
    }
}