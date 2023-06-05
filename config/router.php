<?php 
$tablasValidas=["clientes", "articulos", "pedidos", "albaranes", "facturas"];
$accionesInvisibles=["guardar","borrar"];

$invisible=false;
$tabla="";
$contenido= "views/inicio.php";
$titulo="Inicio";
$ruta_js="";
if (isset($_REQUEST["tabla"],$_REQUEST["accion"])){
    $tabla=$_REQUEST["tabla"];
    if (!in_array ($tabla, $tablasValidas)){
        $contenido= ("views/404.php");
        $titulo="Error, Pagina No encontrada";
    }
    else{
        $titulo= ucwords($_REQUEST["accion"]." ". $_REQUEST["tabla"]);

        if (in_array($_REQUEST["accion"],$accionesInvisibles)) $invisible=true;
        
        switch ($_REQUEST["accion"]){
            case "crear":
                $contenido= "views/$tabla/create.php";
                $ruta_js="views/$tabla/js/create.js";
                break;
            case "guardar":
                $contenido= ("views/$tabla/store.php");
                break;
            case "ver":
                $contenido= ("views/$tabla/show.php");
                break;
            case "listar":
                $contenido= ("views/$tabla/list.php");
                $ruta_js="views/$tabla/js/list.js";
                break;
            case "listar2":
                $contenido= ("views/$tabla/list2.php");
                break;
            case "buscar":
                $contenido= ("views/$tabla/search.php");
                $ruta_js="views/$tabla/js/search.js";
                break;
            case "borrar":
                $contenido= ("views/$tabla/delete.php");
                break;
            case "editar":
                $contenido= ("views/$tabla/edit.php");
                $ruta_js="views/$tabla/js/edit.js";
                break;
            case "imprimir":
                $contenido= ("views/$tabla/print.php");
                break;
            default:
                $contenido= ("views/404.php");
                $titulo="Error, Pagina No encontrada";
            break;
            }
    }
}
else if (substr($_SERVER["REQUEST_URI"],-1)=="/"||substr($_SERVER["REQUEST_URI"],-9)=="index.php" ){// Vista Por defecto
  $contenido= "views/inicio.php";
  $titulo="Inicio";
}
else {
    $contenido= ("views/404.php"); 
    $titulo="Error, Pagina No encontrada";
}

if ($invisible)  require_once $contenido;
