<?php
$ruta = (file_exists("controllers/clientesController.php")) ? "" : "../../";
require_once $ruta . "controllers/clientesController.php";
$controladorCliente = new ClientesController;
$arrayClientes = $controladorCliente->listar();

$ruta = (file_exists("controllers/articulosController.php")) ? "" : "../../";
require_once $ruta . "controllers/articulosController.php";
$controladorArticulo = new ArticulosController;
$arrayArticulos = $controladorArticulo->listar();
?>
<form id="f_insercion">
    <div class="form-group">
        <label for="cliente">Cliente </label>
        <select class="form-control" name="cliente" id="cliente">
            <?php
            foreach ($arrayClientes as $indice => $cliente) {
                if ($cliente["disponibilidad"] == "Disponible") {
                    echo "<option value='{$cliente['cod_cliente']}'>{$cliente['cod_cliente']} - {$cliente['nick']}</option>";
                }
            }
            ?>

        </select>
        <div class="alert alert-danger invisible" role="alert" id="errorescliente"></div>
    </div>

    <div class="form-group">
        <label for="iva">Fecha pedido</label>
        <input type="date" class="form-control" id="fecha" name="fecha" min="2000-01-01">
        <div class="alert alert-danger invisible" role="alert" id="erroresfecha"></div>
    </div>
    <!-- <div class="alert alert-danger invisible" role="alert" id="errorescantidad"></div> -->
    <div class="form-group" id="linped">
        <label>Líneas pedido</label>
        <table id='datos' class='table table'>
            <thead class='table-dark'>
                <tr>
                    <th scope='col'>Código articulo</th>
                    <th scope='col'>Nombre</th>
                    <th scope='col'>Precio</th>
                    <th scope='col'>Cantidad</th>
                    <th scope='col'>Descuento</th>
                    <th scope='col'>IVA</th>

                </tr>
            </thead>
            <tbody>
                <?php
                foreach ($arrayArticulos as $articulo) :
                    if ($articulo["disponibilidad"] == "Disponible") {
                ?>
                        <tr>
                            <td><?= $articulo['cod_articulo'] ?></td>
                            <td><?= $articulo['nombre'] ?></td>
                            <td><?= $articulo['precio'] ?></td>
                            <td><input type="number" value="0" min="0" id="cantidad" class="input_cantidad"></td>
                            <td><?= $articulo['descuento'] ?></td>
                            <td><?= $articulo['iva'] ?>%</td>
                        </tr>
                <?php
                    }
                endforeach;
                ?>
            </tbody>
        </table>
        <button type="button" id="insertar" class="btn btn-primary">Guardar</button>
        <a class="btn btn-danger" href="index.php">Cancelar</a>
    </div>




</form>