<?php
$ruta = (file_exists("controllers/pedidosController.php")) ? "" : "../../";
require_once $ruta . "controllers/pedidosController.php";
$controlador = new pedidosController();

if (!isset($_REQUEST["cliente"]) && (!isset($_REQUEST["cod_pedido"]))) header('Location:index.php?accion=crear&tabla=pedidos');

if ($_REQUEST["evento"] == "crear") {
    $cod_pedido = ($_REQUEST["cod_pedido"]) ?? "";
    $fecha = $newDate = date("Y-m-d", strtotime($_REQUEST["fecha"]));
    $arrayPedido = [
        "cod_pedido" => $cod_pedido,
        "cod_cliente" => $_REQUEST["cliente"],
        "fecha" => $fecha,
        "arrayArticulos" => $_REQUEST["arrayArticulos"]
    ];
    $controlador->crear($arrayPedido);
}

if ($_REQUEST["evento"] == "editar") {
    $arrayLinPed = $_REQUEST["arrayArticulos"];
    $controlador->editar($arrayLinPed);
}
