<?php
namespace Src\Config;

use PDO;
use PDOException;

class Database
{
    private static ?PDO $connection = null;

    public static function getConnection(): PDO
    {
        if (self::$connection === null) {
            $host = '127.0.0.1';    // ou 'localhost'
            $port = '3307';         // padrão MySQL
            $dbname = 'banco';  // <== substitua pelo nome do seu banco
            $username = 'root';     // padrão XAMPP
            $password = '';         // padrão XAMPP, geralmente vazio

            $dsn = "mysql:host=$host;port=$port;dbname=$dbname;charset=utf8mb4";

            try {
                self::$connection = new PDO($dsn, $username, $password);
                self::$connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                self::$connection->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                // Em produção, evite exibir erros sensíveis
                die('Erro ao conectar no banco de dados: ' . $e->getMessage());
            }
        }

        return self::$connection;
    }
}