<?php
function is_valid_dni(string $dni): bool
{
    $letter = substr($dni, -1);
    $numbers = substr($dni, 0, -1);
    $patron = "/^[[:digit:]]+$/";

    if (preg_match($patron, $numbers) && substr("TRWAGMYFPDXBNJZSQVHLCKE", $numbers % 23, 1) == $letter && strlen($letter) == 1 && strlen($numbers) == 8) {
        return true;
    }
    return false;
}

function imagenValida(array $extensiones_permitidas, array $imagen){
    //$tipoMIME = @mime_content_type($imagen['tmp_name']);
    //var_dump($tipoMIME);
    $extension = pathinfo($imagen["name"], PATHINFO_EXTENSION);
    if (in_array($extension, $extensiones_permitidas)) {
        return true;
    }
    return false;
}

function HayNulos(array $camposNoNulos, array $arrayDatos)
{
    $nulos = [];
    foreach ($camposNoNulos as $index => $campo) {
        if (!isset($arrayDatos[$campo]) || (is_string($arrayDatos[$campo]) && trim($arrayDatos[$campo]) === '') || (is_numeric($arrayDatos[$campo]) && $arrayDatos[$campo] === 0)) {
            $nulos[] = $campo;
        }
    }
    return $nulos;
}


function ExisteAficion($aficiones, $aficion)
{

    foreach ($aficiones as $index => $valor) {
        if ($valor == $aficion) return true;
    }
    return false;
}

//existeValor ($usuarios,'nick',$nick);
function existeValor(array $array, string $campo, mixed $valor): bool
{
    foreach ($array as $indice => $fila) {
        if ($fila[$campo] == $valor) {
            return true;
        }
    }
    return false;
}

function DibujarErrores($errores, $campo)
{
    $cadena = "";

    if (isset($errores[$campo])) {
        foreach ($errores[$campo] as $indice => $msgError) {
            $cadena .= "<br>{$msgError}";
        }
    }
    return $cadena;
}

function contieneSoloNumeros($cadena)
{
    // Comprobar si la cadena contiene sólo números
    if (ctype_digit($cadena)) {
        return false;
    }

    // Comprobar si la cadena es completamente numérica
    if (is_numeric($cadena)) {
        return false;
    }

    // La cadena no es completamente numérica y no contiene sólo números
    return true;
}

function contieneSoloLetras($cadena) {
    // Comprobar si la cadena contiene sólo letras y separadores
    if (preg_match('/^[\p{L}\s]+$/u', $cadena)) {
      return true;
    } else {
      return false;
    }
  }

function validarEmail($email)
{
    // Patrón de email válido
    $patron = '/^[^\s()<>@,;:\\"[\]ñáéíóúàèìòùâêîôûäëïöüâêîôûåæø]+(\.[^\s()<>@,;:\\"[\]ñáéíóúàèìòùâêîôûäëïöüâêîôûåæø]+)*@(([a-z0-9]([a-z0-9-]*[a-z0-9])?\.)+[a-z]{2,}|(\\d{1,3}\.){3}\\d{1,3})(:\d{1,5})?$/i';

    // Verificar si el email coincide con el patrón
    if (preg_match($patron, $email)) {
        return true;
    } else {
        return false;
    }
}

function validarTelefono($telefono)
{
    // Patrón de número de teléfono válido
    $patron = '/^\+(?:[0-9] ?){6,14}[0-9]$/';

    // Verificar si el teléfono coincide con el patrón
    if (preg_match($patron, $telefono)) {
        return true;
    } else {
        return false;
    }
}
