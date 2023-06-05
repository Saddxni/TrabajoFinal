<?php
$ruta=(file_exists("controllers/albaranesController.php"))?"":"../../";
require_once $ruta."controllers/albaranesController.php";
$controlador = new AlbaranesController();
$respuesta["ok"]=false;
$respuesta["datos"]=[];

if (isset($_REQUEST["evento"])){
    switch ($_REQUEST["evento"]){
      case "todos":
        $respuesta["datos"] = $controlador->listar();
        $respuesta["ok"]=true;
        break;
      case "filtrar":
        
        $campo=($_REQUEST["campo"])??"cod_albaran";
        $metodo=($_REQUEST["metodoBusqueda"])??"contiene";
        $texto=($_REQUEST["busqueda"])??"";
        switch($campo){
          case "cod_cliente":
            $tabla = "albaranes";
            break;
          default: $tabla = "lineas_albaran";
        }
        
        $respuesta["datos"] = $controlador->buscar($tabla, $campo, $metodo, $texto);
        $respuesta["ok"]=true;
        break;
      }
    } 
echo json_encode($respuesta);



