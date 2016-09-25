<?php
/**
 * Created by PhpStorm.
 * User: stest
 * Date: 12/07/2016
 * Time: 22:00
 *
 * Questo file gestirà l'accesso alle api, innanzitutto verifica che tipologia di metodo è utilizzato e poi
 * verifica se vi è l'abilitazione alla richiesta fatta oppure no
 */
include('./php/tools.php');
include('./php/getters.php');
include('./php/setters.php');


$tool = new tools();
$requestedApi = $tool->explane_uri($_SERVER['REQUEST_URI']);

//verifico che tipo di metodo viene utilizzato per la richiesta
$method = $_SERVER['REQUEST_METHOD'];
if ($method == 'POST' && array_key_exists('HTTP_X_HTTP_METHOD', $_SERVER)) {
    if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'DELETE') {
        $method = 'DELETE';
    } else if ($_SERVER['HTTP_X_HTTP_METHOD'] == 'PUT') {
        $method = 'PUT';
    } else {
        throw new Exception("Unexpected Header");
    }
}

switch($method) {
    case 'POST':
        $handler = new setters();
        $args = $_POST;
        $givenKey=$_POST["key"];
        break;
    case 'GET':
        $handler = new getters();
        $args = $_GET;
        $givenKey=$_GET["key"];
        break;
    default:
        throw new Exception("Unexpected Method");
        break;
}

$res = $tool->apiAuth($givenKey,$requestedApi);
switch($res){
    case 0:
        throw new Exception ("Api Key Error");
        break;
    case 1:
        throw new Exception ("Not enough privilege");
        break;
    case 2:
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: GET, POST');
        echo $handler->$requestedApi($args);
        break;
}
