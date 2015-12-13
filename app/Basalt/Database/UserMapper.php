<?php

namespace Basalt\Database;

use PDO;

class UserMapper extends AbstractMapper
{
    const ENTITY = '\Basalt\Database\User';

    /**
     * Returns user by id.
     *
     * @param $id
     * @return \Basalt\Database\User|null
     */
    public function getById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `users` WHERE `id` = :id');
        $statement->bindValue('id', $id, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchObject(self::ENTITY) ?: null;
    }

    /**
     * Returns user by email.
     *
     * @param $email
     * @return \Basalt\Database\User|null
     */
    public function getByEmail($email)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `users` WHERE `email` = :email LIMIT 1');
        $statement->bindValue('email', $email);

        $statement->execute();

        return $statement->fetchObject(self::ENTITY) ?: null;
    }

    /**
     * Saves the user.
     *
     * @param \Basalt\Database\User $user Page.
     * @return void
     */
    public function save(User &$user)
    {
        if ($user->id) {
            $statement = $this->pdo->prepare('UPDATE `users` SET `email` = :email, `password` = :password WHERE `id` = :id');
            $statement->bindValue(':id', $user->id, PDO::PARAM_INT);
            $statement->bindValue(':email', $user->email);
            $statement->bindValue(':password', $user->password);

            $statement->execute();
        } else {
            $statement = $this->pdo->prepare('INSERT INTO `pages` (`email`, `password`) VALUES (:email, :password)');
            $statement->bindValue(':email', $user->email);
            $statement->bindValue(':password', $user->password);

            $statement->execute();

            $user->id = $this->pdo->lastInsertId();
        }
    }
}
