<?php

$request = $_SERVER['REQUEST_URI'];

/** SET ROUTES HERE */
// insert routes alphabetically
$routes = array(
    "homepage" => array(
        'class_name' => '',
        'has_detail' => 0
    ),
    "sales" => array(
        'class_name' => '',
        'has_detail' => 1
    ),
    "customers" => array(
        'class_name' => 'Customers',
        'has_detail' => 0
    ),
    "suppliers" => array(
        'class_name' => 'Suppliers',
        'has_detail' => 0
    ),
    "products" => array(
        'class_name' => 'Products',
        'has_detail' => 0
    ),
    "product-categories" => array(
        'class_name' => 'ProductCategories',
        'has_detail' => 0
    ),
    
);
/** END SET ROUTES */


$base_folder = "pages/";
$page = str_replace("/chmsc/", "", $request);

// chec if has parameters
if (substr_count($page, "?") > 0) {
    $url_params = explode("?", $page);
    $dir = $base_folder . $url_params[0] . '/index.php';
    //$param = $url_params[1];
    $page = $url_params[0];
} else {

    if ($page == "" || $page == null) {
        $page = "homepage";
    }
    $dir = $base_folder . $page . '/index.php';
}

if (array_key_exists($page, $routes)) {
    require_once $dir;
    $route_settings = json_encode($routes[$page]);
} else {
    require_once 'error-404.html';
    $route_settings = json_encode([]);
}
