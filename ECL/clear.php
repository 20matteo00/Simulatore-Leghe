<?php
    include("../conn.php");
    $sql = "DELETE from partitaqual where Coppa = 2";
    $result = $conn->query($sql);
    $sql = "UPDATE QECL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "DELETE FROM squadragirone WHERE Coppa = 2";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM statistichegirone WHERE Coppa = 2";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitagirone WHERE Coppa = 2";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitaelim WHERE Coppa = 2";
	$result = mysqli_query($conn, $sql);
    header("Location: ../menueuropa.php");
?>
