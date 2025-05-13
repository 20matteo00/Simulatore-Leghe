<?php
include("conn.php");

if(isset($_GET['ID'])){
	
    $campionato = $_GET['ID'];
    risultati($campionato);
    calcola($campionato);
    header("Location: bacheca.php#card".$campionato."");

}else{
    $sql = "SELECT count(*) as total FROM campionato order by ID asc";
    $result = mysqli_query($conn, $sql);
    $data = mysqli_fetch_assoc($result);
    $num_rows = $data['total'];
    for($k=1; $k<=$num_rows; $k++){
        risultati($k);
        calcola($k);
    }
    header("Location: bacheca.php");
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

function risultati($campionato){
    include("conn.php");
    $sql = "DELETE FROM GCL";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM QCL";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM GEL";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM QEL";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM QECL";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitaqual";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitaelim";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM partitagirone";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM squadragirone";
    $result = mysqli_query($conn, $sql);
    $sql = "DELETE FROM statistichegirone";
    $result = mysqli_query($conn, $sql);
    // Ottieni tutte le squadre di questo campionato
    $sqlSquadre = "SELECT * FROM squadra WHERE ID_Campionato = " . $campionato;
    $resultSquadre = mysqli_query($conn, $sqlSquadre);

    $squadre = array();
    $forza = array();

    while ($rowSquadra = mysqli_fetch_assoc($resultSquadre)) {
        $idSquadra = $rowSquadra['ID'];
        $squadre[] = $rowSquadra['Nome'];
        $forza[] = $rowSquadra['Forza'];
    }

    // Ora hai un array $squadre con tutte le squadre del campionato

    // Loop attraverso le squadre per crearne le partite
    $numSquadre = count($squadre);

    for ($i = 0; $i < $numSquadre ; $i++) {
        for ($j = 0; $j < $numSquadre; $j++) {
            if ($i == $j) {
                continue;
            }else{
                $idSquadra1 = $i + 1;
                $idSquadra2 = $j + 1;
                // Ottieni le squadre
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
                $sqlInserimentoPartita = "INSERT INTO partita (ID_Squadra1, ID_Campionato1, GF1, ID_Squadra2, ID_Campionato2, GF2)
                        VALUES ($idSquadra1, $campionato, $golSquadra1, '$idSquadra2', $campionato, $golSquadra2)
                        ON DUPLICATE KEY UPDATE
                        GF1 = VALUES(GF1), GF2 = VALUES(GF2)";
                mysqli_query($conn, $sqlInserimentoPartita);
            }
        }
    }
}


// Ora hai un array $squadre con tutte le squadre del campionato e un elenco delle partite che coinvolgono quelle squadre.
function calcola($campionato){
    include("conn.php");

    // Ottieni tutte le squadre del campionato
    $sqlSquadre = "SELECT * FROM squadra WHERE ID_Campionato = " . $campionato;
    $resultSquadre = mysqli_query($conn, $sqlSquadre);
    $squadre = array();

    while ($rowSquadra = mysqli_fetch_assoc($resultSquadre)) {
        $squadre[] = $rowSquadra['ID'];
    }
    $c=0;
    foreach ($squadre as $squadra) {
        $statistiche = new statistiche();

        $sql = "SELECT * FROM partita WHERE (ID_Campionato1 = $campionato AND ID_Squadra1 = $squadra) OR (ID_Campionato2 = $campionato AND ID_Squadra2 = $squadra)";
        $partite = mysqli_query($conn, $sql);

        while ($partita = mysqli_fetch_assoc($partite)) {
            $gol1 = $partita['GF1'];
            $gol2 = $partita['GF2'];

            if ($partita['ID_Squadra1'] == $squadra) {
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
        $sqlUpdate = "UPDATE statistiche SET    PtTOT = $PtTOT, GTOT=$GTOT, VTOT=$VTOT, NTOT=$NTOT, PTOT=$PTOT, GFTOT=$GFTOT, GSTOT=$GSTOT, DRTOT=$DRTOT,
                                                PtC=$PtC, GC=$GC, VC = $statistiche->VC, NC = $statistiche->NC, PC = $statistiche->PC, GFC = $statistiche->GFC, GSC = $statistiche->GSC, DRC=$DRC,
                                                PtT=$PtT, GT=$GT, VT = $statistiche->VT, NT = $statistiche->NT, PT = $statistiche->PT, GFT = $statistiche->GFT, GST = $statistiche->GST, DRT=$DRT
                                                WHERE ID_Squadra = $squadra AND ID_Campionato = $campionato";
        mysqli_query($conn, $sqlUpdate);
    }
}


?>
