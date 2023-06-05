<?php
$ruta = (file_exists("controllers/pedidosController.php")) ? "" : "../../";
require_once $ruta . "controllers/pedidosController.php";
$controlador = new pedidosController();

$ruta = (file_exists("controllers/articulosController.php")) ? "" : "../../";
require_once $ruta . "controllers/articulosController.php";
$controladorArticulos = new articulosController();

if (!isset($_REQUEST["idPedido"])) header("Location: index.php");

  $id = $_REQUEST["idPedido"];
  $datosPedido = $controlador->buscar("pedidos", "cod_pedido", "igual", $id);
  if($datosPedido == []) header("Location: index.php");
  $datosPedido = $datosPedido[0];
  $arrayArticulos = $controladorArticulos->listar();
?>
  <form id="formulario">
    <h4>Pedido número: <?= $_REQUEST["idPedido"] ?></h4>
    <input type="hidden" name="cod_pedido" id="cod_pedido" value="<?= $id ?>">
    <div class="form-group">
      <table id="datos" class="table text-center">
        <thead class="table-dark">
          <tr>
            <td>Num linea</td>
            <td>Código pedido </td>
            <td>Código artículo </td>
            <td>Nombre artículo </td>
            <td>Precio</td>
            <td>Cantidad</td>
            <td>Cantidad en Alb</td>
            <td>Estado</td>
          </tr>
        </thead>

        <?php
        foreach ($datosPedido["pedidos"] as $dato) :
          $disabled = "";
          if ($dato["cantidad"] == $dato["cantidadenalbaran"]) {
            $disabled = "disabled";
          }
          echo "<tr data-num_linea='{$dato['num_linea_pedido']}'>
          <td>{$dato['num_linea_pedido']}</td>
          <td>{$dato['cod_pedido']}</td>
          <td>{$dato['cod_articulo']}</td>
          <td>{$dato['nombre']}</td>
          <td>{$dato['precio']}</td>  
          <td><input $disabled data-cantidadAnterior='{$dato['cantidad']}' type='number' class='input_cantidad' id='cantidad' min='{$dato['cantidadenalbaran']}' value='{$dato['cantidad']}'></td>
          <td id='cantidadenalbaran" . $dato["num_linea_pedido"] . "'>{$dato['cantidadenalbaran']}</td>
          <td>{$dato['estado']}</td>
        </tr>";
        endforeach;
        ?>
      </table>
    </div>
    <button type="button" id="modificar" class="btn btn-primary">Modificar Albaran</button>
  </form>