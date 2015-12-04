<?php

namespace Basalt\Database;

use PDO;

class SettingMapper
{
    const ENTITY = '\Basalt\Database\Setting';

    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function all()
    {
        $statement = $this->pdo->prepare('SELECT * FROM `settings`');

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, self::ENTITY);
    }

    public function get($name)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `settings` WHERE `name` = :name');
        $statement->bindValue(':name', $name);

        $statement->execute();

        return $statement->fetchObject(self::ENTITY) ?: null;
    }

    public function save(Setting &$setting)
    {
        if ($setting->id) {
            $statement = $this->pdo->prepare('UPDATE `settings` SET `name` = :name, `value` = :value WHERE `id` = :id');
            $statement->bindValue(':id', $setting->id, PDO::PARAM_INT);
            $statement->bindValue(':name', $setting->name);
            $statement->bindValue(':value', $setting->value);

            $statement->execute();
        } else {
            $statement = $this->pdo->prepare('INSERT INTO `settings` (`name`, `value`) VALUES (:name, :value)');
            $statement->bindValue(':name', $setting->name);
            $statement->bindValue(':value', $setting->value);

            $statement->execute();

            $setting->id = $this->pdo->lastInsertId();
        }
    }
}
