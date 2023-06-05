<?php
$ruta = (file_exists("models/articuloModel.php")) ? "" : "../../";
require_once $ruta . "models/articuloModel.php";
require_once $ruta . "assets/php/funciones.php";
class ArticulosController
{
    private $model;

    public function __construct()
    {
        $this->model = new articuloModel();
    }

    public function crear(array $arrayArticulo): void
    {
        $error = false;
        $errores = [];
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];



        //campos NO VACIOS
        $arrayNoNulos = ["nombre", "descripcion", "precio", "descuento", "iva"];
        $nulos = HayNulos($arrayNoNulos, $arrayArticulo);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //Comprobamos los datos numéricos

        //Es numérico
        if (contieneSoloNumeros($arrayArticulo["precio"])) {
            $error = true;
            $errores["precio"][] = "El precio debe ser un número";
        }
        if (contieneSoloNumeros($arrayArticulo["descuento"])) {
            $error = true;
            $errores["descuento"][] = "El descuento debe ser un número entre 0 y 100";
        }
        if (contieneSoloNumeros($arrayArticulo["iva"])) {
            $error = true;
            $errores["iva"][] = "El precio debe ser un número entre 0 y 100";
        }

        //Si los números están entre los valores permitidos
        if ($arrayArticulo["precio"] <= 0) {
            $error = true;
            $errores["precio"][] = "El precio debe ser mayor a 0";
        }
        if ($arrayArticulo["descuento"] < 0) {
            $error = true;
            $errores["descuento"][] = "El descuento no puede ser menor a 0";
        }
        if ($arrayArticulo["iva"] < 0) {
            $error = true;
            $errores["iva"][] = "El IVA no puede ser menor a 0";
        }
        if ($arrayArticulo["descuento"] > 100) {
            $error = true;
            $errores["descuento"][] = "El descuento no puede ser mayor al 100%";
        }
        if ($arrayArticulo["iva"] > 100) {
            $error = true;
            $errores["iva"][] = "El iva no puede ser mayor al 100%";
        }

        //Imagen
        $extensiones_permitidas = ["png", "jpg"];
        if (!imagenValida($extensiones_permitidas, $arrayArticulo["imagen"])) {
            $error = true;
            $errores["imagen"][] = "La imagen no es válida";
        }

        //CAMPOS UNICOS
        $arrayUnicos = ["nombre"];

        foreach ($arrayUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayArticulo[$CampoUnico])) {
                $errores[$CampoUnico][] = "El {$CampoUnico} {$arrayArticulo[$CampoUnico]} de {$CampoUnico} ya existe";
                $error = true;
            }
        }


        $id = null;
        if (!$error){
            $id = $this->model->insertar($arrayArticulo);
            
            //Guardamos la imagen si no ha ocurrido ningún error
            $nombreImagen = $id . '.png';
            $rutaArchivo = 'img/' . $nombreImagen;
            $imagenAlmacenada = move_uploaded_file($arrayArticulo['imagen']["tmp_name"], $rutaArchivo);
            if(!$imagenAlmacenada){
                $id == null;
                $errores["imagen"][] = "La imagen no se ha podido almacenar";
            }
        } 

        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayArticulo;
            $respuesta["ok"] = false;
            $respuesta["errores"] = $errores;
            $respuesta["datos"] = $arrayArticulo;
            echo json_encode($respuesta);
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $respuesta["ok"] = true;
            $respuesta["errores"] = [];
            $respuesta["datos"] = $arrayArticulo;

            
            echo json_encode($respuesta);
        }
    }

    public function listar(): array
    {
        $articulos = $this->model->listar();
        return $articulos;
    }


    public function buscar(string $campo, string $metodoBusqueda, string $texto): array
    {
        return $this->model->buscar($campo, $metodoBusqueda, $texto);
    }

    public function editar($cod_articulo, $arrayArticulo)
    {
        $errores = [];
        //MONTAR SISTEMA DE ERRORES
        $modificacion = $this->model->editar($cod_articulo, $arrayArticulo);
        $respuesta["ok"] = false;
        if ($modificacion) {
            $respuesta["ok"] = true;
             //Guardamos la imagen si no ha ocurrido ningún error
             $nombreImagen = $cod_articulo . '.png';
             $rutaArchivo = 'img/' . $nombreImagen;
             $imagenAlmacenada = move_uploaded_file($arrayArticulo['imagen']["tmp_name"], $rutaArchivo);
             if(!$imagenAlmacenada){
                $errores["imagen"][] = "La imagen no se ha podido almacenar";
             }
        }
        $respuesta["errores"] = $errores;

        echo json_encode($respuesta);
    }

    public function cambiarEstado($id, $estado)
    {
        if ($estado == "Disponible") {
            $estado = "No disponible";
        } else {
            $estado = "Disponible";
        }
        $modificado = $this->model->cambiarEstado($id, $estado);
        $respuesta = [];
        $respuesta["ok"] = $modificado;
        echo json_encode($respuesta);
    }
}
