<?php

namespace App\Support\Database;

trait CacheQueryBuilder
{
    protected function newBaseQueryBuilder()
    {
        $conn = $this->getConnection();
        $grammar = $conn->getQueryGrammar();
        return new Builder($conn, $grammar, $conn->getPostProcessor());
    }
}
