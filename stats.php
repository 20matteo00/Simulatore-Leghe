<?php
    $sqlSquadra = "SELECT * FROM squadra";
    $resultSquadra = mysqli_query($conn, $sqlSquadra);

    while ($rowSquadra = mysqli_fetch_assoc($resultSquadra)) {
        $ID_Squadra = $rowSquadra['ID'];
        $ID_Campionato = $rowSquadra['ID_Campionato'];

        $sqlInsertStat = "INSERT IGNORE INTO `statistiche`(`ID_Squadra`, `ID_Campionato`,
        `PtTOT`, `GTOT`, `VTOT`, `NTOT`, `PTOT`, `GFTOT`, `GSTOT`, `DRTOT`, 
        `PtC`, `GC`, `VC`, `NC`, `PC`, `GFC`, `GSC`, `DRC`, 
        `PtT`, `GT`, `VT`, `NT`, `PT`, `GFT`, `GST`, `DRT`) 
        VALUES ('$ID_Squadra','$ID_Campionato','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0','0')";
        
        mysqli_query($conn, $sqlInsertStat);
    }
?>
