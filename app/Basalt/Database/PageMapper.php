<?php

namespace Basalt\Database;

use PDO;

class PageMapper
{
    protected $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function get($slug)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `slug` = :slug');
        $statement->bindValue(':slug', $slug);

        $statement->execute();

        return $statement->fetchObject('\Basalt\Database\Page') ?: null;
    }

    public function getIndex()
    {
        $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `id` = 1');

        $statement->execute();

        return $statement->fetchObject('\Basalt\Database\Page') ?: null;
    }

    public function all($drafts = false)
    {
        $drafts = $drafts ? '' : ' WHERE `draft` = 0';
        $statement = $this->pdo->prepare('SELECT * FROM `pages`'.$drafts);

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, '\Basalt\Database\Page') ?: null;
    }

    public function save(Page &$page)
    {
        $page->validate();

        if ($page->id) {
            $statement = $this->pdo->prepare('UPDATE `pages` SET `name` = :name, `slug` = :slug, `content` = :content, `draft` = :draft WHERE `id` = :id');
            $statement->bindValue(':id', $page->id, PDO::PARAM_INT);
            $statement->bindValue(':name', $page->name);
            $statement->bindValue(':slug', $page->slug);
            $statement->bindValue(':content', $page->content);
            $statement->bindValue(':draft', $page->draft, PDO::PARAM_BOOL);

            $statement->execute();
        } else {
            $statement = $this->pdo->prepare('INSERT INTO `pages` (`name`, `slug`, `content`, `draft`) VALUES (:name, :slug, :content, :draft)');
            $statement->bindValue(':name', $page->name);
            $statement->bindValue(':slug', $page->slug);
            $statement->bindValue(':content', $page->content);
            $statement->bindValue(':draft', $page->draft, PDO::PARAM_BOOL);

            $statement->execute();

            $page->id = $this->pdo->lastInsertId();
        }
    }

    public function delete($id)
    {
        $statement = $this->pdo->prepare('DELETE `page` WHERE `id` = :id');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();
    }
} 