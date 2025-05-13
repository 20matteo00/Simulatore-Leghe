<?php
include("../conn.php");
if(isset($_GET['simulaeuropa'])){
    $coppa=$_GET['coppa'];
    if(isset($_GET['s1']) && isset($_GET['s2']) && isset($_GET['c1']) && isset($_GET['c2'])){
        $s1=$_GET['s1'];
        $s2=$_GET['s2'];
		$c1=$_GET['c1'];
        $c2=$_GET['c2'];
        
        calcola($s1, $s2, $c1, $c2, $coppa);
        
    }else{
        $sql = "SELECT squadra.*
                FROM QCL, squadra
                WHERE QCL.ID_Campionato = squadra.ID_Campionato
                AND QCL.ID_Squadra = squadra.ID
                AND (QCL.Eliminato = 0 OR QCL.Eliminato > 2)
                ORDER BY squadra.Forza DESC, squadra.Nome ASC";
        $result = $conn->query($sql);
        $squadre = array();
        
        while ($row = $result->fetch_assoc()) {
            $squadre[] = $row;
        }
        $numSquadre = count($squadre);
        for ($i = 0; $i < $numSquadre / 2; $i++) {
            $s1 = $squadre[$i];
            $s2 = $squadre[$numSquadre - $i - 1];
            calcola($s1['ID'], $s2['ID'], $s1['ID_Campionato'], $s2['ID_Campionato'], $coppa);
        }

    }
}
header("Location: ottavi.php#" . $_GET['partita']);








function calcola($s1, $s2, $c1, $c2, $coppa) {
    include("../conn.php");
    $sql1 = "SELECT * from squadra WHERE squadra.ID = '$s1' AND squadra.ID_Campionato = '$c1'";
        $result1 = $conn->query($sql1);
        $row1 = $result1->fetch_assoc();
        $forza1 = $row1['Forza'];
        
        $sql2 = "SELECT * from squadra WHERE squadra.ID = '$s2' AND squadra.ID_Campionato = '$c2'";
        $result2 = $conn->query($sql2);
        $row2 = $result2->fetch_assoc();
        $forza2 = $row2['Forza'];


		
        $p1 = round($forza1 / ($forza1 + $forza2)*100);    
        $p2 = round($forza2 / ($forza1 + $forza2)*100);
        
        
        $p = rand(0, 100);
        if($p1>$p2 && $p1>$p){
            $golSquadra1 = rand(1, 5);
            $golSquadra2 = rand(0, $golSquadra1);                    
        } else if($p2>$p1 && $p2>$p){
            $golSquadra2 = rand(1, 5);
            $golSquadra1 = rand(0, $golSquadra2);
        }else{
            $golSquadra1 = rand(0, 5);
            $golSquadra2 = rand(0, 5);
        }           
		$ga1=$golSquadra1;
        $ga2=$golSquadra2;
        // Inserisci o aggiorna i risultati utilizzando INSERT ... ON DUPLICATE KEY UPDATE
        $sql1 = "INSERT INTO partitaqual (ID_Campionato1, ID_Squadra1, ID_Campionato2, ID_Squadra2, GF1, GF2, Coppa)
                 VALUES (" . $row1['ID_Campionato'] . ", " . $row1['ID'] . ", " . $row2['ID_Campionato'] . ", " . $row2['ID'] . ", $golSquadra1, $golSquadra2, $coppa)
                 ON DUPLICATE KEY UPDATE GF1 = $golSquadra1, GF2 = $golSquadra2";

        $result1 = $conn->query($sql1);

        
        
        $p = rand(0, 100);
        if($p1>$p2 && $p1>$p){
            $golSquadra1 = rand(1, 5);
            $golSquadra2 = rand(0, $golSquadra1);                    
        } else if($p2>$p1 && $p2>$p){
            $golSquadra2 = rand(1, 5);
            $golSquadra1 = rand(0, $golSquadra2);
        }else{
            $golSquadra1 = rand(0, 5);
            $golSquadra2 = rand(0, 5);
        }   
        $gr1=$golSquadra1;
        $gr2=$golSquadra2;
        
        if($ga1+$gr1==$ga2+$gr2){
            $r1 = rand(0, 5);
            $r2 = rand(0, 5);
            if($r1==$r2){
              $r= rand(0,1);
              if($r==0) $r1++;
              else $r2++;
            }
        }
        $golSquadra1=$gr1+$r1;
        $golSquadra2=$gr2+$r2;
        
        // Ripeti l'operazione per l'altra squadra
        $sql2 = "INSERT INTO partitaqual (ID_Campionato1, ID_Squadra1, ID_Campionato2, ID_Squadra2, GF1, GF2, Coppa)
                 VALUES (" . $row2['ID_Campionato'] . ", " . $row2['ID'] . ", " . $row1['ID_Campionato'] . ", " . $row1['ID'] . ", $golSquadra2, $golSquadra1, $coppa)
                 ON DUPLICATE KEY UPDATE GF1 = $golSquadra2, GF2 = $golSquadra1";

        $result2 = $conn->query($sql2);


        if(($ga1+$gr1+$r1)>($ga2+$gr2+$r2)){
            $sql = "UPDATE QCL SET Eliminato=3 WHERE ID_Squadra='$s2' AND ID_Campionato='$c2'";
            $sql1 = "UPDATE QCL SET Eliminato=0 WHERE ID_Squadra='$s1' AND ID_Campionato='$c1'";

        }elseif(($ga1+$gr1+$r1)<($ga2+$gr2+$r2)) {
            $sql = "UPDATE QCL SET Eliminato=3 WHERE ID_Squadra='$s1' AND ID_Campionato='$c1'";
            $sql1 = "UPDATE QCL SET Eliminato=0 WHERE ID_Squadra='$s2' AND ID_Campionato='$c2'";
        }
        $result = $conn->query($sql);
        $result1 = $conn->query($sql1);
        $conn->close();

}





?>
