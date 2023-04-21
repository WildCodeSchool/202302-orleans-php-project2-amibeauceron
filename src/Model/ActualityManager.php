<?php

namespace App\Model;

use PDO;

class ActualityManager extends AbstractManager
{
    public const TABLE = 'actuality';

    public function selectLastThree(): array
    {
        $query = 'SELECT * FROM ' . self::TABLE . ' ORDER BY creation_date DESC LIMIT 3';
        return $this->pdo->query($query)->fetchAll();
    }
}
