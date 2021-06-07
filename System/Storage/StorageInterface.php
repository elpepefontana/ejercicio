<?php

namespace System\Storage;

interface StorageInterface
{
    public function select(string $tableName, $fields, array $data, array $order, $limit = null): array;
    public function insert(string $tableName, array $data): array;
    public function update(string $tableName, array $data): array;
    public function delete(string $tableName, array $data): array;
}
