<?php

namespace App\Metrics;

use App\Metrics\Page\TitleMetric;
use ReflectionException;

class MetricFactory
{
    /**
     * @param string $key
     * @param null $inputData
     * @return mixed
     * @throws ReflectionException
     */
    public static function get(string $key)
    {
        return self::$key();
    }

    public static function title()
    {
        return new TitleMetric();
    }
}
