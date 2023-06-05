<?php
$ruta=(file_exists("controllers/pedidosController.php"))?"":"../../";
require_once $ruta."controllers/pedidosController.php";
if (!isset ($_REQUEST["idPedido"])&&!isset($_REQUEST["idLinea"])) header("Location:{$ruta}index.php");
$controlador= new pedidosController();
if(isset($_REQUEST["idPedido"])){
    $id = $_REQUEST["idPedido"];
    $controlador->borrarPedido ($id);
}else{
    $id = $_REQUEST["idLinea"];
    $controlador->borrarLinea ($id);
}



