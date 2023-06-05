<?php
$ruta = (file_exists("controllers/articulosController.php")) ? "" : "../../";
require_once $ruta . "controllers/articulosController.php";

if (!isset($_REQUEST["nombre"])) header('Location:index.php?accion=crear&tabla=articulos');

$cod_articulo = ($_REQUEST["cod_articulo"]) ?? ""; 

$arrayArticulo = [
    "nombre" => $_REQUEST["nombre"],
    "descripcion" => $_REQUEST["descripcion"],
    "precio" => $_REQUEST["precio"],
    "descuento" => $_REQUEST["descuento"],
    "iva" => $_REQUEST["iva"],
    "imagen" => $_FILES["imagen"],
];

$controlador = new ArticulosController();
if ($_REQUEST["evento"] == "crear") {
    $controlador->crear($arrayArticulo);
}


if ($_REQUEST["evento"] == "editar") { 
    return $controlador->editar($cod_articulo, $arrayArticulo);
}
