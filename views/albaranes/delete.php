<?php
$ruta = (file_exists("controllers/albaranesController.php")) ? "" : "../../";
require_once $ruta . "controllers/albaranesController.php";
if (!isset($_REQUEST["arrayDatos"])) header("Location:{$ruta}index.php");
$controlador = new albaranesController();
$arrayDatosSerializado = $_REQUEST["arrayDatos"];
$arrayDatos = json_decode($arrayDatosSerializado, JSON_OBJECT_AS_ARRAY);
$controlador->borrarAlbaran($arrayDatos);
