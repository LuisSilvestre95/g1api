<?php

require_once "models/connection.php";
require_once "controllers/delete.controller.php";

if (isset($_GET["id"]) && isset($_GET["nameId"])) {

    $columns = array($_GET["nameId"]);


    /*Validar la tabla y las columnas */
    if (empty(Connection::getColumnsData($table, $columns))) {
        $json = array(
            'status' => 400,
            'results' => 'Error: Fileds in the form do not match the database'
        );
        return;
    }

    /*Peticion DELETE para usuarios autorizados*/
    if (isset($_GET["token"])) {
        $tableToken = $_GET["table"] ?? "users";
        $suffix = $_GET["suffix"] ?? "user";

        $validate = Connection::tokenValidate($_GET["token"], $table, $suffix);

        /*Solicitamos respuesta del controlador para crear datos en cualquier tabla token valido*/
        if ($validate == "Ok") {

            /*Solicitamos respuesta del controlador para eliminar datos en cualquier tabla */
            $response = new DeleteController();
            $response->deleteData($table, $_GET["id"], $_GET["nameId"]);
        }

        /*Error cuando el token ha expirado*/
        if ($validate == "Expired") {
            $json = array(
                'status' => 303,
                'results' => 'Error: The token has expired'
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }

        /*Error cuando el token no coincide con base de datos*/
        if ($validate == "No autorizado") {
            $json = array(
                'status' => 400,
                'results' => 'Error: The user has no propper authorization'
            );
            echo json_encode($json, http_response_code($json["status"]));
            return;
        }

        /*Error cuando no envia el token*/
    } else {
        $json = array(
            'status' => 400,
            'results' => 'Error: Authorization required'
        );
        echo json_encode($json, http_response_code($json["status"]));
        return;
    }
}
/*asfdgfhghm*/
/*458975264878*/
/*ASDFGHJKLÑLKJHGFDS */