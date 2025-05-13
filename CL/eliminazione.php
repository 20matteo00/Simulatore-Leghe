<head><?php include('../TAG/head.html'); ?></head>

<?php
ob_start();
include("../conn.php");
include("../TAG/navbar3.html");
$gironi = ["A", "B", "C", "D", "E", "F", "G", "H"];
$first = array();
$second = array();

$sql = "SELECT count(*) as total FROM partitagirone where coppa=0";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['total'] != 96) {
    header ("Location: dettagli.php");
}

$sqltotal = "SELECT count(*) as total FROM GCL WHERE Eliminato=0";
$resulttotal = mysqli_query($conn, $sqltotal);
$rowtotal = $resulttotal->fetch_assoc();
$totalGCL = $rowtotal['total'];

$sqltotal = "SELECT count(*) as total FROM QCL WHERE Eliminato=0";
$resulttotal = mysqli_query($conn, $sqltotal);
$rowtotal = $resulttotal->fetch_assoc();
$totalQCL = $rowtotal['total'];

$total = 16;

$ids1 = array();
$idc1 = array();
$ids = array();
$idc = array();
$nome = array();

$sql2 = "SELECT * FROM GCL WHERE Eliminato=0";
$result2 = mysqli_query($conn, $sql2);


while ($row2 = $result2->fetch_assoc()) {
    $ids1[] = $row2['ID_Squadra'];
    $idc1[] = $row2['ID_Campionato'];
}

$sql3 = "SELECT * FROM QCL WHERE Eliminato=0";
$result3 = mysqli_query($conn, $sql3);


while ($row3 = $result3->fetch_assoc()) {
    $ids1[] = $row3['ID_Squadra'];
    $idc1[] = $row3['ID_Campionato'];
}



for ($i = 0; $i < count($gironi); $i++) {
    $sql = "SELECT squadra.* FROM statistichegirone, squadra WHERE squadra.ID=statistichegirone.ID_Squadra AND squadra.ID_Campionato=statistichegirone.ID_Campionato AND Coppa = 0 AND Girone = '$gironi[$i]' ORDER BY Pt DESC, DR DESC, GF DESC LIMIT 2";
    $result = mysqli_query($conn, $sql);
    // Ottieni la prima riga
    $firstRow = mysqli_fetch_assoc($result);
    $first[$i] = $firstRow;
    // Ottieni la seconda riga
    $secondRow = mysqli_fetch_assoc($result);
    $second[$i] = $secondRow;    

    $sql4 = "SELECT squadra.* FROM statistichegirone, squadra WHERE squadra.ID=statistichegirone.ID_Squadra AND squadra.ID_Campionato=statistichegirone.ID_Campionato AND Coppa = 0 AND Girone = '$gironi[$i]' ORDER BY Pt DESC, DR DESC, GF DESC LIMIT 2 OFFSET 2";
    $result4 = mysqli_query($conn, $sql4);

    while ($row4 = $result4->fetch_assoc()) {
        $idSquadra = $row4['ID'];
        $idCampionato = $row4['ID_Campionato'];

        $sql5 = "UPDATE GCL SET Eliminato=10 WHERE ID_Squadra='$idSquadra' AND ID_Campionato='$idCampionato'";
        $result5 = mysqli_query($conn, $sql5);

        $sql6 = "UPDATE QCL SET Eliminato=10 WHERE ID_Squadra='$idSquadra' AND ID_Campionato='$idCampionato'";
        $result6 = mysqli_query($conn, $sql6);
    }  

}



// Funzione di confronto per usort (ordine inverso)
function cmp($a, $b)
{
    return $b['Forza'] - $a['Forza'];
}

// Funzione di confronto per usort
function cmp2($a, $b)
{
    return $a['Forza'] - $b['Forza'];
}

// Ordina gli array per il campo 'Forza'
usort($first, 'cmp');
usort($second, 'cmp2');



?>


<div class="container">
    <h1 class="mt-5">Eliminazione Diretta Champions League</h1>
    <h3 class="text-center">Ottavi <a class="btn btn-primary" href="eliminazione2.php">Avanti</a></h3>
    <table class="table table-bordered ">
        <thead>
            <tr>
                <th>Partita</th>
                <th class="text-center">Andata</th>
                <th class="text-center">Ritorno</th>
                <th id="colsim" class="text-center">
                    <form class="mb-0" method="get" action="simulaeliminazione.php">
                        <input type="hidden" name="coppa" value="0">
                        <input type="submit" id="simulaeuropa" name="simulaeuropa" value="Simula Tutto">
                    </form>
                </th>

            </tr>
        </thead>
        <tbody>
            <?php
                    
                for ($i = 0; $i < count($first); $i++) {

                    $squadra_casa = $first[$i];
                    $squadra_trasferta = $second[$i];
                    
                    $sqlp1 = "SELECT * FROM partitaelim WHERE ID_Campionato1 = ".$squadra_casa['ID_Campionato']." AND ID_Squadra1 = ".$squadra_casa['ID']." AND ID_Campionato2 = ".$squadra_trasferta['ID_Campionato']." AND ID_Squadra2 = ".$squadra_trasferta['ID']."";
                    $sqlp2 = "SELECT * FROM partitaelim WHERE ID_Campionato1 = ".$squadra_trasferta['ID_Campionato']." AND ID_Squadra1 = ".$squadra_trasferta['ID']." AND ID_Campionato2 = ".$squadra_casa['ID_Campionato']." AND ID_Squadra2 = ".$squadra_casa['ID']."";
					                    
					$resultp1 = $conn->query($sqlp1);
                    $resultp2 = $conn->query($sqlp2);
                    
                    if ($resultp1&&$resultp2) {
                      $rowp1 = $resultp1->fetch_assoc();
                      $rowp2 = $resultp2->fetch_assoc();

                      
                    } 
                    
                              
                ?>
            <tr id="riga<?php echo $i; ?>">
                <td><?php echo $squadra_casa['Nome'] . ' vs ' . $squadra_trasferta['Nome']; ?></td>
                <td class="text-center"><?php echo $rowp1['GF1']."-".$rowp1['GF2']; ?></td>
                <td class="text-center"><?php echo $rowp2['GF2']."-".$rowp2['GF1']; ?></td>
                <td id="colsim" class="text-center">
                    <form class="mb-0" method="get" action="simulaeliminazione.php">
                        <input type="hidden" name="partita" value="riga<?php echo $i; ?>">
                        <input type="hidden" name="s1" value="<?php echo $squadra_casa['ID']; ?>">
                        <input type="hidden" name="s2" value="<?php echo $squadra_trasferta['ID']; ?>">
                        <input type="hidden" name="c1" value="<?php echo $squadra_casa['ID_Campionato']; ?>">
                        <input type="hidden" name="c2" value="<?php echo $squadra_trasferta['ID_Campionato']; ?>">
                        <input type="hidden" name="coppa" value="0">
                        <input type="submit" id="simulaeuropa" name="simulaeuropa" value="Simula">
                    </form>
                </td>

            </tr>
            <?php
                }
                ?>
        </tbody>

    </table>

</div>