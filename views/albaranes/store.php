<?php
$ruta = (file_exists("controllers/albaranesController.php")) ? "" : "../../";
require_once $ruta . "controllers/albaranesController.php";
$controlador = new albaranesController();

if (!isset($_REQUEST["arrayAlbaran"])) header('Location:index.php?accion=crear&tabla=pedidos');

if ($_REQUEST["evento"] == "crear") {
    $arrayAlbaran = json_decode($_REQUEST["arrayAlbaran"], JSON_OBJECT_AS_ARRAY);
    
    $arrayDatos = [
        "cod_cliente" => $arrayAlbaran["cod_cliente"],
        "fecha" => $arrayAlbaran["fecha"],
        "generado_de_pedido" => $arrayAlbaran["generado_de_pedido"],
        "concepto" => $arrayAlbaran["concepto"],
        "arrayLineas" => $arrayAlbaran["linAlb"]
    ];
    $controlador->crear($arrayDatos);
}
if ($_REQUEST["evento"] == "editar") {

    $arrayAlbaran = $_REQUEST["arrayAlbaran"];
    $controlador->editar($arrayAlbaran);
}
