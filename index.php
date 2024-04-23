<?php
session_start();
ob_start();

require_once 'app/config/config.php';
require_once 'app/config/route.php';
require_once 'app/database/database.php';
require_once 'app/controller/Controller.php';
require_once 'vendor/Relations/Relation.php';
require_once 'vendor/model.php';

$controller = isset($_GET['controller']) ? $_GET['controller'] . 'Controller' : $defaultController;

if (!empty($controller)) {
    $controllerFileName = 'app/controller/' . $controller . '.php';

    if (file_exists($controllerFileName)) {
        include_once ($controllerFileName);

        $controllerClassName = ucfirst($controller);
        if (class_exists($controllerClassName)) {
            global $koneksi;
            global $db;

            $controllerInstance = new $controllerClassName($db, $koneksi);
            $model = new Model($db);
            $action = isset($_GET['action']) && $_GET['action'] !== '' ? $_GET['action'] : 'index';
            $id = isset($_GET['id']) ? $_GET['id'] : null;

            if (method_exists($controllerInstance, $action)) {
                // if (isset($_SESSION['KeyLock']) || isset($_SESSION['id'])) {
                // } else {
                //     include ('view/welcome.php');
                // }
                $controllerInstance->$action($id);
            } else {
                $error = "Action not found";
                include ('error.php');
            }
        } else {
            $error = "Controller class not found";
            include ('error.php');
        }
    } else {
        $error = "Controller file not found";
        include ('error.php');
    }
} else {
    $error = "Controller not specified";
    include ('error.php');
}
?>