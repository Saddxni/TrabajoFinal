<?php
require_once "assets/php/funciones.php";
require_once "controllers/articulosController.php";

//recoger datos
if (!isset($_REQUEST["id"])) header('location:index.php?accion=buscar&tabla=articulos');
$id = $_REQUEST["id"];
$controlador = new ArticulosController();
$articulo = $controlador->buscar("cod_articulo", "igual", $id);

$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($articulo == null) {
  $visibilidad = "visbility";
  $mensaje = "El articulo con id: {$id} no existe. Por favor vuelva a la pagina anterior";
  $clase = "alert alert-danger";
  $mostrarForm = false;
}
?>
<div class="<?= $clase ?>" <?= $visibilidad ?>> <?= $mensaje ?> </div>
<?php
unset($_SESSION["datos"]);
unset($_SESSION["errores"]);
if ($mostrarForm) {
?>
  <form id="f_actualizar" name="f_actualizar">
    <input type="hidden" id="idOriginal" name="idOriginal" value="<?= $id ?>">
    <div class="form-group">
      <label for="articulo">articulo </label>
      <input type="text" readonly required class="form-control" id="cod_articulo" name="cod_articulo" value="<?= $articulo[0]["cod_articulo"] ?>" aria-describedby="numarticulo" placeholder="Introduce articulo">
      <div id="cod_articuloerror" class="alert alert-danger invisible errores" role="alert"></div>


    </div>
    <div class="form-group">
      <label for="nombre">Nombre </label>
      <input type="text" class="form-control" id="nombre" name="nombre" value="<?= $articulo[0]["nombre"] ?>" placeholder="Introduce el Nombre de la articulo">
      <div id="nombreerror" class="alert alert-danger invisible errores" role="alert"></div>
    </div>

    <div class="form-group">
      <label for="descripcion">Descripcíon</label>
      <input type="text" class="form-control" id="descripcion" name="descripcion" value="<?= $articulo[0]["descripcion"] ?>" placeholder="Introduce el Precio">
      <div id="descripcionerror" class="alert alert-danger invisible errores" role="alert"></div>
    </div>

    <div class="form-group">
      <label for="precio">Precio de Venta </label>
      <input type="number" step="any" class="form-control" id="precio" name="precio" value="<?= $articulo[0]["precio"] ?>" placeholder="Introduce el Precio">
      <div id="precioerror" class="alert alert-danger invisible errores" role="alert"></div>
    </div>

    <div class="form-group">
    <label for="descuento">Descuento </label>
    <input type="number" class="form-control" step="any" id="descuento" name="descuento" aria-describedby="descuento" max="100" min="0" value="<?= $articulo[0]["descuento"] ?? "0" ?>" placeholder="Introduce descuento" value="<?= $_SESSION["datos"]["descuento"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresdescuento"></div>

  </div>

    <div class="form-group">
    <label for="iva">IVA </label>
    <input type="number" class="form-control" id="iva" name="iva" aria-describedby="iva" max="100" min="0" placeholder="Introduce iva" value="<?= $articulo[0]["iva"] ?? "0" ?>">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresiva"></div>
  </div>

    <div class="form-group">
      <label for="imagen">Imagen</label>
      <input type="file" class="form-control" id="imagen" name="imagen" aria-describedby="imagen" accept=".png,.jpg" placeholder="Selecciona imagen">
      <div class="alert alert-danger invisible" role="alert" class="error" id="erroresimagen"></div>
    </div>

    <div id="contenedorImagen" style="max-width: 200px; max-height: 200px; width: 100%; height: 100%;">
      <img src="views/articulos/img/<?= $id ?>.png" class="img-thumbnail" style="min-height:200px; max-height:200px" id="preview" alt="">
    </div>

    </br>

    <button type="button" id="b_guardar" name="b_guardar" class="btn btn-primary">Guardar</button>
    <a class="btn btn-danger " href="index.php?accion=buscar&tabla=articulos">Volver</a>

  </form>
<?php
} else {
?>
  <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
<?php
}
