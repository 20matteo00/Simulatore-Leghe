<head><?php include('../TAG/head.html'); ?></head>

<?php
ob_start();
include("../conn.php");
include("../TAG/navbar3.html");


$sql = "SELECT count(*) as total FROM partitaelim where coppa=2";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['total'] < 30) {
    header ("Location: eliminazione4.php");
}
$squadre = array();

$sql3 = "SELECT * FROM QECL, squadra WHERE QECL.ID_Squadra=squadra.ID AND QECL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
$result3 = mysqli_query($conn, $sql3);


while ($row3 = $result3->fetch_assoc()) {
    $squadre[] = $row3;
}
?>


<div class="container">
    <h1 class="mt-5">Eliminazione Diretta Conference League</h1>
    <h3 class="text-center">Vincitore: <?php echo $squadre[0]['Nome']; ?></h3>
    

</div>