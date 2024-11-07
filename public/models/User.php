<?php

class User
{
    public $id_usuari;
    public $username;
    public $contrasenya;
    public $dni;
    public $rol;

    public static function getUserByUsername(PDO $pdo, string $username): ?User
    {
        try {
            $sql = "SELECT * FROM usuari WHERE username = :username";
            $statement = $pdo->prepare($sql);
            $statement->bindParam(':username', $username);
            $statement->execute();

            $user = $statement->fetchObject("User");
            return $user ?: null;
        } catch (PDOException $ex) {
            // TODO: Log de l'error
            header("Location: index.php?controller=error");
        } catch (Exception $ex) {
            // TODO: Log de l'error
            header("Location: index.php?controller=error");
        }

        return null;
    }

    public static function addComprador(PDO $pdo, string $dni, string $username): bool
    {
        try {
            $sql = "INSERT INTO usuari (dni, username, rol) VALUES (:dni, :username, 'comprador')";
            $statement = $pdo->prepare($sql);
            return $statement->execute(['dni' => $dni, 'username' => $username]);
        } catch (PDOException $ex) {
            // TODO: Log de l'error
            header("Location: index.php?controller=error");
        } catch (Exception $ex) {
            // TODO: Log de l'error
            header("Location: index.php?controller=error");
        }

        return false;
    }

    public static function getAllUsers(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM usuari");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }

    public static function getAllUsersExceptSubhastador(PDO $pdo): array
    {
        $stmt = $pdo->prepare("SELECT * FROM usuari WHERE rol != 'subhastador'");
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_OBJ);
    }
}
