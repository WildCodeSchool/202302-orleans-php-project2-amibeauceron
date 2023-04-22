<?php

namespace App\Model;

use PDO;

class ActualityManager extends AbstractManager
{
    public const TABLE = 'actuality';

    public function getCount(): int|false
    {
        $query = "SELECT COUNT(*) AS nbr_actualities FROM " . self::TABLE;
        return $this->pdo->query($query)->fetchColumn();
    }
    public function selectAllWithPagination(
        int $firstRow,
        int $rowsPerPage,
        string $orderBy = '',
        string $direction = 'ASC'
    ): array {
        // prepared request
        $query = 'SELECT * FROM ' . static::TABLE;
        if ($orderBy) {
            $query .= ' ORDER BY :orderBy :direction';
        }
        $query .= ' LIMIT :firstRow, :rowsPerPage;';

        $statement = $this->pdo->prepare($query);
        if ($orderBy) {
            $statement->bindValue('orderBy', $orderBy, PDO::PARAM_STR);
            $statement->bindValue('direction', $direction, PDO::PARAM_STR);
        }
        $statement->bindValue('firstRow', $firstRow, PDO::PARAM_INT);
        $statement->bindValue('rowsPerPage', $rowsPerPage, PDO::PARAM_INT);
        $statement->execute();
        return $statement->fetchAll();
    }
}
