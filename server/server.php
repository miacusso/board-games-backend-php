<?php
require_once("ServerController.php");

$method = $_GET; // Redirects in .htaccess converts all requests to GET.
$body = json_decode(file_get_contents('php://input')); // In POST case the body is kept here.

$serverController = new ServerController();
$response = $serverController->redirect($method, $body);
$jsonResponse = strtolower(json_encode($response, JSON_NUMERIC_CHECK));

echo $jsonResponse;
?>