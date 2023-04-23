<?php

namespace App\Model;

use PDO;

class ActualityManager extends AbstractManager
{
    public const TABLE = 'actuality';

    public function insert(array $actuality): int
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE .
                " (`title`, `content`, `creation_date`, `image_path`) " .
                " VALUES (:title , :content, now(), :image_path)"
        );

        $statement->bindValue('title', $actuality['title'], PDO::PARAM_STR);
        $statement->bindValue('content', $actuality['content'], PDO::PARAM_STR);
        $statement->bindValue('image_path', ($actuality['image_path']) ?? null, PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
