<?php
namespace App\Services;

use App\Parsers\Page;
use GuzzleHttp\Client;
use SeoAnalyzer\Metric\MetricInterface;

class Analyzer
{
    private $client;
    private $metrics = [];

    public function __construct()
    {
        $this->client = new Client();
    }

    public function analyzeUrl(string $url): array
    {
        $this->page = new Page($url, $this->client);
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
            throw new \Exception('No Page to analyze');
        }
        if (empty($this->metrics)) {
            $this->metrics = $this->getMetrics();
        }
        $results = [];

        foreach ($this->metrics as $metric) {
            if ($analysisResult = $metric->analyze()) {
                $results[$metric->name] = $this->formatResults($metric, $analysisResult);
            }
        }
        return $results;
    }

    public function getMetrics(): array
    {
        return $this->page->setMetrics();
    }

    protected function formatResults( $metric, string $results): array
    {
        return [
            'analysis' => $results,
            'name' => $metric->name,
            'description' => $metric->description,
            'value' => $metric->value,
            'negative_impact' => $metric->impact,
        ];
    }
}