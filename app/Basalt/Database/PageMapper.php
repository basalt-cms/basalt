<?php

namespace Basalt\Database;

use PDO;

class PageMapper extends AbstractMapper
{
    const ENTITY = '\Basalt\Database\Page';

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

        return $statement->fetchObject(self::ENTITY) ?: null;
    }

    /**
     * Return page by id.
     *
     * @param int $id Page id.
     * @return \Basalt\Database\Page|null
     */
    public function getById($id)
    {
        $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `id` = :id LIMIT 1');
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        return $statement->fetchObject(self::ENTITY) ?: null;
    }

    /**
     * Return index page.
     *
     * @return \Basalt\Database\Page|null
     */
    public function getIndex()
    {
        $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `id` = 1 LIMIT 1');

        $statement->execute();

        return $statement->fetchObject(self::ENTITY) ?: null;
    }

    /**
     * Return all pages.
     *
     * @param bool $drafts Should it return drafts?
     * @return \Basalt\Database\Page|null
     */
    public function all($drafts = false)
    {
        if ($drafts) {
            $statement = $this->pdo->prepare('SELECT * FROM `pages` ORDER BY `order`');
        } else {
            $statement = $this->pdo->prepare('SELECT * FROM `pages` WHERE `draft` = 0 ORDER BY `order`');
        }

        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_CLASS, self::ENTITY) ?: null;
    }

    /**
     * Save the page.
     *
     * @param \Basalt\Database\Page $page Page.
     * @return void
     */
    public function save(Page &$page)
    {
        if ($page->id) {
            $statement = $this->pdo->prepare('UPDATE `pages` SET `name` = :name, `slug` = :slug, `content` = :content, `draft` = :draft WHERE `id` = :id');
            $statement->bindValue(':id', $page->id, PDO::PARAM_INT);
            $statement->bindValue(':name', $page->name);
            $statement->bindValue(':slug', $page->slug);
            $statement->bindValue(':content', $page->content);
            $statement->bindValue(':draft', $page->draft, PDO::PARAM_BOOL);

            $statement->execute();
        } else {
            $statement = $this->pdo->prepare('INSERT INTO `pages` (`name`, `slug`, `content`, `draft`, `order`) SELECT :name, :slug, :content, :draft, MAX(`id`) + 1 FROM `pages`');
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
