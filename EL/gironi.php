<head><?php include('../TAG/head.html'); ?></head>

<?php
ob_start();
include("../conn.php");
include("../TAG/navbar3.html");
include("navbar4.html");

$sql = "SELECT count(*) as total FROM QEL where Eliminato=0";
$result = mysqli_query($conn, $sql);
$row = mysqli_fetch_assoc($result);
if ($row['total'] != 12) {
    header("Location: ventiquattresimi.php");
}


$sql = "SELECT squadra.*, campionato.Nome as nomecampionato FROM GEL, squadra, campionato WHERE squadra.ID = GEL.ID_squadra AND GEL.ID_Campionato = squadra.ID_Campionato AND squadra.ID_Campionato=campionato.ID ORDER BY squadra.Forza DESC, squadra.Nome ASC";
$result = $conn->query($sql);
$squadre = array();
while ($row = $result->fetch_assoc()) {
    $squadre[] = $row;
}

$sql = "SELECT squadra.*, campionato.Nome as nomecampionato FROM QEL, squadra, campionato WHERE QEL.Eliminato = 0 AND squadra.ID = QEL.ID_squadra AND QEL.ID_Campionato = squadra.ID_Campionato AND squadra.ID_Campionato=campionato.ID ORDER BY squadra.Forza DESC, squadra.Nome ASC";
$result = $conn->query($sql);
$squadre2 = array();
while ($row = $result->fetch_assoc()) {
    $squadre2[] = $row;
}


?>

<div class="container mt-5">
    <div class="row mb-5">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-success text-white text-center">Squadre Fascia 1</div>
                <div class="card-body">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Squadra</th>
                                <th>Campionato</th>
                                <th>Forza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 0; $i < 8; $i++) {
                                echo '<tr>';
                                echo '<td>' . $squadre[$i]['Nome'] . '</td>';
                                echo '<td>' . $squadre[$i]['nomecampionato'] . '</td>';
                                echo '<td>' . $squadre[$i]['Forza'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-primary text-white text-center">Squadre Fascia 2</div>
                <div class="card-body">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Squadra</th>
                                <th>Campionato</th>
                                <th>Forza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 8; $i < 16; $i++) {
                                echo '<tr>';
                                echo '<td>' . $squadre[$i]['Nome'] . '</td>';
                                echo '<td>' . $squadre[$i]['nomecampionato'] . '</td>';
                                echo '<td>' . $squadre[$i]['Forza'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-danger text-white text-center">Squadre Fascia 3</div>
                <div class="card-body">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Squadra</th>
                                <th>Campionato</th>
                                <th>Forza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($i = 16; $i < 20; $i++) {
                                echo '<tr>';
                                echo '<td>' . $squadre[$i]['Nome'] . '</td>';
                                echo '<td>' . $squadre[$i]['nomecampionato'] . '</td>';
                                echo '<td>' . $squadre[$i]['Forza'] . '</td>';
                                echo '</tr>';
                            }
                            for ($j = 0; $j < 4; $j++) {
                                echo '<tr>';
                                echo '<td>' . $squadre2[$j]['Nome'] . '</td>';
                                echo '<td>' . $squadre2[$j]['nomecampionato'] . '</td>';
                                echo '<td>' . $squadre2[$j]['Forza'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card">
                <div class="card-header bg-secondary text-white text-center">Squadre Fascia 4</div>
                <div class="card-body">
                    <table class="table table-striped mb-0">
                        <thead>
                            <tr>
                                <th>Squadra</th>
                                <th>Campionato</th>
                                <th>Forza</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for ($j = 4; $j< 12; $j++) {
                                echo '<tr>';
                                echo '<td>' . $squadre2[$j]['Nome'] . '</td>';
                                echo '<td>' . $squadre2[$j]['nomecampionato'] . '</td>';
                                echo '<td>' . $squadre2[$j]['Forza'] . '</td>';
                                echo '</tr>';
                            }
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Aggiungi il tuo pulsante per generare i gironi -->
<div class="text-center">
    <form method="post">
        <button type="submit" name="genera_gironi" class="btn btn-primary">Genera Gironi</button>
    </form>
</div>

<?php

// Verifica se il pulsante "Genera Gironi" è stato premuto
if (isset($_POST['genera_gironi'])) {
    // Includi il codice precedente
    $sql = "DELETE FROM squadragirone where Coppa=1";
    $result = $conn->query($sql);
    $sql = "DELETE FROM statistichegirone where Coppa=1";
    $result = $conn->query($sql);
    function shuffleArray(&$array) {
        for ($i = count($array) - 1; $i > 0; $i--) {
            $j = mt_rand(0, $i);
            list($array[$i], $array[$j]) = array($array[$j], $array[$i]);
        }
    }

    // Squadre divise per fascia
    $squadreFascia1 = array_slice($squadre, 0, 8);
    $squadreFascia2 = array_slice($squadre, 8, 8);
    $squadreFascia3_1 = array_slice($squadre, 16, 4,);
    $squadreFascia3_2 = array_slice($squadre2, 0, 4);
    $squadreFascia3 = array_merge($squadreFascia3_1,$squadreFascia3_2);
    $squadreFascia4 = array_slice($squadre2, 4, 8);

    // Mescola le squadre in ciascuna fascia
    shuffleArray($squadreFascia1);
    shuffleArray($squadreFascia2);
    shuffleArray($squadreFascia3);
    shuffleArray($squadreFascia4);

    $total = count($squadreFascia1) + count($squadreFascia2) + count($squadreFascia3) + count($squadreFascia4);
    if($total != 32){
        echo $total;
        header("Location: trentaduesimi.php");
        exit;
    }

    // Crea i gironi
    $gironi = array();
    for ($i = 0; $i < 8; $i++) {
        $girone = array();
        $girone[] = $squadreFascia1[$i];
        $girone[] = $squadreFascia2[$i];
        $girone[] = $squadreFascia3[$i];
        $girone[] = $squadreFascia4[$i];
        $gironi[] = $girone;
    }

    for ($i = 0; $i < 8; $i += 4) {

        for ($j = $i; $j < $i + 4; $j++) {
            
            foreach ($gironi[$j] as $squadra) {
                // Inserisci la squadra nella tabella squadragironi
                $squadraID = $squadra['ID']; // Sostituisci con il campo corretto che contiene l'ID della squadra
                $campionatoID = $squadra['ID_Campionato'];; // Sostituisci con il campo corretto che contiene l'ID del campionato
                $girone = a($j+1); // Calcola il girone                  
                $sqlInsert = "INSERT INTO squadragirone (ID_squadra, ID_Campionato, Coppa, Girone) VALUES ($squadraID, $campionatoID, 1, '$girone')";
                $resultInsert = $conn->query($sqlInsert);                
            }
        }
    }     
    header("Location: dettagli.php");  
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
?>
