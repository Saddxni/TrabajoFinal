<?php
require_once "assets/php/funciones.php";
/*$cadenaErrores = "";
$cadena = "";
$errores = [];
$datos = [];
$visibilidad = "invisible";
if (isset($_SESSION["errores"])) {
  $errores = ($_SESSION["errores"]) ?? [];
  $datos = ($_SESSION["datos"]) ?? [];
  $cadena = "Atención Se han producido Errores";
  $visibilidad = "visible";
}*/
?>


<form id="f_insercion" enctype="multipart/form-data">
  <div class="form-group">
    <label for="nombre">Nombre </label>
    <input type="text" class="form-control" id="nombre" name="nombre" aria-describedby="nombre" placeholder="Introduce nombre" value="<?= $_SESSION["datos"]["nombre"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresnombre"></div>
  </div>

  <div class="form-group">
    <label for="descripcion">Descripción </label>
    <input type="text" class="form-control" id="descripcion" name="descripcion" aria-describedby="descripcion" placeholder="Introduce descripcion" value="<?= $_SESSION["datos"]["descripcion"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresdescripcion"></div>

  </div>

  <div class="form-group">
    <label for="precio">Precio </label>
    <input type="number" class="form-control" id="precio" step="any" name="precio" aria-describedby="precio" placeholder="Introduce precio" min="0" value="<?= $_SESSION["datos"]["precio"] ?? "0" ?>">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresprecio"></div>

  </div>

  <div class="form-group">
    <label for="descuento">Descuento </label>
    <input type="number" class="form-control" step="any" id="descuento" name="descuento" aria-describedby="descuento" max="100" min="0" value="<?= $_SESSION["datos"]["descuneto"] ?? "0" ?>" placeholder="Introduce descuento" value="<?= $_SESSION["datos"]["descuento"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresdescuento"></div>

  </div>

  <div class="form-group">
    <label for="iva">IVA </label>
    <input type="number" class="form-control" id="iva" name="iva" aria-describedby="iva" max="100" min="0" placeholder="Introduce iva" value="<?= $_SESSION["datos"]["iva"] ?? "0" ?>">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresiva"></div>
  </div>

  <div class="form-group">
    <label for="imagen">Imagen</label>
    <input type="file" class="form-control" id="imagen" name="imagen" aria-describedby="imagen" accept=".png,.jpg" placeholder="Selecciona imagen">
    <div class="alert alert-danger invisible" role="alert" class="error" id="erroresimagen"></div>
  </div>

  <div id="contenedorImagen" style="max-width: 200px; max-height: 200px; width: 100%; height: 100%;" hidden>
    <img class="img-thumbnail" style="min-height:200px; max-height:200px" id="preview" alt="">
  </div>

  </br>

  <button type="button" id="insertar" class="btn btn-primary">Guardar</button>
  <a class="btn btn-danger" href="index.php">Cancelar</a>


</form>





<?php
unset($_SESSION["datos"]);
unset($_SESSION["errores"]);
?>