<?php
$ruta = (file_exists("models/albaranModel.php")) ? "" : "../../";
require_once $ruta . "models/albaranModel.php";
require_once $ruta. "assets/php/funciones.php";


class AlbaranesController
{
    private $model;

    public function __construct()
    {
        $this->model = new AlbaranModel();
    }

    public function crear($arrayAlbaran){
        $error = false;
        $errores = [];
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        
        $insercion = null;
        if (!$error) $insercion = $this->model->insertar($arrayAlbaran);

        if ($insercion == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayAlbaran;
            $respuesta["ok"] = false;
            $respuesta["errores"] = $errores;
            $respuesta["datos"] = $arrayAlbaran;
            echo json_encode($respuesta);
        } else {
            $respuesta["ok"] = true;
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $respuesta["errores"] = [];
            $respuesta["datos"] = $arrayAlbaran;
            echo json_encode($respuesta);
        }
    }

    public function editar(string $arrayAlbaran): void
    {
        $respuesta = [];
        $arrayLinAlb = json_decode($arrayAlbaran, JSON_OBJECT_AS_ARRAY);
        $resultado = $this->model->editar($arrayLinAlb);
        $respuesta["ok"] = $resultado;
        echo json_encode($respuesta);
    }

    public function listar(): array
    {
        $albaranes = $this->model->listarCompleto();
        return $albaranes;
    }


    public function buscar(string $tabla, string $campo, string $metodoBusqueda, string $texto): array
    {
        $albaranes = $this->model->buscar($tabla, $campo, $metodoBusqueda, $texto);
        return $albaranes;
    }

    public function borrarAlbaran($arrayDatos){
        //Si se ha borrado de forma correcta restamos 
        $respuesta = [];
        $borrado = $this->model->borrar($arrayDatos["cod_albaran"]);
        $respuesta["ok"] = $borrado;
        echo json_encode($respuesta);
    }
}