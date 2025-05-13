<head><?php include('../TAG/head.html'); ?></head>

<?php
ob_start();
include("../conn.php");
include("../TAG/navbar3.html");


$sql = "SELECT count(*) as total FROM partitaelim where coppa=0";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['total'] < 30) {
    header ("Location: eliminazione4.php");
}
$squadre = array();

$sql2 = "SELECT * FROM GCL, squadra WHERE GCL.ID_Squadra=squadra.ID AND GCL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
$result2 = mysqli_query($conn, $sql2);

while ($row2 = $result2->fetch_assoc()) {
    $squadre[] = $row2;
}

$sql3 = "SELECT * FROM QCL, squadra WHERE QCL.ID_Squadra=squadra.ID AND QCL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
$result3 = mysqli_query($conn, $sql3);


while ($row3 = $result3->fetch_assoc()) {
    $squadre[] = $row3;
}
?>


<div class="container">
    <h1 class="mt-5">Eliminazione Diretta Champions League</h1>
    <h3 class="text-center">Vincitore: <?php echo $squadre[0]['Nome']; ?></h3>
    

</div>