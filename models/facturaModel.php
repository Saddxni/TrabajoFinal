<?php
$ruta = (file_exists("config/db.php")) ? "" : "../../";
require_once $ruta . 'config/db.php';

class FacturaModel
{
    private $conexion;

    public function __construct()
    {
        $this->conexion = db::conexion();
    }

    public function insertar(array $datosFactura): ?string
    {
        try {
            //Abrimos una transacción
            //NO FUNCIONA POR ALGUNA RAZÓN 
            $this->conexion->beginTransaction();
            //Insertamos la factura
            $sql = "INSERT INTO facturas(cod_cliente, fecha, descuento_factura, concepto) VALUES (:cod_cliente, :fecha, :descuento_factura, :concepto);";
            $sentencia = $this->conexion->prepare($sql);
            $arrayDatos = [
                ":cod_cliente" => $datosFactura["cod_cliente"],
                ":fecha" => $datosFactura["fecha"],
                ":descuento_factura" => $datosFactura["descuento_factura"],
                ":concepto" => $datosFactura["concepto"]
            ];
            $resultado = $sentencia->execute($arrayDatos);
            $cod_factura = $this->conexion->lastInsertId();
            //A partir del ID del albarán insertado creamos las líneas del albarán
            if ($resultado) {
                $sql = "INSERT INTO lineas_facturas (precio, cantidad, descuento, iva, cod_factura, cod_articulo, cod_usu_gestion, cod_albaran, num_linea_albaran)
                VALUES(:precio, :cantidad, :descuento, :iva, :cod_factura, :cod_articulo, :cod_usu_gestion, :cod_albaran, :num_linea_albaran);";

                $sentencia = $this->conexion->prepare($sql);
                $datosLineaFactura = $datosFactura["arrayLineas"];
                foreach ($datosLineaFactura as $indice => $linea) {
                    $arrayDatos = [
                        ":precio" => $linea["precio"],
                        ":cantidad" => $linea["cantidad"],
                        ":descuento" => $linea["descuento"],
                        ":iva" => $linea["iva"],
                        ":cod_factura" => $cod_factura,
                        ":cod_articulo" => $linea["cod_articulo"],
                        ":cod_usu_gestion" => 1,
                        ":cod_albaran" => $linea["cod_albaran"],
                        ":num_linea_albaran" => $linea["num_linea_albaran"]
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

    public function editar($datosFactura){
        try {
            $sql = "UPDATE facturas SET descuento_factura = :descuento_factura WHERE cod_factura = :cod_factura;";
            $arrayDatos = [
                ":cod_factura" => $datosFactura["cod_factura"],
                ":descuento_factura" => $datosFactura["descuento_factura"]
            ];
            $sentencia = $this->conexion->prepare($sql);
            $sentencia->execute($arrayDatos);
            $resultado = ($sentencia->rowCount() <= 0) ? false : true ;
            return $resultado;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<bR>";
            return false;
        }
    }

    public function listarCompleto(): array
    {
        //Listamos las Facturas
        $sentencia = $this->conexion->prepare("SELECT * FROM facturas");
        $sentencia->execute();
        $facturas = $sentencia->fetchAll(PDO::FETCH_ASSOC);
        //Añadimos las líneas a cada Facturas.
        foreach ($facturas as $key => $factura) {
            $sentencia = $this->conexion->prepare("SELECT lineas_facturas.*, articulos.nombre FROM lineas_facturas 
            JOIN articulos ON lineas_facturas.cod_articulo = articulos.cod_articulo WHERE cod_factura = {$factura['cod_factura']}");
            $sentencia->execute();
            $linea = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $facturas[$key]["lineaFactura"] = $linea;
        }
        return $facturas;
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
        $sentencia = $this->conexion->prepare("SELECT DISTINCT facturas.* FROM facturas JOIN lineas_facturas ON facturas.cod_factura = lineas_facturas.cod_factura JOIN articulos ON lineas_facturas.cod_articulo = articulos.cod_articulo WHERE $tabla.$campo LIKE :dato");
        $resultado = $sentencia->execute($arrayDatos);
        if (!$resultado) return [];
        $facturas = $sentencia->fetchAll(PDO::FETCH_ASSOC);

        foreach ($facturas as $key => $factura) {
            $sentencia = $this->conexion->prepare("SELECT lineas_facturas.*, articulos.nombre 
            FROM lineas_facturas JOIN articulos ON 
            lineas_facturas.cod_articulo = articulos.cod_articulo WHERE cod_factura = {$factura['cod_factura']}");
            $sentencia->execute();
            $linea = $sentencia->fetchAll(PDO::FETCH_ASSOC);
            $facturas[$key]["lineaFactura"] = $linea;
        }

        return $facturas;
    }

    public function borrar($cod_factura): bool
    {
        try {
            //Borramos las líneas
            $sentencia = $this->conexion->prepare("DELETE FROM lineas_facturas WHERE cod_factura = $cod_factura");
            $sentencia->execute();
            $resultado =  ($sentencia->rowCount() <= 0) ? false : true;
            //La factura se borra de forma automática al no haber líneas
            return $resultado;
        } catch (Exception $e) {
            echo 'Excepción capturada: ',  $e->getMessage(), "<br>";
            return false;
        }
    }

    public function desfacturar($num_linea_factura)
    {
        $sql = "DELETE FROM lineas_facturas WHERE num_linea_factura = $num_linea_factura";
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
}
