<?php
    include("conn.php");
    $sql = "DELETE from partitaqual";
    $result = $conn->query($sql);
    $sql = "UPDATE GCL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "UPDATE GEL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "UPDATE QCL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "UPDATE QEL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "UPDATE QECL SET Eliminato = 0";
    $result = $conn->query($sql);
    $sql = "DELETE FROM squadragirone";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM statistichegirone";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitagirone";
	$result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitaelim";
	$result = mysqli_query($conn, $sql);
    header("Location: menueuropa.php");
?>
