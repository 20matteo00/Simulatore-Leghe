<head><?php include('../TAG/head.html'); ?></head>

<?php
include("../conn.php");
include("../TAG/navbar3.html");
include("navbar4.html");

$sql = "SELECT count(*) as total FROM partitaqual where coppa=0";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
if ($row['total'] < 96) {
    header ("Location: sedicesimi.php");
}

// Esegui una query per ottenere le squadre qualificate dalla tabella QCL
$sql1 = "SELECT squadra.*
    FROM QCL, squadra
    WHERE QCL.ID_Campionato = squadra.ID_Campionato
    AND QCL.ID_Squadra = squadra.ID
    AND QCL.Eliminato!=1
    AND QCL.Eliminato!=2
    ORDER BY squadra.Forza DESC, squadra.Nome ASC";

$result1 = $conn->query($sql1);

// Creare un array per tenere traccia delle squadre qualificate
$squadre_qualificate = array();

while ($row1 = $result1->fetch_assoc()) {
    $squadre_qualificate[] = $row1;
}

// Calcolare il numero di squadre qualificate
$numero_squadre_cl = count($squadre_qualificate);

?>

<div class="container">
    <h1 class="mt-5">Qualificazioni Champions League</h1>
    <h3 class="text-center">Ottavi <a class="btn btn-primary" href="qualificati.php">Avanti</a></h3>
    <table class="table table-bordered ">
        <thead>
            <tr>
                <th>Partita</th>
                <th class="text-center">Andata</th>
                <th class="text-center">Ritorno</th>
                <th id="colsim" class="text-center">
                    <form class="mb-0" method="get" action="simulaottavi.php">
                        <input type="hidden" name="coppa" value="0">
                        <input type="submit" id="simulaeuropa" name="simulaeuropa" value="Simula Tutto">
                    </form>
                </th>

            </tr>
        </thead>
        <tbody>
            <?php

            $numero_accoppiamenti = $numero_squadre_cl / 2;
            for ($i = 0; $i < $numero_accoppiamenti; $i++) {

                $squadra_casa = $squadre_qualificate[$i];
                $squadra_trasferta = $squadre_qualificate[$numero_squadre_cl - $i - 1];

                $sqlp1 = "SELECT * FROM partitaqual WHERE ID_Campionato1 = " . $squadra_casa['ID_Campionato'] . " AND ID_Squadra1 = " . $squadra_casa['ID'] . " AND ID_Campionato2 = " . $squadra_trasferta['ID_Campionato'] . " AND ID_Squadra2 = " . $squadra_trasferta['ID'] . "";
                $sqlp2 = "SELECT * FROM partitaqual WHERE ID_Campionato1 = " . $squadra_trasferta['ID_Campionato'] . " AND ID_Squadra1 = " . $squadra_trasferta['ID'] . " AND ID_Campionato2 = " . $squadra_casa['ID_Campionato'] . " AND ID_Squadra2 = " . $squadra_casa['ID'] . "";

                echo $squadra_case['ID_Campionato'];

                $resultp1 = $conn->query($sqlp1);
                $resultp2 = $conn->query($sqlp2);

                if ($resultp1 && $resultp2) {
                    $rowp1 = $resultp1->fetch_assoc();
                    $rowp2 = $resultp2->fetch_assoc();
                }


            ?>
                <tr id="riga<?php echo $i; ?>">
                    <td><?php echo $squadra_casa['Nome'] . ' vs ' . $squadra_trasferta['Nome']; ?></td>
                    <td class="text-center"><?php echo $rowp1['GF1'] . "-" . $rowp1['GF2']; ?></td>
                    <td class="text-center"><?php echo $rowp2['GF2'] . "-" . $rowp2['GF1']; ?></td>
                    <td id="colsim" class="text-center">
                        <form class="mb-0" method="get" action="simulaottavi.php">
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
