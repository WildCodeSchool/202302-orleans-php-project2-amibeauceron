<?php

namespace App\Model;

use PDO;

class DogManager extends AbstractManager
{
    public const TABLE = 'dog';

    public function insert(array $dog): int
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE .
                " (`owner`, `owner_city`, `owner_email`, `name`, `gender`,`birthdate`, " .
                " `is_lof`,`identity_number`,`image`,`description`) " .
                " VALUES (:owner, :owner_city, :owner_email, :name, :gender, " .
                ":birthdate, :is_lof, :identity_number, :image, :description)"
        );

        $statement->bindValue('owner', $dog['owner'], PDO::PARAM_STR);
        $statement->bindValue('owner_city', $dog['owner_city'], PDO::PARAM_STR);
        $statement->bindValue('owner_email', $dog['owner_email'], PDO::PARAM_STR);
        $statement->bindValue('name', $dog['name'], PDO::PARAM_STR);
        $statement->bindValue('gender', $dog['gender'], PDO::PARAM_STR);
        $statement->bindValue('birthdate', (empty($dog['birthdate'])) ? null : $dog['birthdate'], PDO::PARAM_STR);
        $statement->bindValue('is_lof', $dog['is_lof'], PDO::PARAM_BOOL);
        $statement->bindValue('identity_number', $dog['identity_number'], PDO::PARAM_STR);
        $statement->bindValue('image', $dog['image'], PDO::PARAM_STR);
        $statement->bindValue('description', $dog['description'], PDO::PARAM_STR);
        $statement->execute();
        return (int)$this->pdo->lastInsertId();
    }

    public function update(array $dog): bool
    {
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE .
                " SET `owner` = :owner, `owner_city` = :owner_city, `owner_email` = :owner_email, " .
                " `name` = :name, `gender` = :gender,`birthdate` = :birthdate, `is_lof` = :is_lof, " .
                " `identity_number` = :identity_number,`image` = :image,`description` = :description " .
                " WHERE id=:id"
        );
        $statement->bindValue('id', $dog['id'], PDO::PARAM_INT);
        $statement->bindValue('owner', $dog['owner'], PDO::PARAM_STR);
        $statement->bindValue('owner_city', $dog['owner_city'], PDO::PARAM_STR);
        $statement->bindValue('owner_email', $dog['owner_email'], PDO::PARAM_STR);
        $statement->bindValue('name', $dog['name'], PDO::PARAM_STR);
        $statement->bindValue('gender', $dog['gender'], PDO::PARAM_STR);
        $statement->bindValue('birthdate', (empty($dog['birthdate'])) ? null : $dog['birthdate'], PDO::PARAM_STR);
        $statement->bindValue('is_lof', $dog['is_lof'], PDO::PARAM_BOOL);
        $statement->bindValue('identity_number', $dog['identity_number'], PDO::PARAM_STR);
        $statement->bindValue('image', $dog['image'], PDO::PARAM_STR);
        $statement->bindValue('description', $dog['description'], PDO::PARAM_STR);
        return $statement->execute();
    }
}
