<?php
$ruta = (file_exists("models/clienteModel.php")) ? "" : "../../";
require_once $ruta . "models/clienteModel.php";
$ruta = (file_exists("controllers/pedidosController.php")) ? "" : "../../";
require_once $ruta . "controllers/pedidosController.php";
require_once $ruta . "assets/php/funciones.php";
class ClientesController
{
    private $model;

    public function __construct()
    {
        $this->model = new clienteModel();
    }

    public function crear(array $arrayCliente): void
    {
        //controles correspondientes
        //preciovent sea numero y mayor a 0, dos decimales
        $error = false;
        $errores = [];
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];


        //campos NO VACIOS
        $arrayNoNulos = ["cif_dni", "razon_social", "domicilio_social", "ciudad", "email", "telefono", "nick", "contrasenya"];
        $nulos = HayNulos($arrayNoNulos, $arrayCliente);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //Comprobamos los datos
        if (!is_valid_dni($arrayCliente["cif_dni"])) {
            $error = true;
            $errores["cif_dni"][] = "El dni no es válido";
        }
        if (!contieneSoloLetras($arrayCliente["ciudad"])) {
            $error = true;
            $errores["ciudad"][] = "La ciudad no puede contener números";
        }
        if (!validarEmail($arrayCliente["email"])) {
            $error = true;
            $errores["email"][] = "El email no coincide con el patrón";
        }
        if (!validarTelefono($arrayCliente["telefono"])) {
            $error = true;
            $errores["telefono"][] = "El teléfono debe componerse de un prefijo seguido de 9 números";
        }
        if(!contieneSoloLetras($arrayCliente["nick"])){
            $error = true;
            $errores["nick"][] = "El nick no puede contener números";
        }

        //CAMPOS UNICOS
        $arrayUnicos = ["cif_dni", "nick"];
        foreach ($arrayUnicos as $CampoUnico) {
            if ($this->model->exists($CampoUnico, $arrayCliente[$CampoUnico])) {
                $errores[$CampoUnico][] = "El {$CampoUnico} - {$arrayCliente[$CampoUnico]}  ya existe";
                $error = true;
            }
        }
        $id = null;
        if (!$error) $id = $this->model->insertar($arrayCliente);

        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayCliente;
            $respuesta["ok"] = false;
            $respuesta["errores"] = $errores;
            $respuesta["datos"] = $arrayCliente;
            echo json_encode($respuesta);
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $respuesta["ok"] = true;
            $respuesta["errores"] = [];
            $respuesta["datos"] = $arrayCliente;
            echo json_encode($respuesta);
        }
    }

    public function listar(): array
    {
        $clientes = $this->model->listar();
        return $clientes;
    }



    public function editar(array $arrayCliente){
        $error = false;
        $errores = [];
        $_SESSION["errores"] = [];
        $_SESSION["datos"] = [];

        //campos NO VACIOS
        $arrayNoNulos = ["razon_social", "domicilio_social", "ciudad", "email", "telefono", "nick", "contrasenya"];
        $nulos = HayNulos($arrayNoNulos, $arrayCliente);
        if (count($nulos) > 0) {
            $error = true;
            for ($i = 0; $i < count($nulos); $i++) {
                $errores[$nulos[$i]][] = "El campo {$nulos[$i]} es nulo";
            }
        }

        //Comprobamos los datos
        if (!contieneSoloLetras($arrayCliente["ciudad"])) {
            $error = true;
            $errores["ciudad"][] = "La ciudad no puede contener números";
        }
        if (!validarEmail($arrayCliente["email"])) {
            $error = true;
            $errores["email"][] = "El email no coincide con el patrón";
        }
        if (!validarTelefono($arrayCliente["telefono"])) {
            $error = true;
            $errores["telefono"][] = "El teléfono debe componerse de un prefijo seguido de 9 números";
        }
        if(!contieneSoloLetras($arrayCliente["nick"])){
            $error = true;
            $errores["nick"][] = "El nick no puede contener números";
        }

        //CAMPOS UNICOS
        if($arrayCliente["nick"] != $arrayCliente["old_nick"]){
            $arrayUnicos = ["nick"];
            foreach ($arrayUnicos as $CampoUnico) {
                if ($this->model->exists($CampoUnico, $arrayCliente[$CampoUnico])) {
                    $errores[$CampoUnico][] = "El {$CampoUnico} - {$arrayCliente[$CampoUnico]}  ya existe";
                    $error = true;
                }
            }
        }
        

        
        $id = null;
        if (!$error) $id = $this->model->editar($arrayCliente);

        if ($id == null) {
            $_SESSION["errores"] = $errores;
            $_SESSION["datos"] = $arrayCliente;
            $respuesta["ok"] = false;
            $respuesta["errores"] = $errores;
            $respuesta["datos"] = $arrayCliente;
            echo json_encode($respuesta);
        } else {
            unset($_SESSION["errores"]);
            unset($_SESSION["datos"]);
            $respuesta["ok"] = true;
            $respuesta["errores"] = [];
            $respuesta["datos"] = $arrayCliente;
            echo json_encode($respuesta);
        }
    }

    public function buscar(string $campo, string $metodoBusqueda, string $texto): array
    {
        return $this->model->buscar($campo, $metodoBusqueda, $texto);
    }


    public function cambiarEstado($id, $estado){
        if($estado == "Disponible"){
            $estado = "No disponible";
        }else{
            $estado = "Disponible";
        }
        $modificado = $this->model->cambiarEstado($id, $estado);
        $respuesta = [];
        $respuesta["ok"] = $modificado;
        echo json_encode($respuesta);
    }

    public function listarClientesConAlbaran(){
        $clientes = $this->model->listarClientesConAlbaran();
        return $clientes;
    }

}
