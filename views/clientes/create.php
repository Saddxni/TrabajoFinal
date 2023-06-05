<?php
require_once "assets/php/funciones.php";
$cadenaErrores = "";
$cadena = "";
$errores = [];
$datos = [];
$visibilidad = "invisible";
if (isset($_REQUEST["error"])) {
  $errores = ($_SESSION["errores"]) ?? [];
  $datos = ($_SESSION["datos"]) ?? [];
  $cadena = "Atención Se han producido Errores";
  $visibilidad = "visible";
}
//action="index.php?accion=guardar&evento=crear&tabla=piezas"
?>

<div class="alert alert-danger <?= $visibilidad ?>"><?= $cadena ?></div>
<form id="f_insercion">
  <div class="form-group">
    <label for="cif_dni">Dni </label>
    <input type="text" required class="form-control" pattern="[0-9]{8}[A-Za-z]{1}" minlength="9" maxlength="9" id="cif_dni" name="cif_dni" aria-describedby="cif_dni" placeholder="Introduce Dni" value="<?= $_SESSION["datos"]["cif_dni"] ?? "" ?>">
    <small id="pieza" class="form-text text-muted">El dni debe cumplir con el patrón 00000000A.</small>
    <div class="alert alert-danger invisible" role="alert" id="errorescif_dni"></div>
  </div>

  <div class="form-group">
    <label for="nick">Nick </label>
    <input type="text" required class="form-control" id="nick" name="nick" aria-describedby="nick" placeholder="Introduce nick" value="<?= $_SESSION["datos"]["nick"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" id="erroresnick"></div>
  </div>

  <div class="form-group">
    <label for="contraseña">Contraseña </label>
    <input type="password" required class="form-control" id="contraseña" name="contraseña" aria-describedby="contraseña" placeholder="Introduce contraseña" value="<?= $_SESSION["datos"]["contraseña"] ?? "" ?>">
    <small id="contra" class="form-text text-muted">Compartir tu contraseña lo hace menos seguro.</small>
    <div class="alert alert-danger invisible" role="alert" id="errorescontrasenya"></div>
  </div>

  <div class="form-group">
    <label for="razon_social">Razón social </label>
    <input type="text" class="form-control" id="razon_social" name="razon_social" placeholder="Introduce razón social" value="<?= $_SESSION["datos"]["razon_social"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" id="erroresrazon_social"></div>
  </div>
  
  <div class="form-group">
    <label for="domicilio_social">Domicilio social </label>
    <input type="text" min="0" step="any"  class="form-control" id="domicilio_social" name="domicilio_social" placeholder="Introduce el domicilio social" value="<?= $_SESSION["datos"]["domicilio_social"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" id="erroresdomicilio_social"></div>
  </div>

  <div class="form-group">
    <label for="ciudad">Ciudad</label>
    <input type="text" min="0" step="any" class="form-control" id="ciudad" name="ciudad" placeholder="Introduce la ciudad" value="<?= $_SESSION["datos"]["ciudad"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" id="erroresciudad"></div>
  </div>

  <div class="form-group">
    <label for="email">Email</label>
    <input type="email" min="0" step="any" class="form-control" id="email" name="email" placeholder="Introduce email" value="<?= $_SESSION["datos"]["email"] ?? "" ?>">
    <div class="alert alert-danger invisible" role="alert" id="erroresemail"></div>
  </div>

  <div class="form-group">
    <label for="telefono">Telefono</label>
    <input type="telefono" min="0" step="any" class="form-control" id="telefono" name="telefono" placeholder="Introduce telefono" value="<?= $_SESSION["datos"]["telefono"] ?? "" ?>">
    <small id="telef" class="form-text text-muted">El teléfono debe componerse del prefijo seguido del número. Ej: +34000000000</small>
    <div class="alert alert-danger invisible" role="alert" id="errorestelefono"></div>
    
  </div>
  <button type="button" id="insertar"  class="btn btn-primary">Guardar</button>
  <a class="btn btn-danger" href="index.php">Cancelar</a>
</form>





<?php
unset($_SESSION["datos"]);
unset($_SESSION["errores"]);
?>