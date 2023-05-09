<?php

namespace App\Model;

use PDO;

class MemberManager extends AbstractManager
{
    public const TABLE = 'member';

    public function insert(array $member): void
    {
        $statement = $this->pdo->prepare(
            "INSERT INTO " . self::TABLE . " (`lastname`, `firstname`, `job`, `email`, `image`)
             VALUES (:lastname, :firstname, :job, :email, :image)"
        );
        $statement->bindValue('lastname', $member['lastname'], PDO::PARAM_STR);
        $statement->bindValue('firstname', $member['firstname'], PDO::PARAM_STR);
        $statement->bindValue('job', $member['job'], PDO::PARAM_STR);
        $statement->bindValue('email', $member['email'], PDO::PARAM_STR);
        $statement->bindValue('image', $member['image'], PDO::PARAM_STR);
        $statement->execute();
    }

    public function update(array $member): void
    {
        $statement = $this->pdo->prepare(
            "UPDATE " . self::TABLE . " SET(`lastname`=:lastname, `firstname`=:firstname,
             `job`=:job, `email`=:email, `image`=:image)
            WHERE id=:id"
        );
        $statement->bindValue('lastname', $member['lastname'], PDO::PARAM_STR);
        $statement->bindValue('firstname', $member['firstname'], PDO::PARAM_STR);
        $statement->bindValue('job', $member['job'], PDO::PARAM_STR);
        $statement->bindValue('email', $member['email'], PDO::PARAM_STR);
        $statement->bindValue('image', $member['image'], PDO::PARAM_STR);
        $statement->bindValue('id', $member['id'], PDO::PARAM_STR);
        $statement->execute();
    }
}
