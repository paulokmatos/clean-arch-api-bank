<?php

namespace App\Infra\Database;

interface ISqlAdapter
{
    public function query(string $sql, array $params = []): array;
    public function execute(string $sql, array $params = []): bool;
    public function beginTransaction(): bool;
    public function commit(): bool;
    public function rollBack(): bool;
}
