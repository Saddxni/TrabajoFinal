<?php
$ruta=(file_exists("controllers/facturasController.php"))?"":"../../";
require_once $ruta."controllers/facturasController.php";
$controlador = new FacturasController();
$respuesta["ok"]=false;
$respuesta["datos"]=[];

if (isset($_REQUEST["evento"])){
    switch ($_REQUEST["evento"]){
      case "todos":
        $respuesta["datos"] = $controlador->listar();
        $respuesta["ok"]=true;
        break;
      case "filtrar":
        
        $campo=($_REQUEST["campo"])??"cod_factura";
        $metodo=($_REQUEST["metodoBusqueda"])??"contiene";
        $texto=($_REQUEST["busqueda"])??"";
        switch($campo){
          case "cod_factura":
            $tabla = "facturas";
            break;
          default: $tabla = "lineas_facturas";
        }
        
        $respuesta["datos"] = $controlador->buscar($tabla, $campo, $metodo, $texto);
        $respuesta["ok"]=true;
        break;
      }
    } 
echo json_encode($respuesta);



