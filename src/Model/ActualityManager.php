<?php

namespace App\Model;

use PDO;

class ActualityManager extends AbstractManager
{
    public const TABLE = 'actuality';

    public function update(array $item): bool
    {
        $statement = $this->pdo->prepare("UPDATE " . self::TABLE .
            " SET `title` = :title, `content` = :content, `image_path` = :image_path " .
            " WHERE id=:id");
        $statement->bindValue('id', $item['id'], PDO::PARAM_INT);
        $statement->bindValue('title', $item['title'], PDO::PARAM_INT);
        $statement->bindValue('content', $item['content'], PDO::PARAM_INT);
        $statement->bindValue('image_path', $item['image_path'], PDO::PARAM_STR);
        return $statement->execute();
    }

    public function insert(array $actuality): int
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE .
                " (`title`, `content`, `creation_date`, `image_path`) " .
                " VALUES (:title , :content, now(), :image_path)"
        );

        $statement->bindValue('title', $actuality['title'], PDO::PARAM_STR);
        $statement->bindValue('content', $actuality['content'] ?? null, PDO::PARAM_STR);
        $statement->bindValue('image_path', ($actuality['image_path']) ?? null, PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }
}
