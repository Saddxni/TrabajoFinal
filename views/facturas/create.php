<?php
//Hacemos un filtro que nos muestre los albaranes por cliente. Si el cliente no tiene albaranes no aparecerá en las opciones para crear una factura.
$ruta = (file_exists("controllers/clientesController.php")) ? "" : "../../";
require_once $ruta . "controllers/clientesController.php";
$controladorCliente = new ClientesController();
$clientesConAlbaran = $controladorCliente->listarClientesConAlbaran();
?>

<form id="formulario">
    <div class="form-group">
        <input type="hidden" name="campo" id="campo" value="cod_cliente">
        <select class="form-control" name="busqueda" id="busqueda">
            <?php
            foreach ($clientesConAlbaran as $cod_cliente => $nombre) {
                echo "<option value='{$nombre["cod_cliente"]}'>{$nombre["cod_cliente"]} - {$nombre["nick"]}</option>";
            }
            ?>
        </select>
    </div>
    </br>

    <h3>Albaranes</h3>
    <div id="datos"></div>

    <h3>En facturación</h3>
    <div id="factura">
        <table id='tablaFactura' class='table table text-center align-middle'>
            <thead class="table-dark">
                <tr>
                    <th></th>
                    <th>Código albarán</th>
                    <th>Generado de pedido</th>
                    <th>Código cliente</th>
                    <th>Fecha</th>
                    <th>Concepto</th>
                    <th>Estado</th>
                    <th></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
    <div class="form-group w-25">
        <label for="concepto">Concepto</label>
        <textarea class="form-control" id="concepto" name="concepto" rows="3"></textarea>
    </div>
    <div class="form-group">
        <label for="descuento">Descuento</label>
        </br>
        <input type="number" max="100" min="0" name="descuento" id="descuento" value="0">
    </div>
    
    <button type="button" id="insertar" class="btn btn-primary">Generar factura</button>
    <a class="btn btn-danger" href="index.php">Cancelar</a>
</form>