<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    $_SESSION=[];
    unset ($_SESSION);
    session_destroy ();    
    header ("location:login.php");
    exit();
}


