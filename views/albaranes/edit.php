<?php
$ruta = (file_exists("controllers/albaranesController.php")) ? "" : "../../";
require_once $ruta . "controllers/albaranesController.php";
$controlador = new albaranesController();

if (isset($_REQUEST["idPedido"])) {
  $id = $_REQUEST["idPedido"];
  $datosAlbaran = $controlador->buscar("albaranes", "cod_albaran", "igual", $id);
  $datosAlbaran = $datosAlbaran[0];
?>
  <form id="formulario">
    <h4>Albarán número: <?= $_REQUEST["idPedido"] ?></h4>
    <input type="hidden" name="cod_albaran" id="cod_albaran" value="<?= $id ?>">
    <div class="form-group">
      <table id="datos" class="table text-center">
        <thead class="table-dark">
          <tr>
            <td>Num linea</td>
            <td>Cod Albarán </td>
            <td>Código artículo </td>
            <td>Nombre artículo </td>
            <td>Num linea Pediod</td>
            <td>Cod Pedido</td>
            <td>Precio</td>
            <td>Cantidad</td>
            <td>IVA</td>
            <td>Descuento</td>
          </tr>
        </thead>

        <?php
        foreach ($datosAlbaran["lineaAlbaran"] as $dato) :
          echo "<tr data-num_linea_albaran='{$dato['num_linea_albaran']}'>
          <td >{$dato['num_linea_albaran']}</td>
          <td>{$dato['cod_albaran']}</td>
          <td>{$dato['cod_articulo']}</td>
          <td>{$dato['nombre']}</td>
          <td>{$dato['num_linea_pedido']}</td>  
          <td>{$dato['cod_pedido']}</td> 
          <td>{$dato['precio']}</td> 
          <td>{$dato['cantidad']}</td> 
          <td><input data-cantidadanterior='{$dato['iva']}' type='number' class='input_cantidad' id='iva' min='0' max='100' value='{$dato['iva']}'></td>
          <td><input data-cantidadanterior='{$dato['descuento']}' type='number' class='input_cantidad' id='descuento' min='0' max='100' value='{$dato['descuento']}'></td>
        </tr>";
        endforeach;
        ?>
      </table>
    </div>
    <button type="button" id="modificar" class="btn btn-primary">Modificar Albaran</button>

  </form>


<?php
}
?>