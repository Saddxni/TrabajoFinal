<?php
$ruta = (file_exists("config/db.php")) ? "" : "../../";
require_once $ruta . 'config/db.php';

class PedidoModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }
    public function insertar(array $pedido): ?string
    {
        try {
            //Abrimos una transacción
            //NO FUNCIONA POR ALGUNA RAZÓN 
            $this->conexion->beginTransaction();
            //Insertamos el pedido
            $sql = "INSERT INTO pedidos(cod_cliente, fecha)  VALUES (:cod_cliente,:fecha);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":cod_cliente" => $pedido["cod_cliente"],
                ":fecha" => $pedido["fecha"]
            ];
            $resultado = $sentencia->execute($arrayDatos);
            $cod_pedido = $this->conexion->lastInsertId();

            //Si la inserción del pedido ha ido bien entonces insertamos las líneas
            if ($resultado) {
                $sql = "INSERT INTO lineas_pedidos(cod_pedido, precio, cantidad, cod_articulo)  
            VALUES (:cod_pedido, :precio, :cantidad, :cod_articulo);";
                $sentencia = $this->conexion->prepare($sql);
                $arrayArticulos = json_decode($pedido["arrayArticulos"], JSON_OBJECT_AS_ARRAY);
                foreach ($arrayArticulos as $indice => $lineaPedido) {
                    $arrayDatos = [
                        ":cod_pedido" => $cod_pedido,
                        ":precio" => $lineaPedido["precio"],
                        ":cantidad" => $lineaPedido["cantidad"],
                        ":cod_articulo" => $lineaPedido["cod_articulo"]
                    ];
                    $resultado = $sentencia->execute($arrayDatos);
                    //Si alguna de las lineas no se inserta de forma correcta salimos del foreach
                    if (!$resultado) {
                        break;
                    }
                }
            }
            //Si todo ha ido bien, realizamos el commit
            if ($resultado) {
                $this->conexion->commit();
            }
            return $resultado;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            $this->conexion->rollBack();
            return false;
        }
    }

    public function listarCompleto(): array
    {
        $sentencia = $this->conexion->prepare("SELECT * FROM pedidos;");
        $sentencia->execute();
        $pedidos = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        foreach ($pedidos as $key => $pedido) {
            $sentencia = $this->conexion->prepare("SELECT lineas_pedidos.*, articulos.nombre 
            FROM lineas_pedidos JOIN articulos ON 
            lineas_pedidos.cod_articulo = articulos.cod_articulo WHERE cod_pedido = {$pedido['cod_pedido']}");
            $sentencia->execute();
            $linea = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $pedidos[$key]["pedidos"] = $linea;
        }
        return $pedidos;
    }

    public function borrarPedido($cod_pedido)
    {
        try {
            $sentencia = $this->conexion->prepare("DELETE FROM lineas_pedidos WHERE cod_pedido = $cod_pedido");
            $sentencia->execute();
            $resultado = ($sentencia->rowCount() <= 0) ? false : true;
            return $resultado;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function borrarLinea(int $cod_linea): bool
    {
        $sql = "DELETE FROM lineas_pedidos WHERE num_linea_pedido = $cod_linea";
        try {
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute();
            $resultado = ($sentencia->rowCount() <= 0) ? false : true;
            return $resultado;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }



    public function editar(array $arrayLinPed): bool
    {
        try {
            $sql = "UPDATE lineas_pedidos SET cantidad = :cantidad WHERE num_linea_pedido = :num_linea_pedido";
            foreach ($arrayLinPed as $key => $linea) {
                $arrayDatos = [
                    ":cantidad" => $linea["cantidad"],
                    ":num_linea_pedido" => $linea["num_linea"]
                ];
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute($arrayDatos);
            $resultado = ($sentencia->rowCount() <= 0) ? false : true ;
            if(!$resultado){
                break;
            } 
        }
            return $resultado;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function search(string $tabla, string $campo, string $metodoBusqueda, string $dato): array
    {

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
        $sentencia = $this->conexion->prepare("SELECT DISTINCT pedidos.* FROM pedidos JOIN lineas_pedidos ON pedidos.cod_pedido = lineas_pedidos.cod_pedido JOIN articulos ON lineas_pedidos.cod_articulo = articulos.cod_articulo WHERE $tabla.$campo LIKE :dato");
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $pedidos = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        foreach ($pedidos as $key => $pedido) {
            $sentencia = $this->conexion->prepare("SELECT lineas_pedidos.*, articulos.nombre 
            FROM lineas_pedidos JOIN articulos ON 
            lineas_pedidos.cod_articulo = articulos.cod_articulo WHERE cod_pedido = {$pedido['cod_pedido']}");
            $sentencia->execute();
            $linea = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $pedidos[$key]["pedidos"] = $linea;
        }

        return $pedidos;
    }

    public function cambiarCantAlb($cod_pedido, $estado)
    {
        try {
            $sentencia = $this->conexion->prepare("UPDATE pedidos SET estado = '$estado' WHERE cod_pedido = $cod_pedido;");
            $resultado = $sentencia->execute();
            return $resultado;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }
}