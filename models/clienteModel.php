<?php
$ruta = (file_exists("config/db.php")) ? "" : "../../";
require_once $ruta . 'config/db.php';


class clienteModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }


    public function insertar(array $cliente): ?string //devuelvo entero o null
    {
        try {
            $sql = "INSERT INTO clientes(cif_dni, razon_social, domicilio_social, ciudad, email, telefono, nick ,contraseña)  VALUES (:cif_dni, :razon_social, :domicilio_social, :ciudad ,:email ,:telefono ,:nick ,:contrasenya);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":cif_dni" => $cliente["cif_dni"],
                ":razon_social" => $cliente["razon_social"],
                ":domicilio_social" => $cliente["domicilio_social"],
                ":ciudad" => $cliente["ciudad"],
                ":email" => $cliente["email"],
                ":telefono" => $cliente["telefono"],
                ":nick" => $cliente["nick"],
                ":contrasenya" => $cliente["contrasenya"]
            ];
            $resultado = $sentencia->execute($arrayDatos);
            $id = $this->conexion->lastInsertId();
            return ($resultado == true) ? $id : null;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return null;
        }
    }

    public function listar(): array
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM clientes;");
        $sentencia->execute();
        $clientes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $clientes;
    }

    public function editar(array $cliente): bool
    {
        try {
            $sql = "UPDATE clientes SET razon_social=:razon_social, domicilio_social=:domicilio_social, ciudad=:ciudad ,email=:email ,telefono=:telefono ,nick=:nick ,contraseña=:contrasenya";
            $sql .= " WHERE cod_cliente = :cod_cliente;";
            $arrayDatos = [
                ":cod_cliente" => $cliente["cod_cliente"],
                ":razon_social" => $cliente["razon_social"],
                ":domicilio_social" => $cliente["domicilio_social"],
                ":ciudad" => $cliente["ciudad"],
                ":email" => $cliente["email"],
                ":telefono" => $cliente["telefono"],
                ":nick" => $cliente["nick"],
                ":contrasenya" => $cliente["contrasenya"]
            ];
            $sentencia = $this->conexion->prepare($sql);
            return $sentencia->execute($arrayDatos);
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function cambiarEstado($id, $estado): bool
    {
        try {
            $arrayDatos = [
                ":id" => $id,
                ":estado" => $estado,
            ];
            $sentencia = $this->conexion->prepare("UPDATE clientes SET disponibilidad = :estado WHERE cod_cliente = :id;");
            $sentencia->execute($arrayDatos);
            return ($sentencia->rowCount() <= 0) ? false : true;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function buscar(string $campo, string $metodoBusqueda, string $dato): array
    {
        try {
            $sentencia = $this->conexion->prepare("SELECT * FROM clientes WHERE $campo LIKE :dato");
            switch ($metodoBusqueda) {
                case "empieza":
                    $arrayDatos = [":dato" => "$dato%"];
                    break;
                case "contiene":
                    $arrayDatos = [":dato" => "%$dato%"];
                    break;
                case "igual":
                    $arrayDatos = [":dato" => "$dato"];
                    break;
                case "acaba":
                    $arrayDatos = [":dato" => "%$dato"];
                    break;
                default:
                    $arrayDatos = [":dato" => "%$dato%"];
                    break;
            }
            $resultado = $sentencia->execute($arrayDatos);
            if (!$resultado) return [];
            $clientes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            return $clientes;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function exists(string $campo, string $valor): bool
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM clientes WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount() <= 0) ? false : true;
    }

    //ESTO NO SÉ SI ESTÁ BIEN
    public function listarClientesConAlbaran(){
        $sentencia = $this->conexion->prepare("SELECT DISTINCT clientes.cod_cliente, clientes.nick FROM clientes JOIN albaranes ON clientes.cod_cliente = albaranes.cod_cliente");
        $sentencia->execute();
        $clientes = $sentencia->fetchAll();
        return $clientes;
    }
}
