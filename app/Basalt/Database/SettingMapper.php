<?php

namespace Basalt\Database;

use PDO;

class SettingMapper
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($name)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `settings` WHERE `name` = :name');
        $statement->bindValue(':name', $name);

        $statement->execute();

        return $statement->fetchObject('\Basalt\Database\Setting') ?: null;
    }

    public function save(Setting &$setting)
    {
        $setting->validate();

        if ($setting->id) {
            $statement = $this->pdo->prepare('UPDATE `settings` SET `name` = :name, `value` = :value, `type` = :type WHERE `id` = :id');
            $statement->bindValue(':id', $setting->id, PDO::PARAM_INT);
            $statement->bindValue(':name', $setting->name);
            $statement->bindValue(':value', $setting->value);
            $statement->bindValue(':type', $setting->type, PDO::PARAM_INT);

            $statement->execute();
        } else {
            $statement = $this->pdo->prepare('INSERT INTO `settings` (`name`, `value`, `type`) VALUES (:name, :value, :type)');
            $statement->bindValue(':name', $setting->name);
            $statement->bindValue(':value', $setting->value);
            $statement->bindValue(':type', $setting->type, PDO::PARAM_INT);

            $statement->execute();

            $setting->id = $this->pdo->lastInsertId();
        }
    }
} 