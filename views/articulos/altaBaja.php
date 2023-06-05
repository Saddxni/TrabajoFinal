<?php
$ruta=(file_exists("controllers/articulosController.php"))?"":"../../";
require_once $ruta."controllers/articulosController.php";

if (!isset ($_REQUEST["id"])) header('Location:index.php' );
//recoger datos
$id=$_REQUEST["id"];
$estado = $_REQUEST["estado"];
$controlador = new ArticulosController();
$controlador->cambiarEstado($id, $estado);

