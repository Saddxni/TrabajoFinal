<?php
$ruta = (file_exists("models/facturaModel.php")) ? "" : "../../";
require_once $ruta . "models/facturaModel.php";
require_once $ruta. "assets/php/funciones.php";


class FacturasController
{
    private $model;

    public function __construct()
    {
        $this->model = new FacturaModel();
    }

    public function crear($arrayFactura){
        $error = false;
        $errores = [];

        $insercion = null;
        if (!$error) $insercion = $this->model->insertar($arrayFactura);

        if ($insercion == false) {
            $respuesta["ok"] = false;
            $respuesta["errores"] = $errores;
            $respuesta["datos"] = $arrayFactura;
            echo json_encode($respuesta);
        } else {
            $respuesta["ok"] = true;
            $respuesta["errores"] = [];
            $respuesta["datos"] = $arrayFactura;
            echo json_encode($respuesta);
        }
    }

    public function editar($arrayFactura){
        $respuesta = [];
        $resultado = $this->model->editar($arrayFactura);
        $respuesta["ok"] = $resultado;
        echo json_encode($respuesta);
        return $resultado;
    }

    public function listar(): array
    {
        $facturas = $this->model->listarCompleto();
        return $facturas;
    }


    public function buscar(string $tabla, string $campo, string $metodoBusqueda, string $texto): array
    {
        $albaranes = $this->model->buscar($tabla, $campo, $metodoBusqueda, $texto);
        return $albaranes;
    }

    public function borrarFactura($cod_factura){
        //Si se ha borrado de forma correcta restamos 
        $respuesta = [];
        $borrado = $this->model->borrar($cod_factura);
        $respuesta["ok"] = $borrado;
        echo json_encode($respuesta);
    }

    public function desfacturar(int $numLineaFactura): void
    {
        $respuesta = [];
        $borrado = $this->model->desfacturar($numLineaFactura);
        $respuesta["ok"] = $borrado;
        echo json_encode($respuesta);
    }
}