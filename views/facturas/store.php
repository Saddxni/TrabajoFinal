<?php
$ruta = (file_exists("controllers/facturasController.php")) ? "" : "../../";
require_once $ruta . "controllers/facturasController.php";
$controlador = new facturasController();

if (!isset($_REQUEST["arrayFactura"]) && (!isset($_REQUEST["cod_factura"]))) header('Location:index.php?accion=crear&tabla=facturas');

if ($_REQUEST["evento"] == "crear") {
    //Esto sirve para editar
    $cod_factura = ($_REQUEST["cod_factura"]) ?? "";
    $cod_cliente = ($_REQUEST["busqueda"]) ?? "";
    $descuento_factura = $_REQUEST["descuento"];
    $arrayFactura = json_decode($_REQUEST["arrayFactura"], JSON_OBJECT_AS_ARRAY);
    $concepto = $_REQUEST["concepto"];
    $arrayDatos = [
        "cod_cliente" => $cod_cliente,
        "fecha" => $arrayFactura["fecha"],
        "descuento_factura" => $descuento_factura,
        "concepto" => $concepto,
        "arrayLineas" => $arrayFactura["linFac"]
    ];
    $controlador->crear($arrayDatos);
}

if ($_REQUEST["evento"] == "editar") {
    $arrayDatos = [
        "cod_factura" => $_REQUEST["cod_factura"],
        "descuento_factura" => $_REQUEST["descuento_factura"]
    ];
    $controlador->editar($arrayDatos);
}