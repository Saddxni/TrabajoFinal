<?php 
session_start(); 
require_once "config/db.php";
$usuario="";
$password="";
$visibilidad = "invisible";
$msg="";
$clase="";
$conexion=db::conexion();
if (isset($_POST["user"])){
  $usuario=$_POST["user"];
  $password=$_POST["password"];
    $sql="SELECT * FROM usuarios_gestion WHERE nick=:user and contraseña=:pass";
    $sentencia = $conexion->prepare($sql );
    $arrayDatos = [
        ":user" => $usuario,
        "pass"  => $password
      ];
    $resultado = $sentencia->execute($arrayDatos);
    if ($resultado && $sentencia->rowCount()>=1) {
      $_SESSION['usuario']=$usuario;
      header ("location:index.php");
      exit ();
    }
    //falló
    $_SESSION=[];
    $visibilidad="visible";
    $msg="Usuario o contraseña no Validos";
    $clase="alert_danger";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Log in</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="assets/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="assets/css/adminlte.min.css">
  <link rel="stylesheet" href="assets/plugins/sweetalert2-theme-bootstrap-4/bootstrap-4.min.css">
</head>
<body class="hold-transition login-page">


<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <b>Aplicacion Proveedores</b>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Logueate para entrar en la apliación</p>
      <div class="alert alert-danger <?= $visibilidad ?>"><?= $msg ?></div>

      <form action="<?= $_SERVER["PHP_SELF"]?>" method="post">
        <div class="input-group mb-3">
          <input type="text" name="user" class="form-control" value="<?=$usuario?>"placeholder="Usuario">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="password" class="form-control" value="<?=$password?>" placeholder="Password">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <button type="submit" class="btn btn-primary btn-block"> Comprobar</button>
          </div>
          <!-- /.col -->
        </div>
      </form>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->
<!-- jQuery -->
<script src="assets/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/js/adminlte.min.js"></script>
<script src="assets/plugins/sweetalert2/sweetalert2.min.js"></script>
<script>
  $(function() {
    var Toast = Swal.mixin({
      toast: true,
      position: 'top-end',
      showConfirmButton: false,
      timer: 3000
    });
  let params = new URLSearchParams(location.search);
  let q = params.get('q');
  if (q=="fin"){
   Toast.fire({
        icon: 'success',
        title: 'Aplicación Abandona con Éxito.'
      });
    }
  });
</script>
</body>
</html>
