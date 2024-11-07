<?php
require_once 'database/DBConnection.php';
require_once 'models/Missatge.php';
require_once 'models/Notificacio.php';

class NotificacionsController
{
    public function __construct()
    {
        $this->checkRole();
    }

    private function checkRole(): void
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function index(): void
    {
        if (!isset($_SESSION['id_usuari']) || $_SESSION['rol'] != 'venedor') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        $messages = Missatge::getAllMessages($pdo);
        $notificacions = Notificacio::getNotificationsByUsuari($pdo, $_SESSION['id_usuari']);

        require_once 'views/notificacions/notificacions.php';
    }

    public function marcarLlegit(): void
    {
        if (!isset($_SESSION['id_usuari']) || $_SESSION['rol'] != 'venedor') {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }

        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        if (isset($_POST['id_missatge'])) {
            $id_missatge = $_POST['id_missatge'];
            if (!empty($id_missatge)) {
                if (Missatge::marcarLlegit($pdo, $id_missatge)) {
                    echo "Mensaje marcado como leído.";
                } else {
                    echo "Error al marcar el mensaje como leído.";
                }
            } else {
                echo "ID de mensaje no proporcionado.";
            }
        } else if (isset($_POST['id_notificacio'])) {
            $id_notificacio = $_POST['id_notificacio'];
            if (!empty($id_notificacio)) {
                if (Notificacio::marcarLlegit($pdo, $id_notificacio)) {
                    echo "Notificación marcada como leída.";
                } else {
                    echo "Error al marcar la notificación como leída.";
                }
            } else {
                echo "ID de notificación no proporcionado.";
            }
        }

        header('Location: index.php?controller=notificacions&action=index');
    }
}
