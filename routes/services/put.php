<?php
require_once "controllers/put.controller.php";
require_once "models/put.model.php";

$data = json_decode(file_get_contents("php://input"), true);
$id = $_GET["id"];

$response = new PutModel();

$response->putData($table, $data, $id);
?>
