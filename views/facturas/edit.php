<?php
$ruta = (file_exists("controllers/facturasController.php")) ? "" : "../../";
require_once $ruta . "controllers/facturasController.php";
$controlador = new facturasController();

if (isset($_REQUEST["cod_factura"])) {
  $cod_factura = $_REQUEST["cod_factura"];
  $datosFactura = $controlador->buscar("facturas", "cod_factura", "igual", $cod_factura);
  $datosFactura = $datosFactura[0];
?>
  <form id="formulario">
    <h4>Factura número: <?= $cod_factura ?></h4>
    <input type="hidden" name="cod_factura" id="cod_factura" value="<?= $cod_factura ?>">
    <input type="hidden" name="descuentoAntiguo" id="descuentoAntiguo" value="<?= $datosFactura["descuento_factura"] ?>">
    <div class="form-group">
      <table id="datos" class="table text-center">
        <thead class="table-dark">
          <tr>
            <th scope='col'>Código factura</th> 
            <th scope='col'>Código cliente</th>
            <th scope='col'>Fecha</th>
            <th scope='col'>Descuento Global </th>
            <th scope='col'>Concepto </th>
          </tr>
        </thead>
        <tr>
          <td><?= $datosFactura['cod_factura'] ?></td>
          <td><?= $datosFactura['cod_cliente'] ?></td>
          <td><?= $datosFactura['fecha'] ?></td>
          <td><input type="number" min="0" max="100" id="descuento_factura" name="descuento_factura" value="<?= $datosFactura['descuento_factura'] ?>"></td>
          <td><?= $datosFactura['concepto'] ?></td>
        </tr>
      </table>
    </div>
    <button type="button" id="modificar" class="btn btn-primary">Modificar Albaran</button>
  </form>


<?php
}
?>