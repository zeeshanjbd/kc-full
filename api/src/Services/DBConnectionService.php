<?php

namespace App\Services;

use PDO;
use PDOException;

class DBConnectionService
{
    /**
     * Establishes and returns PDO connection to the database.
     *
     * @return PDO
     * @throws PDOException
     */
    public static function connect(): PDO
    {
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $port = getenv('DB_PORT');

        return new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }

    /**
     * Establishes and returns a PDO connection for import purposes.
     *
     * @return PDO
     * @throws PDOException
     */
    public static function importConnection(): PDO
    {
        $host = getenv('DB_HOST');
        $dbname = getenv('DB_NAME');
        $user = getenv('DB_USER');
        $password = getenv('DB_PASS');
        $port = getenv('DB_PORT');

        $pdo = new PDO("mysql:host=$host;port=$port;dbname=$dbname", $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        $stmt = $pdo->query("SHOW DATABASES LIKE '$dbname'");
        if ($stmt->rowCount() === 0) {
            $pdo->exec("CREATE DATABASE `$dbname` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");
        }

        return new PDO("mysql:host=$host;dbname=$dbname", $user, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);
    }
}