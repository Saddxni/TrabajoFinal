<?php
$ruta=(file_exists("controllers/pedidosController.php"))?"":"../../";
require_once $ruta."controllers/pedidosController.php";
$controlador = new PedidosController();

$campo="cod_cliente";$metodo="contiene";$texto="";
$respuesta["ok"]=false;
$respuesta["datos"]=[];

if (isset($_REQUEST["evento"])){
    switch ($_REQUEST["evento"]){
      case "todos":
        $respuesta["datos"] = $controlador->listar();
        $respuesta["ok"]=true;
        break;
      case "filtrar":
        $campo=($_REQUEST["campo"])??"cod_pedido";
        $metodo=($_REQUEST["metodoBusqueda"])??"contiene";
        $texto=($_REQUEST["busqueda"])??"";
        switch($campo){
          case "nombre":
            $tabla = "articulos";
            break;
          
          default: $tabla = "pedidos";
        }
        
        $respuesta["datos"] = $controlador->buscar($tabla, $campo, $metodo, $texto);
        $respuesta["ok"]=true;
        break;
      }
    }

echo json_encode($respuesta);



