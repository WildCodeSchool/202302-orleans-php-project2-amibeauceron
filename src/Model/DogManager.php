<?php

namespace App\Model;

use PDO;

class DogManager extends AbstractManager
{
    public const TABLE = 'dog';

    public function findDogs(array $search = []): array
    {
        $query = 'SELECT * FROM ' . self::TABLE . ' WHERE (0=0) ';
        if (isset($search['is_lof'])) {
            $query .= ' AND is_lof = :is_lof ';
        }
        $query .= ' ORDER BY name ';
        $statement = $this->pdo->prepare($query);
        if (isset($search['is_lof'])) {
            $statement->bindValue(':is_lof', $search['is_lof'], PDO::PARAM_STR);
        }
        $statement->execute();
        return $statement->fetchAll();
    }
}
