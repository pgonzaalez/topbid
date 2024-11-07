<?php
class DBConnection
{

    private static $pdo;

    private final function  __construct()
    {
    }

    public static function getConnection($dbconfig): PDO
    {
        if (!isset(self::$pdo)) {
            try {
                $pdo = new PDO(
                    $dbconfig['connection'] . ';dbname=' . $dbconfig['dbname'],
                    $dbconfig['usr'],
                    $dbconfig['pwd'],
                    $dbconfig['options']
                );
            } catch (PDOException $ex) {
                // TODO: Log de l'error
                header("Location: index.php?controller=error");
            } catch (Exception $ex) {
                // TODO: Log de l'error
                header("Location: index.php?controller=error");
            }
            self::$pdo = $pdo;
        }
        return self::$pdo;
    }
}
