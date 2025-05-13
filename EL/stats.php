<?php
	include ("../conn.php");
    $sqlSquadra = "SELECT * FROM squadragirone where Coppa=1";
    $resultSquadra = mysqli_query($conn, $sqlSquadra);

    while ($rowSquadra = mysqli_fetch_assoc($resultSquadra)) {
        $ID_Squadra = $rowSquadra['ID_Squadra'];
        $ID_Campionato = $rowSquadra['ID_Campionato'];
        $Girone = $rowSquadra['Girone'];

        $sqlInsertStat = "INSERT IGNORE INTO `statistichegirone`(`ID_Squadra`, `ID_Campionato`,
        `Pt`, `G`, `V`, `N`, `P`, `GF`, `GS`, `DR`, `Coppa`, `Girone`) 
        VALUES ('$ID_Squadra','$ID_Campionato','0','0','0','0','0','0','0','0','1', '$Girone')";
        
        mysqli_query($conn, $sqlInsertStat);
    }
?>
