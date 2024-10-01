<?php
require_once "controllers/delete.controller.php";
require_once "models/delete.model.php";

$id = $_GET["id"];

$response = new DeleteModel();
/*HOLA MUNDO */
$response->deleteData($table, $id);
?>
