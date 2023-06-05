<?php
$ruta = (file_exists("config/db.php")) ? "" : "../../";
require_once $ruta . 'config/db.php';

class AlbaranModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function insertar(array $datosAlbaran): ?string
    {
       try {
            //Abrimos una transacción
            //NO FUNCIONA POR ALGUNA RAZÓN 
            $this->conexion->beginTransaction();

            //Insertamos el albarán
            $sql = "INSERT INTO albaranes(cod_cliente, fecha, generado_de_pedido, concepto)  VALUES (:cod_cliente,:fecha,:generado_de_pedido, :concepto);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":cod_cliente" => $datosAlbaran["cod_cliente"],
                ":fecha" => $datosAlbaran["fecha"],
                ":generado_de_pedido" => $datosAlbaran["generado_de_pedido"],
                ":concepto" => $datosAlbaran["concepto"]
            ];
            $resultado = $sentencia->execute($arrayDatos);
            $cod_albaran = $this->conexion->lastInsertId();
            //A partir del ID del albarán insertado creamos las líneas del albarán
            
            if($resultado){
                $sql = "INSERT INTO lineas_albaran(cod_albaran, cod_pedido, num_linea_pedido, cod_articulo, precio, cantidad, descuento, iva, cod_usu_gestion)  
            VALUES (:cod_albaran, :cod_pedido, :num_linea_pedido, :cod_articulo, :precio, :cantidad, :descuento, :iva, :cod_usu_gestion);";
            $sentencia = $this->conexion->prepare($sql);
            $datosLineaAlbaran = $datosAlbaran["arrayLineas"];
            foreach ($datosLineaAlbaran as $indice => $linea) {
                $arrayDatos = [
                    ":cod_albaran" => $cod_albaran,
                    ":cod_pedido" => $datosAlbaran["generado_de_pedido"],
                    ":num_linea_pedido" => $linea["num_linea_pedido"],
                    ":cod_articulo" => $linea["cod_articulo"],
                    ":precio" => $linea["precio"],
                    ":cantidad" => $linea["cantidad"],
                    ":descuento" => $linea["descuento"],
                    ":iva" => $linea["iva"],
                    ":cod_usu_gestion" => 1
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
            return false;
        }
    }

    public function editar(array $arrayAlbaran): bool
    {
        try {
            $sql = "UPDATE lineas_albaran SET iva = :iva, descuento = :descuento WHERE num_linea_albaran = :num_linea_albaran";
            foreach ($arrayAlbaran as $indice => $linea) {
                $arrayDatos = [
                    ":iva" => $linea["iva"],
                    ":descuento" => $linea["descuento"],
                    ":num_linea_albaran" => $linea["num_linea_albaran"]
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

    public function listarCompleto(): array
    {
        //Listamos los albaranes
        $sentencia = $this->conexion->prepare("SELECT * FROM albaranes");
        $sentencia->execute();
        $albaranes = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        //Añadimos las líneas a cada albaran.
        foreach ($albaranes as $key => $albaran) {
            $sentencia = $this->conexion->prepare("SELECT lineas_albaran.*, articulos.nombre FROM lineas_albaran 
            JOIN articulos ON lineas_albaran.cod_articulo = articulos.cod_articulo WHERE cod_albaran = {$albaran['cod_albaran']}");
            $sentencia->execute();
            $linea = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $albaranes[$key]["lineaAlbaran"] = $linea;
        }
        return $albaranes;
    }



    public function buscar(string $tabla, string $campo, string $metodoBusqueda, string $dato): array
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
        $sentencia = $this->conexion->prepare("SELECT DISTINCT albaranes.* FROM albaranes JOIN lineas_albaran ON albaranes.cod_albaran = lineas_albaran.cod_albaran JOIN articulos ON lineas_albaran.cod_articulo = articulos.cod_articulo WHERE $tabla.$campo LIKE :dato");
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $albaranes = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        foreach ($albaranes as $key => $albaran) {
            $sentencia = $this->conexion->prepare("SELECT lineas_albaran.*, articulos.nombre 
            FROM lineas_albaran JOIN articulos ON 
            lineas_albaran.cod_articulo = articulos.cod_articulo WHERE cod_albaran = {$albaran['cod_albaran']}");
            $sentencia->execute();
            $linea = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $albaranes[$key]["lineaAlbaran"] = $linea;
        }

        return $albaranes;
    }

    public function borrar($cod_albaran): bool
    {
        try {
            //Borramos las líneas
            $sentencia = $this->conexion->prepare("DELETE FROM lineas_albaran WHERE cod_albaran = $cod_albaran");
            $sentencia->execute();
            $resultado =  ($sentencia->rowCount() <= 0) ? false : true;
            //Borramos el albarán
            if($resultado){
                $sentencia = $this->conexion->prepare("DELETE FROM albaranes WHERE cod_albaran = $cod_albaran");
                $sentencia->execute();
            }
            return ($sentencia->rowCount() <= 0) ? false : true;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }
}
