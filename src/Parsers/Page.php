<?php
namespace App\Parsers;

use App\Metrics\MetricFactory;
use GuzzleHttp\Client;
use GuzzleHttp\ClientInterface;
use App\Factors\Factor;

class Page
{
    public $config;

    public $content;

    /**
     * @var array Web page factors values
     */
    public $factors = [];

    public $client;

    public $parser;

    public $dom;

    public $url;


    public function __construct(
        string $url
    ) {
        $this->client = new Client();
        $this->parser = new Parser();
        $this->setUpUrl($url);
        $this->getContent();
    }

    public function getContent() :void
    {
        $this->content = file_get_contents($this->url);
    }

    /**
     * Verifies URL and sets up some basic metrics.
     *
     * @param string $url
     * @return string URL
     */
    protected function setUpUrl(string $url): void
    {
        $parsedUrl = parse_url($url);
//        $this->setFactor(Factor::URL_PARSED, $parsedUrl);
        $this->url = $url;
    }


    /**
     * Parses page's html content setting up related metrics.
     */
    public function parse()
    {
        $this->parser->setContent($this->content);
        $this->setFactors([
            Factor::TITLE => $this->parser->getTitle(),
        ]);
    }

    public function setMetrics()
    {
        $this->initializeFactors();
        return $this->setUpMetrics();
    }

    private function initializeFactors()
    {
        if (empty($this->dom)) {
            $this->parse();
        }
    }

    public function setUpMetrics()
    {
        $metricFactory = new MetricFactory();
        $metrics = [];
        foreach ($this->factors as $factor => $value) {
            $metricObject = MetricFactory::get($factor);
            $metricObject->setValue($value);
            $metrics[$factor] = $metricObject;
        }
        return $metrics;
    }

    public function setFactor(string $name, $value) :void
    {
        if (count(explode('.', $name)) > 1) {
            $this->setArrayByDot($this->factors, $name, $value);
        } else {
            $this->factors[$name] = $value;
        }
    }

    /**
     * Sets array values using string with dot notation.
     *
     * @param array $array Array to be updated
     * @param string $path Dot notated string
     * @param mixed $val Value to be set in array
     * @return array
     */
    protected function setArrayByDot(array &$array, string $path, $val)
    {
        $loc = &$array;
        foreach (explode('.', $path) as $step) {
            $loc = &$loc[$step];
        }
        return $loc = $val;
    }

    /**
     * Sets multiple page factors values at once.
     *
     * @param array $factors
     */
    public function setFactors(array $factors)
    {
        foreach ($factors as $factorName => $factorValue) {
            $this->setFactor($factorName, $factorValue);
        }
    }

    public function getFactor($name)
    {
        if (!empty($this->factors[$name])) {
            return $this->factors[$name];
        }
        return false;
    }

}