<?php
require_once "assets/php/funciones.php";
require_once "controllers/clientesController.php";

//recoger datos
if (!isset($_REQUEST["id"])) header('location:index.php?accion=buscar&tabla=clientes');
$id = $_REQUEST["id"];
$controlador = new clientesController();
$cliente = $controlador->buscar("cod_cliente", "igual", $id);
$cliente = $cliente[0];
$visibilidad = "hidden";
$mensaje = "";
$clase = "alert alert-success";
$mostrarForm = true;
if ($cliente == null) {
  $visibilidad = "visbility";
  $mensaje = "El cliente con id: {$id} no existe. Por favor vuelva a la pagina anterior";
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
  <h4 style="font-weight:bold;">Cliente <?= $cliente["cod_cliente"] ?> - <?= $cliente["cif_dni"] ?></h4>
  <form id="f_actualizar" name="f_actualizar">
    <input type="hidden" id="cod_cliente" name="cod_cliente" value="<?= $id ?>">
    <input type="hidden" id="cif_dni" name="cif_dni"  value="<?= $cliente["cif_dni"] ?>">
    <input type="hidden" id="old_nick" name="old_nick" value="<?= $cliente["nick"] ?>">

    <div class="form-group">
      <label for="nick">Nick </label>
      <input type="text" required class="form-control" id="nick" name="nick" oldvalue="<?= $cliente["nick"] ?>"  aria-describedby="nick" placeholder="Introduce nick" value="<?= $cliente["nick"] ?>">
      <div class="alert alert-danger invisible" role="alert" id="erroresnick"></div>
    </div>
    <div class="form-group">
      <label for="contraseña">Contraseña </label>
      <input type="password" required class="form-control" id="contraseña" name="contraseña" oldvalue="<?= $cliente["contraseña"] ?>" aria-describedby="contraseña" placeholder="Introduce contraseña" value="<?= $cliente["contraseña"] ?>">
      <small id="contra" class="form-text text-muted">Compartir tu contraseña lo hace menos seguro.</small>
      <div class="alert alert-danger invisible" role="alert" id="errorescontrasenya"></div>
    </div>

    <div class="form-group">
      <label for="razon_social">Razón social </label>
      <input type="text" class="form-control" id="razon_social" name="razon_social" oldvalue="<?= $cliente["razon_social"] ?>" placeholder="Introduce razón social" value="<?= $cliente["razon_social"] ?>">
      <div class="alert alert-danger invisible" role="alert" id="erroresrazon_social"></div>
    </div>
    <div class="form-group">
      <label for="domicilio_social">Domicilio social </label>
      <input type="text" min="0" step="any" class="form-control" id="domicilio_social" name="domicilio_social" placeholder="Introduce el domicilio social" oldvalue ="<?= $cliente["domicilio_social"] ?>" value="<?= $cliente["domicilio_social"] ?>">
      <div class="alert alert-danger invisible" role="alert" id="erroresdomicilio_social"></div>
    </div>

    <div class="form-group">
      <label for="ciudad">Ciudad</label>
      <input type="text" min="0" step="any" class="form-control" id="ciudad" name="ciudad" placeholder="Introduce la ciudad" oldvalue="<?= $cliente["ciudad"] ?>"  value="<?= $cliente["ciudad"] ?>">
      <div class="alert alert-danger invisible" role="alert" id="erroresciudad"></div>
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input type="email" min="0" step="any" class="form-control" id="email" name="email" placeholder="Introduce email" oldvalue="<?= $cliente["email"] ?>" value="<?= $cliente["email"] ?>">
      <div class="alert alert-danger invisible" role="alert" id="erroresemail"></div>
    </div>

    <div class="form-group">
      <label for="telefono">Telefono</label>
      <input type="telefono" min="0" step="any" class="form-control" id="telefono" name="telefono" placeholder="Introduce telefono" oldvalue="<?= $cliente["telefono"] ?>" value="<?= $cliente["telefono"] ?>">
      <small id="telef" class="form-text text-muted">El teléfono debe componerse del prefijo seguido del número. Ej: +34000000000</small>
      <div class="alert alert-danger invisible" role="alert" id="errorestelefono"></div>

    </div>
    <button type="button" id="b_guardar" name="b_guardar" class="btn btn-primary">Guardar</button>
    <a class="btn btn-danger " href="index.php?accion=buscar&tabla=clientes">Volver</a>

  </form>
<?php
} else {
?>
  <a href="index.php" class="btn btn-primary">Volver a Inicio</a>
<?php
}
