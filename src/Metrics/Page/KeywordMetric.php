<?php

namespace App\Metrics\Page;

use App\Metrics\AbstractMetric;

class KeywordMetric extends AbstractMetric
{
    public $description = 'Does it contain a key phrase?';

    /**
     * @inheritdoc
     */
    public function analyze(): string
    {
        $this->name = 'Keyword' . $this->value['type'];
        if (stripos($this->value['text'], $this->value['keyword']) === false) {
            $this->impact = $this->value['impact'];
            return 'Can not find the keyword phrase. Adding it could improve SEO';
        }
        return 'Good! Found the keyword phrase';
    }
}
