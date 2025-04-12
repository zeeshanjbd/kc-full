<?php

namespace App\Repositories;

use App\Services\DBConnectionService;
use PDO;

abstract class BaseRepository
{
    protected PDO $pdo;
    protected string $table;

    public function __construct()
    {
        $this->pdo = DBConnectionService::connect();
    }

    /**
     * Fetch all rows from the table.
     *
     * @return array
     */
    public function fetchAll(): array
    {
        $stmt = $this->pdo->query(sprintf('SELECT * FROM %s', $this->table));
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Fetch a single row by its primary key (assumed `id`).
     *
     * @param string $id
     * @return array|null
     */
    public function fetchById(string $id): ?array
    {
        $sql  = sprintf('SELECT * FROM %s WHERE id = ?', $this->table);
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([$id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}
