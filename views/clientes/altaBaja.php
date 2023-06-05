<?php
$ruta=(file_exists("controllers/clientesController.php"))?"":"../../";
require_once $ruta."controllers/clientesController.php";
if (!isset ($_REQUEST["id"])) header('Location:index.php' );
$id=$_REQUEST["id"];
$estado = $_REQUEST["estado"];
$controlador= new clientesController();
$controlador->cambiarEstado($id, $estado);

