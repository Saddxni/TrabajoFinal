<?php
$ruta = (file_exists("controllers/facturasController.php")) ? "" : "../../";
require_once $ruta . "controllers/facturasController.php";
if (!isset($_REQUEST["cod_factura"]) && !isset($_REQUEST["cod_linea_factura"])) header("Location:{$ruta}index.php");
$controlador = new facturasController();
if(isset($_REQUEST["cod_factura"])){
    $cod_factura = $_REQUEST["cod_factura"];
    $controlador->borrarFactura($cod_factura);
}else{
    $cod_linea_factura = $_REQUEST["cod_linea_factura"];
    $controlador->desfacturar ($cod_linea_factura);
}