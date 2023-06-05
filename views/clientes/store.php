<?php
$ruta = (file_exists("controllers/clientesController.php")) ? "" : "../../";
require_once $ruta . "controllers/clientesController.php";

//recoger datos
if (!isset($_REQUEST["cif_dni"])) header('Location:index.php?accion=crear&tabla=clientes');

$cod_cliente = ($_REQUEST["cod_cliente"]) ?? ""; //el id me servirá en editar

$arrayCliente = [
    "cod_cliente" => $cod_cliente,
    "cif_dni" => ($_REQUEST["cif_dni"]) ?? "",
    "razon_social" => $_REQUEST["razon_social"],
    "domicilio_social" => $_REQUEST["domicilio_social"],
    "ciudad" => $_REQUEST["ciudad"],
    "email" => $_REQUEST["email"],
    "telefono" => $_REQUEST["telefono"],
    "nick" => $_REQUEST["nick"],
    "old_nick" => ($_REQUEST["old_nick"]) ?? "",
    "contrasenya" => $_REQUEST["contraseña"]
];
//pagina invisible
$controlador = new clientesController();
if ($_REQUEST["evento"] == "crear") {
    $controlador->crear($arrayCliente);
}


if ($_REQUEST["evento"] == "editar") {
    $controlador->editar($arrayCliente);
}
