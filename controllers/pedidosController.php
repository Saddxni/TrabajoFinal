<?php
$ruta = (file_exists("models/pedidoModel.php")) ? "" : "../../";
require_once $ruta . "models/pedidoModel.php";

$ruta = (file_exists("models/clienteModel.php")) ? "" : "../../";
require_once $ruta . "models/clienteModel.php";


require_once $ruta . "assets/php/funciones.php";
//nombre de los controladores suele ir en plural
class PedidosController
{
    private $model;
    private $clienteModel;

    public function __construct()
    {
        $this->model = new PedidoModel();
        $this->clienteModel = new clienteModel();
    }

    public function crear(array $arrayPedido): void
    {
        $arrayLinPed = json_decode($arrayPedido["arrayArticulos"], JSON_OBJECT_AS_ARRAY);
        $error = false;
        $errores = [];
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        foreach ($arrayLinPed as $indice => $linea) {
            if (!ctype_digit($linea["cantidad"])) {
                $error = true;
                $errores["cantidad"][] = "La cantidad solo puede contener números enteros";
            }
        }
        //campos NO VACIOS
        $arrayNoNulos = ["cod_cliente", "fecha"];
        $nulos = HayNulos($arrayNoNulos, $arrayPedido);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //Comprobamos que sea una fecha "válida" en mi caso voy a poner que las fechas sean válidas a partir del año 2020 y desde el día de hoy
        $fechaMinima = strtotime("01-01-2020");
        $fechaMaxima = strtotime(date("d-m-Y", time()));

        if (strtotime($arrayPedido["fecha"]) < $fechaMinima) {
            $error = true;
            $errores["fecha"][] = "La fecha no puede ser inferior al 1 de enero de 2020";
        }
        if (strtotime($arrayPedido["fecha"]) > $fechaMaxima) {
            $error = true;
            $errores["fecha"][] = "La fecha no puede ser superior al día de hoy";
        }

        //Hacemos  doble check, si no existe el id en la tabla clientes entonces damos un error
        $arrayClientes = $this->clienteModel->listar();
        $arrayId = [];
        foreach ($arrayClientes as $indice => $cliente) {
            $arrayId[] = $cliente["cod_cliente"];
        }

        if (!in_array($arrayPedido["cod_cliente"], $arrayId)) {
            $error = true;
            $errores["cliente"] = "El cliente no existe";
        }


        $insercion = null;
        if (!$error) $insercion = $this->model->insertar($arrayPedido);

        if (!$insercion) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayPedido;
            $respuesta["ok"] = false;
            $respuesta["errores"] = $errores;
            $respuesta["datos"] = $arrayPedido;
            echo json_encode($respuesta);
        } else {
            //$respuesta["ok"] = $this->lineaPedidoModel->insertar($id, $arrayLinPed);
            $respuesta["ok"] = true;
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $respuesta["errores"] = [];
            $respuesta["datos"] = $arrayPedido;
            echo json_encode($respuesta);
        }
    }

    public function listar()
    {
        $resultado = $this->model->listarCompleto();
        return $resultado;
    }

    public function editar(string $arrayPedido): void
    {
        $respuesta = [];
        $arrayLinPed = json_decode($arrayPedido, JSON_OBJECT_AS_ARRAY);
        $resultado = $this->model->editar($arrayLinPed);
        $respuesta["ok"] = $resultado;
        echo json_encode($respuesta);
    }


    public function buscar(string $tabla, string $campo, string $metodoBusqueda, string $texto): array
    {
        var_dump($texto);
        $resultado = $this->model->search($tabla, $campo, $metodoBusqueda, $texto);
        return $resultado;
    }

    public function borrarPedido(int $cod_pedido): void
    {
        $respuesta = [];
        $borrado = $this->model->borrarPedido($cod_pedido);
        $respuesta["ok"] = $borrado;
        echo json_encode($respuesta);
    }

    public function borrarLinea(int $numLinea): void
    {
        $respuesta = [];
        $borrado = $this->model->borrarLinea($numLinea);
        $respuesta["ok"] = $borrado;
        echo json_encode($respuesta);
    }
}
