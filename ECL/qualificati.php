<head><?php include('../TAG/head.html'); ?></head>

<?php
include("../conn.php");
include("../TAG/navbar3.html");
include("navbar4.html");
$sql = "SELECT count(*) as total FROM partitaqual where coppa=2";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['total']  < 64) {
    header ("Location: trentaduesimi.php");
}elseif ($row['total']  == 64) {
    header ("Location: gironi.php");
}else{
    $sql2 = "DELETE from partitaqual where coppa=2";
    $result2 = $conn->query($sql2);
    $sql3 = "UPDATE QECL SET Eliminato = 0";
    $result3 = $conn->query($sql3);
    header ("Location: trentaduesimi.php");
}
