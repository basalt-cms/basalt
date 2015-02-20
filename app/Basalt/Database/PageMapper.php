<?php

namespace Basalt\Database;

use PDO;

class PageMapper
{
    /**
     * @var \PDO PDO.
     */
    protected $pdo;

    /**
     * Constructor.
     *
     * @param \PDO $pdo PDO.
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Return page by slug.
     *
     * @param string $slug Page slug.
     * @return \Basalt\Database\Page|null
     */
    public function getBySlug($slug)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `slug` = :slug');
        $statement->bindValue(':slug', $slug);

        $statement->execute();

        return $statement->fetchObject('\Basalt\Database\Page') ?: null;
    }

    /**
     * Return page by id.
     *
     * @param int $id Page id.
     * @return \Basalt\Database\Page|null
     */
    public function getById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `id` = :id');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchObject('\Basalt\Database\Page') ?: null;
    }

    /**
     * Return index page.
     *
     * @return \Basalt\Database\Page|null
     */
    public function getIndex()
    {
        $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `id` = 1');

        $statement->execute();

        return $statement->fetchObject('\Basalt\Database\Page') ?: null;
    }

    /**
     * Return all pages.
     *
     * @param bool $drafts Should it return drafts?
     * @return \Basalt\Database\Page|null
     */
    public function all($drafts = false)
    {
        $drafts = $drafts ? '' : ' WHERE `draft` = 0';
        $statement = $this->pdo->prepare('SELECT * FROM `pages`'.$drafts.' ORDER BY `order`');

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, '\Basalt\Database\Page') ?: null;
    }

    /**
     * Save the page.
     *
     * @param \Basalt\Database\Page $page Page.
     * @return void
     */
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

    public function changeOrder(array $order)
    {
        $counter = 0;

        foreach ($order as $id) {
            $statement = $this->pdo->prepare('UPDATE `pages` SET `order` = :order WHERE `id` = :id');
            $statement->bindValue(':order', $counter, PDO::PARAM_INT);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);

            $statement->execute();

            $counter++;
        }

    }

    /**
     * Delete page by id.
     *
     * @param int $id Page id.
     */
    public function delete($id)
    {
        $statement = $this->pdo->prepare('DELETE FROM `pages` WHERE `id` = :id');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();
    }
} 