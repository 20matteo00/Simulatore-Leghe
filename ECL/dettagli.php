<?php
include("../conn.php");
include("../TAG/head.html");
include("../TAG/navbar3.html");
include("navbar4.html");

include("stats.php");

$sql = "SELECT count(*) as total FROM squadragirone where Coppa=2";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if ($row['total'] == 32) {
echo '<div class="container-fluid">';
    for ($i = 0; $i < 8; $i++) {
    	$girone = a($i + 1);
        $sql2 = 'SELECT squadra.Nome, squadra.Forza, statistichegirone.*
                FROM squadra, statistichegirone
                WHERE squadra.ID_Campionato= statistichegirone.ID_Campionato and squadra.ID=statistichegirone.ID_Squadra
                and statistichegirone.Girone = "'.$girone.'" and statistichegirone.Coppa = 2
                ORDER BY statistichegirone.Pt desc, statistichegirone.DR desc, statistichegirone.GF desc, squadra.Forza desc';
        $result2 = mysqli_query($conn, $sql2);
        
        
        echo '<br><br><div class="row">';
        echo '<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12 mb-4">';
        echo '<div class="card" id="girone' . $girone . '">';
        echo '<div class="card-header text-center text-primary" id="cardhf">Girone ' . $girone . '</div>';
        echo '<div class="card-body table-responsive">';
        echo '<table class="table table-striped">';
        echo '<thead>';
        echo '<tr>';
        echo '<th>#</th>';
        echo '<th>Squadra</th>';
        echo '<th>Forza</th>';
        echo '<th>Pt</th>';
        echo '<th>V</th>';
        echo '<th>N</th>';
        echo '<th>P</th>';
        echo '<th>GF</th>';
        echo '<th>GS</th>';
        echo '<th>DR</th>';
        echo '</tr>';
        echo '</thead>';
        echo '<tbody>';
        $j = 1;
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $class = ($j == 1 || $j == 2) ? 'table-success' : 'table-danger';
            echo '<tr class="' . $class . '">';
            echo '<td>' . $j . '</td>';
            echo '<td>' . $row2['Nome'] . '</td>';
            echo '<td>' . $row2['Forza'] . '</td>';
            echo '<td>' . $row2['Pt'] . '</td>';
            echo '<td>' . $row2['V'] . '</td>';
            echo '<td>' . $row2['N'] . '</td>';
            echo '<td>' . $row2['P'] . '</td>';
            echo '<td>' . $row2['GF'] . '</td>';
            echo '<td>' . $row2['GS'] . '</td>';
            echo '<td>' . $row2['DR'] . '</td>';
            echo '</tr>';
            $j++;
        }
        echo '</tbody>';
        echo '</table>';
        echo '<br>';
        echo '<div class="legend-box" style="background-color: rgb(206,226,217);"></div>';
        echo '<span class="legend-text">Ammesso Alla Fase Finale</span>';
        echo '<br>';
        echo '<div class="legend-box" style="background-color: rgb(242,211,213);"></div>';
        echo '<span class="legend-text">Non Ammesso Alla Fase Finale</span>';
        echo '<br>';
        echo '</div>';
        echo '<div class="card-footer text-center" id="cardhf">';
        echo '<a class="link-underline link-underline-opacity-0" href="simulagironi.php?Coppa=2&Girone='.$girone.'">Simula</a>';
        echo '</div>';
        echo '</div>';
        echo '</div>';

        // Aggiungi qui la parte del tabellone degli incontri per il girone
        // Puoi aggiungere ulteriori card o elementi come sopra
        echo '<div class="col-lg-6 col-md-12 col-sm-12 col-xs-12">';
        echo '<div class="row mb-4">';
        $campionato = array();
        $idsquadra = array();
        // Aggiungi qui il codice per il tabellone degli incontri
        $sql3 = 'SELECT squadra.ID, LEFT(squadra.Nome, 4) AS NomeAbbreviato, squadra.Nome, squadra.ID_Campionato FROM squadra, squadragirone WHERE  squadra.ID = squadragirone.ID_Squadra and squadra.ID_Campionato = squadragirone.ID_Campionato   and squadragirone.Coppa = 2 and squadragirone.Girone = "' .$girone. '" ORDER BY NomeAbbreviato';
        $result3 = mysqli_query($conn, $sql3);
        while($row3 = mysqli_fetch_assoc($result3)){
            
            ?>
<div class="card p-0">
    <div class="card-header text-center text-primary" id="cardhf">Tabellone degli Incontri</div>
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th></th> <!-- Spazio vuoto nell'angolo in alto a sinistra -->
                    <?php
                        // Riporta il puntatore del risultato alla prima riga
                        mysqli_data_seek($result3, 0);
                        
                        // Stampa l'intestazione con i nomi delle squadre in alto
                        while ($row3 = mysqli_fetch_assoc($result3)) {
                            echo '<th>' . $row3['NomeAbbreviato'] . '</th>';                            
                            $campionato[] = $row3['ID_Campionato'];
                            $idsquadra[] = $row3['ID']; 
                        }   
                        ?>
                </tr>

            </thead>
            <tbody>
                <?php
                        
                        // Ora puoi stampare le righe con le squadre a sinistra
                        mysqli_data_seek($result3, 0); // Reimposta il puntatore del risultato alla prima riga
                        $j=0;
                        while ($row3 = mysqli_fetch_assoc($result3)) {
                            echo '<tr>';
                            echo '<th>' . $row3['Nome'] . '</th>'; // Squadra a sinistra
                            
                      

                            // Qui puoi aggiungere le celle per gli incontri tra squadre, ad esempio:
                            for ($k = 0; $k < mysqli_num_rows($result3); $k++) {
                                $sqlpartite = "SELECT partitagirone.GF1, partitagirone.GF2 FROM partitagirone where ID_Campionato1 = ".$row3["ID_Campionato"]." AND ID_Squadra1 = ".$row3["ID"]." AND ID_Campionato2 = ".$campionato[$k]." AND ID_Squadra2 = ".$idsquadra[$k]."";
                                $resultpartite = mysqli_query($conn, $sqlpartite);
                                $partita = mysqli_fetch_assoc($resultpartite);
                                if($k==$j) echo '<td> - </td>';
                                else echo '<td>' . $partita['GF1'] . ' - ' . $partita['GF2'] . '</td>';
                            }
                            echo '</tr>';
                            $j++;
                        }
                        ?>
            </tbody>
        </table>
    </div>
</div>
<?php
        }
        echo '</div>';
        echo '<div class="row mb-4">';
        $sql4 = "SELECT squadra.Nome, squadra.ID, squadra.ID_Campionato FROM squadra, squadragirone WHERE squadra.ID = squadragirone.ID_Squadra and squadra.ID_Campionato = squadragirone.ID_Campionato and squadragirone.Coppa = 2 and squadragirone.Girone = '" .$girone. "' ORDER BY squadra.Nome";
        $result4 = mysqli_query($conn, $sql4);
        $squadre = array();
        $id = array();
        $camp = array();
        while($row4 = mysqli_fetch_assoc($result4)){
            array_push($squadre, $row4['Nome']);
            $id[] = $row4['ID'];
            $camp[] = $row4['ID_Campionato'];
        }
        ?>
<div class="card p-0">
    <div class="card-header text-center text-primary" id="cardhf">Giornate</div>
    <div class="card-body table-responsive">
        <table class="table table-bordered">
            <thead>
                <tr class="text-center">
                    <th colspan="3">Giornata 1</th>
                    <th colspan="3">Giornata 4</th>
                </tr>
            </thead>
            <tbody>        
                <tr>
                    <td><?php echo $squadre[0] ?></td>
                    <td><?php echo query($id[0],$id[1], $camp[0],$camp[1]) ?></td>
                    <td><?php echo $squadre[1] ?></td>
                    <td><?php echo $squadre[1] ?></td>
                    <td><?php echo query($id[1],$id[0], $camp[1],$camp[0]) ?></td>
                    <td><?php echo $squadre[0] ?></td>
                </tr>
                <tr>
                    <td><?php echo $squadre[2] ?></td>
                    <td><?php echo query($id[2],$id[3], $camp[2],$camp[3]) ?></td>
                    <td><?php echo $squadre[3] ?></td>
                    <td><?php echo $squadre[3] ?></td>
                    <td><?php echo query($id[3],$id[2], $camp[3],$camp[2]) ?></td>
                    <td><?php echo $squadre[2] ?></td>
                </tr>
            </tbody>
            <thead>
                <tr class="text-center">
                    <th colspan="3">Giornata 2</th>
                    <th colspan="3">Giornata 5</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $squadre[3] ?></td>
                    <td><?php echo query($id[3],$id[0], $camp[3],$camp[0]) ?></td>
                    <td><?php echo $squadre[0] ?></td>
                    <td><?php echo $squadre[0] ?></td>
                    <td><?php echo query($id[0],$id[3], $camp[0],$camp[3]) ?></td>
                    <td><?php echo $squadre[3] ?></td>
                </tr>
                <tr>
                    <td><?php echo $squadre[1] ?></td>
                    <td><?php echo query($id[1],$id[2], $camp[1],$camp[2]) ?></td>
                    <td><?php echo $squadre[2] ?></td>
                    <td><?php echo $squadre[2] ?></td>
                    <td><?php echo query($id[2],$id[1], $camp[2],$camp[1]) ?></td>
                    <td><?php echo $squadre[1] ?></td>
                </tr>
            </tbody>
            <thead>
                <tr class="text-center">
                    <th colspan="3">Giornata 3</th>
                    <th colspan="3">Giornata 6</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><?php echo $squadre[0] ?></td>
                    <td><?php echo query($id[0],$id[2], $camp[0],$camp[2]) ?></td>
                    <td><?php echo $squadre[2] ?></td>
                    <td><?php echo $squadre[2] ?></td>
                    <td><?php echo query($id[2],$id[0], $camp[2],$camp[0]) ?></td>
                    <td><?php echo $squadre[0] ?></td>
                </tr>
                <tr>
                    <td><?php echo $squadre[1] ?></td>
                    <td><?php echo query($id[1],$id[3], $camp[1],$camp[3]) ?></td>
                    <td><?php echo $squadre[3] ?></td>
                    <td><?php echo $squadre[3] ?></td>
                    <td><?php echo query($id[3],$id[1], $camp[3],$camp[1]) ?></td>
                    <td><?php echo $squadre[1] ?></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>
<?php
        echo '</div>';
        echo '</div></div>';
    }
} else {
    header("Location: gironi.php");
}


function a($n){
	if($n==1) return "A";
    if($n==2) return "B";
    if($n==3) return "C";
    if($n==4) return "D";
    if($n==5) return "E";
    if($n==6) return "F";
    if($n==7) return "G";
    if($n==8) return "H";
}

function query($s1, $s2, $c1, $c2){
    include("../conn.php");
    $sqlpartite = "SELECT partitagirone.GF1, partitagirone.GF2 FROM partitagirone where ID_Campionato1 = ".$c1." AND ID_Squadra1 = ".$s1." AND ID_Campionato2 = ".$c2." AND ID_Squadra2 = ".$s2."";
    $resultpartite = mysqli_query($conn, $sqlpartite);
    $partita = mysqli_fetch_assoc($resultpartite);
    return $partita['GF1'] . ' - ' . $partita['GF2'];
}
?>
</div>
