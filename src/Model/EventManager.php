<?php

namespace App\Model;

use PDO;

class EventManager extends AbstractManager
{
    public const TABLE = 'evenement';

    public function insert(array $event): int
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE .
                " (`title`, `date`, `place`, `description`) " .
                " VALUES (:title , :date, :place , :description)"
        );

        $statement->bindValue('title', $event['title'], PDO::PARAM_STR);
        $statement->bindValue('date', $event['date'], PDO::PARAM_INT);
        $statement->bindValue('place', $event['place'], PDO::PARAM_STR);
        $statement->bindValue('description', $event['description'], PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
