<?php
    include("../conn.php");
    $sql = "DELETE from partitaqual where Coppa = 1";
    $result = $conn->query($sql);
    $sql = "UPDATE GEL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "UPDATE QEL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "DELETE FROM squadragirone WHERE Coppa = 1";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM statistichegirone WHERE Coppa = 1";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitagirone WHERE Coppa = 1";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitaelim WHERE Coppa = 1";
	$result = mysqli_query($conn, $sql);
    header("Location: ../menueuropa.php");
?>
