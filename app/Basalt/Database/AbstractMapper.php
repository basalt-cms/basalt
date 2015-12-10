<?php

namespace Basalt\Database;

use PDO;

abstract class AbstractMapper
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
}