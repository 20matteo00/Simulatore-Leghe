<?php
    include("../conn.php");
    $sql = "DELETE from partitaqual where Coppa = 0";
    $result = $conn->query($sql);
    $sql = "UPDATE GCL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "UPDATE QCL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "DELETE FROM squadragirone WHERE Coppa = 0";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM statistichegirone WHERE Coppa = 0";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitagirone WHERE Coppa = 0";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitaelim WHERE Coppa = 0";
	$result = mysqli_query($conn, $sql);
    header("Location: ../menueuropa.php");
?>
