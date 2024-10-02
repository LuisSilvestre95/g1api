<?php

require_once "models/connection.php";
require_once "controllers/put.controller.php";

if (isset($_GET["id"]) && isset($_GET["nameId"])) {

    /*Capturamos datos del formulario */

    $data = array();
    parse_str(file_get_contents('php://input'), $data);

    /*Separar propiedades en un arreglo */

    $columns = array();

    foreach (array_keys($data) as $key => $value) {
        array_push($columns, $value);
    }

    array_push($columns, $_GET["nameId"]);
    $columns = array_unique($columns);

    /*Validar la tabla y las columnas */
    if (empty(Connection::getColumnsData($table, $columns))) {
        $json = array(
            'status' => 400,
            'results' => 'Datos no coinciden'
        );
        echo json_encode($json, http_response_code($json["status"]));
    }


    if (isset($_GET["token"])) {
        /*Peticion PUT para usuarios no autorizados*/
        if ($_GET["token"] == "no" && isset($_GET["except"])) {

            /*Validar la tabla y las columnas */
            $columns = array($_GET["except"]);
            if (empty(Connection::getColumnsData($table, $columns))) {
                $json = array(
                    'status' => 400,
                    'results' => 'Error: Fileds in the form do not match the database'
                );
                echo json_encode($json, http_response_code($json["status"]));
                return;
            }

            /*Solicitamos respuesta del controlador para crear datos en cualquier tabla*/
            $response = new PutController();
            $response->putData($table, $data, $_GET["id"], $_GET["nameId"]);

            /*Peticion PUT para usuarios autorizados*/
        } else {
            $tableToken = $_GET["table"] ?? "users";
            $suffix = $_GET["suffix"] ?? "user";

            $validate = Connection::tokenValidate($_GET["token"], $table, $suffix);

            /*Solicitamos respuesta del controlador para crear datos en cualquier tabla token valido*/
            if ($validate == "Ok") {
                /*Solicitamos respuesta del controlador para editar datos en cualquier tabla */
                $response = new PutController();
                $response->putData($table, $data, $_GET["id"], $_GET["nameId"]);
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
?>
/*COMENTARIO DE BRAYAN*/