<?php

namespace App\Metrics\Page;

use App\Metrics\AbstractMetric;

class TitleMetric extends AbstractMetric
{
    public $description = 'Does it contain a key phrase?';

    /**
     * @inheritdoc
     */
    public function analyze(): string
    {
        return $this->checkTitleTag();
    }

    private function checkTitleTag()
    {
        return isset($this->value);
    }
}
