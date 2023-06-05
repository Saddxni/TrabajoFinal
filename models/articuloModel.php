<?php
$ruta = (file_exists("config/db.php")) ? "" : "../../";
require_once $ruta . 'config/db.php';


class articuloModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function insertar(array $articulos): ?string //devuelvo entero o null
    {
        try {
            $sql = "INSERT INTO articulos(nombre, descripcion, precio, descuento, iva)  VALUES (:nombre, :descripcion, :precio, :descuento, :iva);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":nombre" => $articulos["nombre"],
                ":descripcion" => $articulos["descripcion"],
                ":precio" => $articulos["precio"],
                ":descuento" => $articulos["descuento"],
                ":iva" => $articulos["iva"]
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
        $sentencia = $this->conexion->prepare("SELECT * FROM articulos;");
        $sentencia->execute();
        $articulos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $articulos;
    }


    public function buscar(string $campo, string $metodoBusqueda, string $dato): array
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM articulos WHERE $campo LIKE :dato");
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
        $articulos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        return $articulos;
    }


    public function borrar(string $cod_articulo): bool
    {
        $sql = "DELETE FROM articulos WHERE cod_articulo =:cod_articulo";
        try {
            $sentencia = $this->conexion->prepare($sql);
            $resultado = $sentencia->execute([":cod_articulo" => $cod_articulo]);

            return ($sentencia->rowCount() <= 0) ? false : true;
        } catch (Exception $e) {

            return false;
        }
    }

    public function editar($arrayDatos): bool{
        $sql = "UPDATE articulos SET nombre = :nombre, descripcion = :descripcion, precio = :precio, descuento = :descuento, iva = :iva WHERE cod_articulo = :cod_articulo;";
        $array = [
            ":nombre" => $arrayDatos["nombre"],
            ":descripcion"=> $arrayDatos["descripcion"],
            ":precio"=> $arrayDatos["precio"],
            ":descuento"=> $arrayDatos["descuento"],
            ":iva" => $arrayDatos["iva"],
            ":cod_articulo" => $arrayDatos["cod_articulo"]
        ];
        try {
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute($array);
            return ($sentencia->rowCount() <= 0) ? false : true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function exists(string $campo, string $valor):bool
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM articulos WHERE $campo=:valor");
        $arrayDatos = [":valor" => $valor];
        $resultado = $sentencia->execute($arrayDatos);
        return (!$resultado || $sentencia->rowCount()<=0)?false:true;
    }

    public function cambiarEstado($id, $estado): bool{
        try{
            $arrayDatos = [
                ":id" => $id,
                ":estado" => $estado,
            ];
            $sentencia = $this->conexion->prepare("UPDATE articulos SET disponibilidad = :estado WHERE cod_articulo = :id;");
            $sentencia->execute($arrayDatos);
            return ($sentencia->rowCount() <= 0) ? false : true;
        }catch(Exception $e){
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }
}
