<head><?php include('../TAG/head.html'); ?></head>

<?php
ob_start();
include("../conn.php");
include("../TAG/navbar3.html");


$sql = "SELECT count(*) as total FROM partitaelim where coppa=0";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['total'] < 16) {
    header ("Location: eliminazione.php");
}

$sqltotal = "SELECT count(*) as total FROM GCL WHERE Eliminato=0";
$resulttotal = mysqli_query($conn, $sqltotal);
$rowtotal = $resulttotal->fetch_assoc();
$totalGCL = $rowtotal['total'];

$sqltotal = "SELECT count(*) as total FROM QCL WHERE Eliminato=0";
$resulttotal = mysqli_query($conn, $sqltotal);
$rowtotal = $resulttotal->fetch_assoc();
$totalQCL = $rowtotal['total'];

$total = 8;

$squadre = array();

$sql2 = "SELECT * FROM GCL, squadra WHERE GCL.ID_Squadra=squadra.ID AND GCL.ID_Campionato=squadra.ID_Campionato AND (Eliminato=0 OR Eliminato>=25)";
$result2 = mysqli_query($conn, $sql2);


while ($row2 = $result2->fetch_assoc()) {
    $squadre[] = $row2;
}

$sql3 = "SELECT * FROM QCL, squadra WHERE QCL.ID_Squadra=squadra.ID AND QCL.ID_Campionato=squadra.ID_Campionato AND (Eliminato=0 OR Eliminato>=25)";
$result3 = mysqli_query($conn, $sql3);


while ($row3 = $result3->fetch_assoc()) {
    $squadre[] = $row3;
}

// Funzione di confronto per usort
function cmp($a, $b)
{
    return $b['Forza'] - $a['Forza'];
}

// Ordina gli array per il campo 'Forza'
usort($squadre, 'cmp');
?>


<div class="container">
    <h1 class="mt-5">Eliminazione Diretta Champions League</h1>
    <h3 class="text-center">Quarti <a class="btn btn-primary" href="eliminazione3.php">Avanti</a></h3>
    <table class="table table-bordered ">
        <thead>
            <tr>
                <th>Partita</th>
                <th class="text-center">Andata</th>
                <th class="text-center">Ritorno</th>
                <th id="colsim" class="text-center">
                    <form class="mb-0" method="get" action="simulaeliminazione2.php">
                        <input type="hidden" name="coppa" value="0">
                        <input type="submit" id="simulaeuropa" name="simulaeuropa" value="Simula Tutto">
                    </form>
                </th>

            </tr>
        </thead>
        <tbody>
            <?php
                    
                for ($i = 0; $i < count($squadre)/2; $i++) {

                    $squadra_casa = $squadre[$i];
                    $squadra_trasferta = $squadre[count($squadre)-$i-1];
                    
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
                    <form class="mb-0" method="get" action="simulaeliminazione2.php">
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