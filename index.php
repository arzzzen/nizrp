<?php
// error_reporting(E_ALL);
// $html = file_get_html('ftp://onizrp:969357@nizrp.narod.ru/zbk-expess.htm');
// echo $html->find('#main-ul', 0);
require 'vendor\autoload.php';

Admin\Lib\Bootstrap::boot();

if (!isset($_GET['page'])) {
    $controller = new Admin\Controllers\Index();
    $controller->index();
} else {
    list($controller_class, $action) = explode('/', $_GET['page']);
    $controller_class = 'Admin\Controllers\\'.$controller_class;
    $controller = new $controller_class();
    if ($action) {
        $controller->{$action}();
    } else {
        $controller->index();
    }
}