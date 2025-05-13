<?php 
include("../conn.php");
session_start();
if(!isset($_SESSION['ID'])) {
    header("Location: /AREA/Accesso/login.php");
}
// Elimina tutti i dati dalla tabella "partita"
$sql = "DELETE FROM partita";
$result = mysqli_query($conn, $sql);

// Elimina tutti i dati dalla tabella "statistiche"
$sql = "DELETE FROM statistiche";
$result = mysqli_query($conn, $sql);
/*
$sql = "DELETE FROM GCL";
$result = mysqli_query($conn, $sql);
$sql = "DELETE FROM QCL";
$result = mysqli_query($conn, $sql);
$sql = "DELETE FROM GEL";
$result = mysqli_query($conn, $sql);
$sql = "DELETE FROM QEL";
$result = mysqli_query($conn, $sql);
$sql = "DELETE FROM QECL";
$result = mysqli_query($conn, $sql);

$sql = "DELETE FROM partitaqual";
$result = mysqli_query($conn, $sql);*/

header("Location: ../bacheca.php");
?>
