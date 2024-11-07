<?php
session_start();
require_once 'autoload.php';
define("DEFAULT_CONTROLLER", "ProductsController");
define("DEFAULT_ACTION", "index");
define('BASE_PATH', __DIR__);
include 'controllers/ErrorController.php';
?>
    <?php
    function showError()
    {
        $error = new ErrorController();
        $error->index();
    }

    if (isset($_GET['controller'])) {
        $controllerName = ucfirst($_GET['controller']) . 'Controller';
    } elseif (!isset($_GET['controller']) && !isset($_GET['action'])) {
        $controllerName = DEFAULT_CONTROLLER;
    } else {
        echo "<script>console.log('Error: No se ha especificado un controlador o una acción.');</script>";
        showError();
        exit();
    }

    if (class_exists($controllerName)) {
        $controller = new $controllerName();

        if (isset($_GET['action']) && method_exists($controller, $_GET['action'])) {
            $action = $_GET['action'];
            $controller->$action();
        } elseif (!isset($_GET['controller']) && !isset($_GET['action'])) {
            $defaultAction = DEFAULT_ACTION;
            $controller->$defaultAction();
        } else {
            echo "<script>console.log('Error: La acción especificada no existe en el controlador.');</script>";

            showError();
        }
    } else {
        echo "<script>console.log('Error: El controlador especificado no existe.');</script>";

        showError();
    }
    ?>