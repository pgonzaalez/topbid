<?php
require_once 'models/User.php';
require_once 'database/DBConnection.php';

class AuthController
{
    public function login(): void
    {
        require_once 'views/auth/login.php';
    }

    public function authenticate(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        $username = $_POST['username'];
        $password = $_POST['password'];

        $user = User::getUserByUsername($pdo, $username);

        if ($user && $password == $user->contrasenya) {
            session_start();
            $_SESSION['id_usuari'] = $user->id_usuari;
            $_SESSION['username'] = $user->username;
            $_SESSION['rol'] = $user->rol;

            if ($_SESSION['rol'] == 'subhastador') {
                header('Location: index.php?controller=subhastador&action=index');
                exit();
            } else {
                header('Location: index.php?controller=venedors&action=index');
                exit();
            }
        } else {
            $error = "Invalid username or password.";
            require_once 'views/auth/login.php';
        }
    }

    public function logout(): void
    {
        session_start();
        session_destroy();
        header("Location: index.php");
        exit();
    }
}