<?php
require_once 'models/Product.php';
require_once 'database/DBConnection.php';

class VenedorsController
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

        $userRole = $_SESSION['rol'] ?? null;

        if ($userRole !== 'venedor') {
            if ($userRole === 'subhastador') {
                header('Location: index.php?controller=subhastador&action=index');
            } else {
                header('Location: index.php?controller=auth&action=login');
            }
            exit();
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

        $products = Product::getProductsByUserId($pdo, $_SESSION['id_usuari']);

        require_once 'views/venedor/venedor.php';
    }

    public function delete(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'delete') {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);
            $id = $_POST['id'];

            Product::deleteProductById($pdo, $id);

            header('Location: index.php?controller=venedors&action=index');
            exit();
        }
    }

    public function storeProducte(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $userid = $_SESSION['id_usuari'];
            $nom = $_POST['nomProducte'];
            $descripcioCurta = $_POST['descripcioCurtaProducte'];
            $descripcioLlarga = $_POST['descripcioLlargaProducte'];
            $preu = $_POST['preuProducte'];
            $observacions = $_POST['observacions'];

            $imatge = null;
            if (isset($_FILES["imatgeProducte"]) && $_FILES["imatgeProducte"]["error"] == 0) {
                $target_dir = "views/images/productes/";
                $imageFileType = strtolower(pathinfo($_FILES["imatgeProducte"]["name"], PATHINFO_EXTENSION));
                $target_file = $target_dir . uniqid() . '.' . $imageFileType;
                $uploadOk = 1;

                $check = getimagesize($_FILES["imatgeProducte"]["tmp_name"]);
                if ($check !== false) {
                    $uploadOk = 1;
                } else {
                    echo "El archivo no es una imagen.";
                    $uploadOk = 0;
                }

                if ($_FILES["imatgeProducte"]["size"] > 500000) {
                    echo "Lo siento, tu archivo es demasiado grande.";
                    $uploadOk = 0;
                }

                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    echo "Lo siento, solo se permiten archivos JPG, JPEG, PNG y GIF.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 0) {
                    echo "Lo siento, tu archivo no fue subido.";
                } else {
                    if (move_uploaded_file($_FILES["imatgeProducte"]["tmp_name"], $target_file)) {
                        $imatge = $target_file;
                    } else {
                        echo "Lo siento, hubo un error al subir tu archivo.";
                    }
                }
            }

            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);

            if (Product::createProduct($pdo, $nom, $descripcioCurta, $descripcioLlarga, $imatge, $preu, $userid, $observacions)) {
                header('Location: index.php?controller=venedors&action=index');
                exit();
            } else {
                echo "Error al crear el producto.";
            }
        }
        require_once 'views/venedor/afegir-productes.php';
    }
}
