<?php

include("../conn.php");
if(isset($_GET['Girone'])&&isset($_GET['Coppa'])){


    $girone = $_GET['Girone'];
    $coppa = $_GET['Coppa'];
    risultati($girone, $coppa);
    calcola($girone, $coppa);
    header("Location: dettagli.php#girone".$girone."");
} else{
    $coppa = 1;
    $gironi = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H'];

    foreach ($gironi as $girone) {
        risultati($girone, $coppa);
        calcola($girone, $coppa);
    }
    
    header("Location: dettagli.php");
}


function risultati($girone, $coppa){
    include("../conn.php");

    $sql = "DELETE FROM partitaelim WHERE Coppa = ".$coppa;
	$result = mysqli_query($conn, $sql);

    $sql ="UPDATE GEL SET Eliminato = 0 WHERE Eliminato > 5 ";
    $result = mysqli_query($conn, $sql);
    $sql ="UPDATE QEL SET Eliminato = 0 WHERE Eliminato > 5 ";
    $result = mysqli_query($conn, $sql);

    // Ottieni tutte le squadre di questo campionato
    $sqlSquadre = "SELECT squadra.* FROM squadra, squadragirone WHERE squadra.ID_Campionato = squadragirone.ID_Campionato 
    and squadra.ID = squadragirone.ID_Squadra and Coppa = " . $coppa . " AND Girone = '" . $girone . "'  ORDER BY squadra.Forza desc, squadra.Nome asc";
    $resultSquadre = mysqli_query($conn, $sqlSquadre);

    $campionato = array();
    $idsquadra = array();
    $squadre = array();
    $forza = array();

    while ($rowSquadra = mysqli_fetch_assoc($resultSquadre)) {
        $campionato[] = $rowSquadra['ID_Campionato'];
        $idsquadra[] = $rowSquadra['ID'];
        $squadre[] = $rowSquadra['Nome'];
        $forza[] = $rowSquadra['Forza'];
    }
    // Loop attraverso le squadre per crearne le partite
    $numSquadre = count($squadre);
    for ($i = 0; $i < $numSquadre ; $i++) {
        for ($j = 0; $j < $numSquadre; $j++) {
            if ($i == $j) {
                continue;
            }else{
                
                $squadra1 = $squadre[$i];
                $squadra2 = $squadre[$j];
                $forza1 = $forza[$i];
                $forza2 = $forza[$j];
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
                // Ora puoi inserire questa coppia di squadre come partita nel database
                $sqlInserimentoPartita = "INSERT INTO partitagirone (ID_Campionato1, ID_Squadra1, ID_Campionato2, ID_Squadra2, GF1, GF2, Coppa, Girone)
                        VALUES ($campionato[$i], $idsquadra[$i], $campionato[$j], $idsquadra[$j], $golSquadra1, $golSquadra2, $coppa, '$girone')
                        ON DUPLICATE KEY UPDATE
                        GF1 = VALUES(GF1), GF2 = VALUES(GF2)";
                mysqli_query($conn, $sqlInserimentoPartita);
                if (!$resultSquadre) {
                    die('Errore nella query: ' . mysqli_error($conn) . '<br>Query: ' . $sqlInserimentoPartita);
                }
            }
        }
    }
}


// Ora hai un array $squadre con tutte le squadre del campionato e un elenco delle partite che coinvolgono quelle squadre.
function calcola($girone, $coppa){
    include("../conn.php");

    // Ottieni tutte le squadre del campionato
    $sqlSquadre = "SELECT squadra.* FROM squadra, squadragirone WHERE squadra.ID_Campionato = squadragirone.ID_Campionato 
    and squadra.ID = squadragirone.ID_Squadra and Coppa = " . $coppa . " AND Girone = '" . $girone . "'  ORDER BY squadra.Forza desc, squadra.Nome asc";
    
    $resultSquadre = mysqli_query($conn, $sqlSquadre);
    $squadre = array();
    $campionato = array();

    while ($rowSquadra = mysqli_fetch_assoc($resultSquadre)) {
        $squadre[] = $rowSquadra['ID'];
        $camp[] = $rowSquadra['ID_Campionato'];
    }
    for ($i = 0; $i < count($squadre); $i++) {
        $squadra = $squadre[$i];
        $campionato = $camp[$i];
        $statistiche = new statistiche();
        $sql = "SELECT * FROM partitagirone WHERE (ID_Campionato1 = $campionato AND ID_Squadra1 = $squadra) OR (ID_Campionato2 = $campionato AND ID_Squadra2 = $squadra) and Coppa = " . $coppa . " AND Girone = '" . $girone . "'";
        $partite = mysqli_query($conn, $sql);
        while ($partita = mysqli_fetch_assoc($partite)) {
            $gol1 = $partita['GF1'];
            $gol2 = $partita['GF2'];

            if ($partita['ID_Squadra1'] == $squadra && $partita["ID_Campionato1"] == $campionato) {
                if ($gol1 > $gol2) {
                    $statistiche->VC++;
                } elseif ($gol1 < $gol2) {
                    $statistiche->PC++;
                } else {
                    $statistiche->NC++;
                }
                $statistiche->GFC += $gol1;
                $statistiche->GSC += $gol2;
            } else {
                if ($gol2 > $gol1) {
                    $statistiche->VT++;
                } elseif ($gol2 < $gol1) {
                    $statistiche->PT++;
                } else {
                    $statistiche->NT++;
                }
                $statistiche->GFT += $gol2;
                $statistiche->GST += $gol1;
            }
        }
        $GC = $statistiche->VC + $statistiche->NC + $statistiche->PC;
        $GT = $statistiche->VT + $statistiche->NT + $statistiche->PT;
        $PtC = ($statistiche->VC * 3) + $statistiche->NC;
        $PtT = ($statistiche->VT * 3) + $statistiche->NT;
        $DRC = $statistiche->GFC - $statistiche->GSC;
        $DRT = $statistiche->GFT - $statistiche->GST;

        $GTOT = $GC + $GT;
        $PtTOT = $PtC + $PtT;
        $DRTOT = $DRC + $DRT;
        $GFTOT = $statistiche->GFC + $statistiche->GFT;
        $GSTOT = $statistiche->GSC + $statistiche->GST;
        $VTOT = $statistiche->VC + $statistiche->VT;
        $NTOT = $statistiche->NC + $statistiche->NT;
        $PTOT = $statistiche->PC + $statistiche->PT;

        // Aggiorna le statistiche nel database per la squadra corrente
        $sqlUpdate = "UPDATE statistichegirone  SET    Pt = $PtTOT, G=$GTOT, V=$VTOT, N=$NTOT, P=$PTOT, GF=$GFTOT, GS=$GSTOT, DR=$DRTOT
                                                WHERE ID_Squadra = $squadra AND ID_Campionato = $campionato AND Coppa = $coppa AND Girone = '$girone'";
        mysqli_query($conn, $sqlUpdate);
        
    }
}


class statistiche{
    public $VC=0;
    public $VT=0;
    public $NC=0;
    public $NT=0;
    public $PC=0;
    public $PT=0;
    public $GFC=0;
    public $GFT=0;
    public $GSC=0;
    public $GST=0;
}


?>
