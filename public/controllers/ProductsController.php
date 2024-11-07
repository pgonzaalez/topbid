<?php
require_once 'models/Product.php';
require_once 'database/DBConnection.php';

class ProductsController
{

    public function index(): void
    {
        $config = require_once 'config.php';
        $depuracion = $config['depuracion'];
        $pdo = DBConnection::getConnection($config['db']);

        $search = $_GET['search'] ?? '';
        $order = $_GET['order'] ?? 'nom';
        $page = isset($_GET['page']) ? (int) $_GET['page'] : 1;
        $limit = 9;
        $offset = ($page - 1) * $limit;

        if ($depuracion === true) {
            $products = Product::getProductsByDepuracio($pdo, $search, $order, $limit, $offset);
        } else {
            $products = Product::searchProductsPaginated($pdo, $search, $order, $limit, $offset);
        }

        $totalProducts = Product::getTotalProductsCount($pdo, $search);
        $totalPages = ceil($totalProducts / $limit);

        $userId = $_SESSION['id_usuari'] ?? null;
        $likedProducts = isset($_COOKIE['liked_products']) ? json_decode($_COOKIE['liked_products'], true) : [];

        foreach ($products as $product) {
            $product->likesCount = Product::getLikesCount($pdo, $product->id_producte);
            $product->hasLiked = $userId ? Product::hasUserLiked($pdo, $product->id_producte, $userId) : in_array($product->id_producte, $likedProducts);
            $product->subhastaNom = Subhasta::getSubhastaNameByProductId($pdo, $product->id_producte);
        }


        require 'views/products/products.php';
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

    public function veureProductes(): void
    {
        if (isset($_GET['id_subhasta'])) {
            $config = require_once 'config.php';
            $pdo = DBConnection::getConnection($config['db']);
            $id_subhasta = intval($_GET['id_subhasta']);
            $productes = Subhasta::getProductForSubhasta($pdo, $id_subhasta);
            $subhasta = Subhasta::getSubhastaById($pdo, $id_subhasta);
            require_once 'views/home/productes-subhasta.php';
        }
    }

    public function buscarSubhastes(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);
        $search = $_GET['search'] ?? '';

        $subhastes = Subhasta::buscarSubhastes($pdo, $search);
        foreach ($subhastes as $subhasta) {
            $subhasta->productes = Subhasta::getProductForSubhasta($pdo, $subhasta->id_subhasta);
        }

        require_once 'views/home/sessions-subhasta.php';
    }
    public function like(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        $productId = $_POST['product_id'];
        $userId = $_SESSION['id_usuari'] ?? null;

        if ($userId) {
            if (Product::hasUserLiked($pdo, $productId, $userId)) {
                Product::unlikeProduct($pdo, $productId, $userId);
            } else {
                Product::likeProduct($pdo, $productId, $userId);
            }
        } else {
            $likedProducts = isset($_COOKIE['liked_products']) ? json_decode($_COOKIE['liked_products'], true) : [];
            if (in_array($productId, $likedProducts)) {
                $likedProducts = array_diff($likedProducts, [$productId]);
                Product::decrementLikesCount($pdo, $productId);
            } else {
                $likedProducts[] = $productId;
                Product::incrementLikesCount($pdo, $productId);
            }
            setcookie('liked_products', json_encode($likedProducts), time() + (10 * 365 * 24 * 60 * 60), "/"); // Cookie válida por 10 años
        }

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function saveLikesFromCookies(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        $userId = $_SESSION['id_usuari'] ?? null;
        if ($userId && isset($_COOKIE['liked_products'])) {
            $likedProducts = json_decode($_COOKIE['liked_products'], true);
            foreach ($likedProducts as $productId) {
                if (!Product::hasUserLiked($pdo, $productId, $userId)) {
                    Product::likeProduct($pdo, $productId, $userId);
                }
            }
            setcookie('liked_products', '', time() - 3600, "/"); // Eliminar la cookie
        }

        header('Location: index.php?controller=products&action=index');
        exit;
    }

    public function likedProducts(): void
    {
        $config = require_once 'config.php';
        $pdo = DBConnection::getConnection($config['db']);

        $userId = $_SESSION['id_usuari'] ?? null;
        if ($userId) {
            $likedProducts = Product::getLikedProductsByUser($pdo, $userId);
            foreach ($likedProducts as $product) {
                $product->likesCount = Product::getLikesCount($pdo, $product->id_producte);
                $product->hasLiked = Product::hasUserLiked($pdo, $product->id_producte, $userId);
            }
            require_once 'views/products/liked-products.php';
        } else {
            header('Location: index.php?controller=auth&action=login');
            exit();
        }
    }
}
