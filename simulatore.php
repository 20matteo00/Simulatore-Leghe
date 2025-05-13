<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("TAG/head.html");?>
    <script src="JS/script.js"></script>
</head>

<body>
    <?php include("conn.php");
    include("TAG/navbar.html");
    include("TAG/navbar2.html");
    include("stats.php"); ?>

    <div class="container-fluid mt-5">
        <div class="row">
            <?php
            $sql = "SELECT * FROM campionato order by ID asc";
            $result = mysqli_query($conn, $sql);
            $num_rows = mysqli_num_rows($result);
            $i = 0;
            $k = 1;
            while ($row = mysqli_fetch_assoc($result)) {
                $partecipanti = $row['Partecipanti'];
                $id = $row['ID'];
                echo "<div class='col-lg-6 mb-3'>";
                echo "<div class='card' id='card".$k++."'>";
                echo "<div class='card-header text-center' id='cardhf'><h5 class='card-title'><a class='link-underline link-underline-opacity-0' href='dettagli.php?O=DESC&Tipo=PtTOT&ID=" . $row['ID'] . "'>" . $row['Nome'] . "</a></h5></div>";
                echo "<div class='card-body table-responsive ' id='cardbody-" . $row['ID'] . " collapse'>"; // Utilizza la classe 'collapse' di Bootstrap per nascondere il card-body
                echo "<table class='table mb-0'>";
                echo "<thead>";
                echo "<tr>";
                echo "<th scope='col'>#</th>";
                echo "<th scope='col'>Squadra</th>";
                echo "<th scope='col'>Forza</th>";
                echo "<th scope='col'>Pt</th>";
                echo "<th scope='col'>V</th>";
                echo "<th scope='col'>N</th>";
                echo "<th scope='col'>P</th>";
                echo "<th scope='col'>GF</th>";
                echo "<th scope='col'>GS</th>";
                echo "<th scope='col'>DR</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                $sql2 = "SELECT squadra.Nome, squadra.Forza, statistiche.*
                        FROM squadra, statistiche
                        WHERE squadra.ID_Campionato= statistiche.ID_Campionato and squadra.ID=statistiche.ID_Squadra
                        and squadra.ID_Campionato = " . $row['ID'] . "
                        ORDER BY statistiche.PtTOT desc, statistiche.DRTOT desc, statistiche.GFTOT desc, squadra.ID asc";


                $result2 = mysqli_query($conn, $sql2);
                $j = 1;
                while ($row2 = mysqli_fetch_assoc($result2)) {                    
                    if (($partecipanti == 20 && $j >= 17)||($partecipanti == 18 && $j >= 16)||($partecipanti == 16 && $j >= 14)||($partecipanti == 14 && $j >= 13)||
                    ($partecipanti == 12 && $j >= 11)||($partecipanti == 10 && $j == 10)||($partecipanti == 8 && $j == 8) ) {
                        echo "<tr class='table-danger'>";
                    }else if (($id <= 4 && $j <= 4)||(($id == 5 || $id == 6) && $j <= 2)||($id <= 10 && $j == 1)) {
                        echo "<tr class='table-success'>";
                    }else if (($id <= 6 && ($j==3||$j==4))||($id <=10&&($j>=2&&$j<=4))||($id <=13&&($j>=1&&$j<=2))||($id <=55&&($j==1))){
                    	echo "<tr class='table-warning'>";
                    } else if (($id<=10 && ($j==5||$j==6))) {
                        echo "<tr class='table-primary'>";
                    } else if ((($id>=11&&$id<=13)&&($j==3||$j==4))||($id<=55&&($j==2))){
                        echo "<tr class='table-info'>";
                    } else if (($id<=9&&($j==7||$j==8))||($id==10&&$j==7)||(($id>=11&&$id<=13)&&$j==5)||($id<=55&&$j==3)){
                        echo "<tr class='table-secondary'>";
                    } else{
                    	echo "<tr>";
                    }            
                    echo "<td scope='row'>" . $j . "</td>";
                    echo "<td>" . $row2['Nome'] . "</td>";
                    echo "<td>" . $row2['Forza'] . "</td>";
                    echo "<td>" . $row2['PtTOT'] . "</td>";
                    echo "<td>" . $row2['VTOT'] . "</td>";
                    echo "<td>" . $row2['NTOT'] . "</td>";
                    echo "<td>" . $row2['PTOT'] . "</td>";
                    echo "<td>" . $row2['GFTOT'] . "</td>";
                    echo "<td>" . $row2['GSTOT'] . "</td>";
                    echo "<td>" . $row2['DRTOT'] . "</td>";
                    echo "</tr>";
                    $j++;
                }
                echo "</tbody>";
                echo "</table>";
                echo '<br>
                <div class="legend-box" style="background-color: rgb(206,226,217);"></div>
                <span class="legend-text">Gironi Champions League</span><br>

                <div class="legend-box" style="background-color: rgb(254,241,201);"></div>
                <span class="legend-text">Qualificazioni Champions League</span><br>

                <div class="legend-box" style="background-color: rgb(203,222,253);"></div>
                <span class="legend-text">Gironi Europa League</span><br>

                <div class="legend-box" style="background-color: rgb(206,241,251);"></div>
                <span class="legend-text">Qualificazioni Europa League</span><br>

				<div class="legend-box" style="background-color: rgb(222,223,225);"></div>
                <span class="legend-text">Qualificazioni Conference League</span><br>
                
                <div class="legend-box" style="background-color: rgb(242,211,213);"></div>
                <span class="legend-text">Retrocessione</span>
                ';
                
                
                echo "</div>";
                echo "<div class='card-footer text-center' id='cardhf'><a class='link-underline link-underline-opacity-0' href='simula.php?ID=" . $row['ID'] . "'>Simula</a></div>";
                echo "</div>";
                echo "</div>";
                if ($num_rows > 3 && ($i + 1) % 2 == 0) {
                    echo "</div>";
                    echo "<div class='row mt-5'>";
                }
                $i++;
            }
            ?>
        </div>
    </div>
</body>

</html>
