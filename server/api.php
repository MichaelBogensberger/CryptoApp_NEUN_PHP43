<?php

require_once('controllers/RESTController.php');

// entry point for the rest api
// e.g. GET http://localhost/php41/api.php?r=purchase/25
// or with url_rewrite GET http://localhost/php41/api/purchase/25
// select route: purchase/25 -> controller=purchase, action=GET, id=25
$route = isset($_GET['r']) ? explode('/', trim($_GET['r'], '/')) : ['purchase'];
$controller = sizeof($route) > 0 ? $route[0] : 'purchase';

if ($controller == 'purchase') {
    require_once('controllers/PurchaseRESTController.php');

    try {
        (new PurchaseRESTController())->handleRequest();
    } catch(Exception $e) {
        RESTController::responseHelper($e->getMessage(), $e->getCode());
    }
} else {
    RESTController::responseHelper('REST-Controller "' . $controller . '" not found', '404');
}
