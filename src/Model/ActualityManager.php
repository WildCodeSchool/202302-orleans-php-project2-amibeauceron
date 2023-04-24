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
}
