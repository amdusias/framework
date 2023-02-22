<?php

namespace Framework\Db\Drivers;

use Exception;
use Framework\Db\Interfaces\IDriver;
use PDO;

/**
 * Class Driver
 */
abstract class Driver implements IDriver
{
    /** @var PDO $conn соединение с базой данных */
    protected $conn;

    /**
     * Конструктор драйвера
     *
     * @param string $dsn
     * @param array $config
     * @param array $options
     * @throws Exception
     */
    public function __construct(string $dsn, array $config = [], array $options = [])
    {
        try {
            $this->conn = new \PDO($dsn, $config['username'], $config['password'], $options);
            $this->conn->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
        } catch (\PDOException $e) {
            throw new Exception('Произошла ошибка соединения с базой данных: '.$e->getMessage());
        }
    }

    /**
     * Деструктор драйвера
     */
    public function __destruct()
    {
        $this->conn = null;
    }
}