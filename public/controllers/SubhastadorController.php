<?php
require_once 'models/Product.php';
require_once 'models/Subhasta.php';
require_once 'models/Missatge.php';
require_once 'models/Notificacio.php';
require_once 'database/DBConnection.php';

class SubhastadorController
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

        if ($userRole !== 'subhastador') {
            if ($userRole === 'venedor') {
                header('Location: index.php?controller=venedors&action=index');
            } else {
                header('Location: index.php?controller=auth&action=login');
            }
            exit();
        }
    }

    public function index(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);
        $userId = $_SESSION['id_usuari'] ?? null;
        $products = Product::getProductsDashboard($pdo);

        foreach ($products as $product) {
            $product->likesCount = Product::getLikesCount($pdo, $product->id_producte);
            $product->hasLiked = $userId ? Product::hasUserLiked($pdo, $product->id_producte, $userId) : false;
        }

        require_once 'views/subhastador/dashboard.php';
    }

    public function createSubhasta(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);

            $subhasta = new Subhasta();
            $subhasta->nom = $_POST['nom'];
            $subhasta->date_hora = $_POST['date_hora'];
            $subhasta->lloc = $_POST['lloc'];
            $subhasta->descripcio = $_POST['descripcio'];
            $subhasta->percentatge = $_POST['percentatge'];

            $subhasta->save($pdo);

            header("Location: index.php?controller=subhastador&action=subhastaAdmin");
        } else {
            $this->subhastaAdmin();
        }
    }

    public function subhastaAdmin(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        $subhastes = Subhasta::getAllSubhastes($pdo);
        $subhastesSenseIniciar = Subhasta::getSubhastaSenseIniciar($pdo);
        $products = Product::getProductsByStatusPendentAssignacio($pdo);

        require_once 'views/subhastador/subhasta-admin.php';
    }

    public function addProductsToSubhasta(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);

            $id_subhasta = $_POST['id_subhasta'];
            $productes = $_POST['productes'];

            if (!empty($productes)) {
                Subhasta::addProducts($pdo, $id_subhasta, $productes);
                echo "Productes agregats a la subhasta correctament.";
            } else {
                echo "No has seleccionat cap producte.";
            }

            header('Location: index.php?controller=subhastador&action=subhastaAdmin');
            exit();
        }
    }

    public function viewProducts(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
            $product_id = $_POST['id_producte'];
            $action = $_POST['action'];

            if ($action === 'accept') {
                $status = 'pendent-assignacio';
            } elseif ($action === 'reject') {
                $status = 'rebutjat';
            }

            $creador_id = Product::getCreatorId($pdo, $product_id);

            $data = date('Y-m-d H:i:s');
            $missatge = $_POST['enviaMissatge'];
            Missatge::createMessage($pdo, $product_id, $missatge, $data, $_SESSION['id_usuari'], $creador_id);

            Product::updateStatus($pdo, $product_id, $status);

            Notificacio::createNotification($pdo, $product_id, $_SESSION['id_usuari'], $data);

            header('Location: index.php?controller=subhastador&action=viewProducts');
            exit();
        }

        $pendingProducts = Product::getPendingProducts($pdo);
        $allProducts = Product::getAllProducts($pdo);
        $pendingCount = Product::getPendingCount($pdo);

        require_once 'views/subhastador/view-products.php';
    }

    public function editProducts(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['products'])) {
            foreach ($_POST['products'] as $productId => $productData) {
                $nom = $productData['nom'];
                $descripcioCurta = $productData['descripcio_curta'];
                $descripcioLlarga = $productData['descripcio_llarga'];

                Product::updateDescriptions($pdo, $productId, $nom, $descripcioCurta, $descripcioLlarga);
            }
            header('Location: index.php?controller=subhastador&action=editProducts');
            exit;
        }

        $products = Product::getAllProducts($pdo);

        require_once 'views/subhastador/editar-producte.php';
    }

    public function sessio(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        $allSubhastes = Subhasta::getAllSubhastesSenseFeroIniciades($pdo);
        foreach ($allSubhastes as $subhasta) {
            $subhasta->inici = Subhasta::getIniciById($pdo, $subhasta->id_subhasta);
        }
        require_once 'views/subhastador/sessions.php';
    }

    public function iniciaSubhasta(): void
    {
        if (isset($_GET['id_subhasta'])) {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);
            $id_subhasta = $_GET['id_subhasta'];


            Subhasta::iniciaSubhasta($pdo, $id_subhasta);


            header('Location: index.php?controller=subhastador&action=subhastaIniciada&id_subhasta=' . $id_subhasta);
            exit;
        }
    }
    public function subhastaIniciada(): void
    {
        if (isset($_GET['id_subhasta'])) {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);
            $id_subhasta = intval($_GET['id_subhasta']);
            $subhasta = Subhasta::getSubhastaById($pdo, $id_subhasta);
            if ($subhasta->inici !== 'iniciat') {
                header('Location: index.php?controller=subhastador&action=sessio');
                exit;
            }
            $products = Subhasta::visualitzaSubhastaIniciada($pdo, $id_subhasta);
            $users = User::getAllUsersExceptSubhastador($pdo);
            require_once 'views/subhastador/sessio-iniciada.php';
        }
    }
    public function registerUser(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);

            $dni = $_POST['dni'];
            $username = $_POST['username'];

            if (User::addComprador($pdo, $dni, $username)) {
                echo "Usuario registrado correctamente.";
            } else {
                echo "Error al registrar el usuario.";
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        }
    }


    public function finalitzaSubhasta(): void
    {
        if (isset($_GET['id_subhasta'])) {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);
            $id_subhasta = intval($_GET['id_subhasta']);
            Subhasta::tancaSubhasta($pdo, $id_subhasta);
            Subhasta::getEstatComptes($pdo, $id_subhasta);
            Subhasta::saveEstatComptes($pdo, $id_subhasta);
            header('Location: index.php?controller=subhastador&action=estatComptes&id_subhasta=' . $id_subhasta);
            exit();
        }
    }

    public function estatComptes(): void
    {
        if (isset($_GET['id_subhasta'])) {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);
            $id_subhasta = intval($_GET['id_subhasta']);
            $estatComptes = Subhasta::getEstatComptes($pdo, $id_subhasta);
            require_once 'views/subhastador/estat-comptes.php';
        }
    }
    public function viewSubhastes(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);
        $subhastes = Subhasta::getAllSubhastesSenseFeroIniciades($pdo);

        // Obtener productos para cada subhasta
        foreach ($subhastes as $subhasta) {
            $subhasta->productes = Subhasta::getProductForSubhasta($pdo, $subhasta->id_subhasta);
        }

        require_once 'views/home/sessions-subhasta.php';
    }

    public function totesSubhastes(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);
        $allSubhastes = Subhasta::getAllSubhastes($pdo);
        require_once 'views/subhastador/view-comptes.php';
    }
    

    public function assignComprador(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);

            $comprador_id = $_POST['comprador_id'];
            $product_id = intval($_POST['product_id']);

            error_log("Comprador ID: $comprador_id");
            error_log("Product ID: $product_id");

            if (Subhasta::assignComprador($pdo, $product_id, $comprador_id)) {
                error_log("Comprador assigned successfully.");
            } else {
                error_log("Failed to assign comprador.");
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            error_log("Request method is not POST.");
        }
    }

    public function assignPreu(): void
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);

            $preu_final = $_POST['preu_final'];
            $product_id = intval($_POST['product_id']);

            if (Subhasta::assignPreu($pdo, $product_id, $preu_final)) {
                error_log("Preu assigned successfully.");
            } else {
                error_log("Failed to assign preu.");
            }

            header('Location: ' . $_SERVER['HTTP_REFERER']);
            exit();
        } else {
            error_log("Request method is not POST.");
        }
    }
}
