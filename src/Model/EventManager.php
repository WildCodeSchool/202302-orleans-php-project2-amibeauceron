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
                " (`title`, `date`, `place`, `description`, `image`) " .
                " VALUES (:title , :date, :place , :description, :image)"
        );

        $statement->bindValue('title', $event['title'], PDO::PARAM_STR);
        $statement->bindValue('date', $event['date'], PDO::PARAM_STR);
        $statement->bindValue('place', $event['place'], PDO::PARAM_STR);
        $statement->bindValue('description', $event['description'], PDO::PARAM_STR);
        $statement->bindValue('image', ($event['image'] ?? null), PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $event): bool
    {
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE .
                " SET `title` = :title, `date` = :date, `place` = :place,
                 `description` = :description, `image` = :image " .
                " WHERE id=:id"
        );
        $statement->bindValue('id', $event['id'], PDO::PARAM_INT);
        $statement->bindValue('title', $event['title'], PDO::PARAM_STR);
        $statement->bindValue('date', $event['date'], PDO::PARAM_STR);
        $statement->bindValue('place', $event['place'], PDO::PARAM_STR);
        $statement->bindValue('description', $event['description'], PDO::PARAM_STR);
        $statement->bindValue('image', $event['image'], PDO::PARAM_STR);
        return $statement->execute();
    }
}
