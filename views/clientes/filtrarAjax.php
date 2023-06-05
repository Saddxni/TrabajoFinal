<?php
$ruta=(file_exists("controllers/clientesController.php"))?"":"../../";
require_once $ruta."controllers/clientesController.php";
$controlador = new ClientesController();
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
        $campo=($_REQUEST["campo"])??"numpieza";
        $metodo=($_REQUEST["metodoBusqueda"])??"contiene";
        $texto=($_REQUEST["busqueda"])??"";
        //var_dump($_REQUEST);
        $respuesta["datos"] = $controlador->buscar($campo, $metodo, $texto,true);
        $respuesta["ok"]=true;
        break;
      }
    }

    
echo json_encode($respuesta);



