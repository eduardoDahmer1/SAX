<?php

namespace App\Support\Database;
use Cache;
use Illuminate\Database\Query\Builder as QueryBuilder;

class Builder extends QueryBuilder
{
    protected function runSelect()
    {
        return Cache::store('request')->tags($this->from)->remember($this->getCacheKey(), 5, function() {
            return parent::runSelect();
        });
    }
    protected function getCacheKey()
    {
        return json_encode([
            $this->toSql() => $this->getBindings()
        ]);
    }
}
