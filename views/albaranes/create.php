<?php
$ruta = (file_exists("controllers/articulosController.php")) ? "" : "../../";
require_once $ruta . "controllers/articulosController.php";
$controladorArticulo = new ArticulosController;
$arrayArticulos = $controladorArticulo->listar();

$ruta = (file_exists("controllers/pedidosController.php")) ? "" : "../../";
require_once $ruta . "controllers/pedidosController.php";
$controladorPedido = new PedidosController();

if (isset($_REQUEST["idPedido"])) {
?>

    <form id="formulario">
        <h4>Pedido número: <?= $_REQUEST["idPedido"] ?></h4>

        <div class="form-group" id="linealbaran">
            <table id='datos' class='table table text-center'>
                <thead class='table-dark'>
                    <tr>
                        <th scope='col'>Línea pedido</th>
                        <th scope='col'>Código articulo</th>
                        <th scope='col'>Nombre</th>
                        <th scope='col'>Precio</th>
                        <th scope='col'>Cantidad</th>
                        <th scope='col'>Cantidad en Albarán</th>
                        <th scope='col'>Descuento</th>
                        <th scope='col'>IVA</th>
                        <th scope='col'>Añadir albarán</th>
                    </tr>
                </thead>

                <?php
                $pedido =  $controladorPedido->buscar("lineas_pedidos","cod_pedido", "igual a", $_REQUEST["idPedido"]);
                
                foreach ($pedido[0]["pedidos"] as $linea) :
                    $articulo = $controladorArticulo->buscar("cod_articulo", "igual a", $linea["cod_articulo"]);

                    //Calculamos el restando la cantidad que tenemos en albarán con la cantidad total del pedido
                    $maximo = $linea["cantidad"] - $linea["cantidadenalbaran"];
                    //Si la línea está completamente en albarán la deshabilitamos 
                    $deshabilitado = "";
                    if($maximo == 0){
                        $deshabilitado = "disabled";
                    }
                ?>
                    <tr class='<?= $linea["cod_articulo"] ?>'>
                        <td><?= $linea["num_linea_pedido"] ?></td>
                        <td><?= $linea["cod_articulo"] ?></td>
                        <td><?= $articulo[0]["nombre"] ?></td>
                        <td><?= $linea["precio"] ?></td>
                        <td><?= $linea["cantidad"] ?></td>
                        <td><?= $linea["cantidadenalbaran"] ?></td>
                        <td><?= $articulo[0]["descuento"] ?></td>
                        <td><?= $articulo[0]["iva"] ?></td>
                        <td><input type="number" max="<?= $maximo ?>" min="0" <?=$deshabilitado?> id="cantidad" value="0" class="input_cantidad"></td>
                    </tr>

                <?php endforeach ?>
            </table>

            <div class="form-group w-25">
                <label for="concepto">Concepto</label>
                <textarea class="form-control" id="concepto" rows="3"></textarea>
            </div>
            <button type="button" id="insertar" class="btn btn-primary">Generar Albaran</button>
            <a class="btn btn-danger" href="index.php">Cancelar</a>
        </div>

        <input type="hidden" id="cod_pedido" value='<?= $_REQUEST["idPedido"] ?>'>
        <input type="hidden" id="cod_cliente" value='<?= $pedido[0]["cod_cliente"] ?>'>


    </form>


<?php
} else {
    header("location: index.html");
}
?>