<?php
include("conn.php");



if (isset($_GET['ID'])) {
    if (isset($_GET['Tipo'])){
        $tipo=$_GET['Tipo'];
    }
      
    if (isset($_GET['o']) && ($_GET['o'] === 'ASC' || $_GET['o'] === 'DESC')) {
    // Se il parametro 'o' è presente nell'URL e è 'ASC' o 'DESC', usalo, altrimenti, usa 'ASC' come predefinito.
        $o = $_GET['o'];
    } else {
        $o = 'DESC'; // Imposta 'DESC' come ordinamento predefinito.
    }

    // Inverti il valore di 'o' per il prossimo click.
    $nextO = ($o === 'ASC') ? 'DESC' : 'ASC';

	
    $campionato = $_GET['ID'];
    $sqlCampionato = "SELECT Nome FROM campionato WHERE ID = " . $campionato;
    $resultCampionato = mysqli_query($conn, $sqlCampionato);
    $nomeCampionato = mysqli_fetch_assoc($resultCampionato)['Nome'];

    $sql = "SELECT * FROM partita WHERE ID_Campionato1 = " . $campionato . " AND ID_Campionato2 = " . $campionato;
    $result = mysqli_query($conn, $sql);
    $partite = array();

    while ($row = mysqli_fetch_assoc($result)) {
        $partite[] = $row;
    }

    // Ottieni i nomi delle squadre con i primi 5 caratteri
    $squadre = array();
    $sqlSquadre = "SELECT ID, LEFT(Nome, 4) AS NomeAbbreviato, Nome FROM squadra WHERE ID_Campionato = " . $campionato . " ORDER BY NomeAbbreviato";
    $resultSquadre = mysqli_query($conn, $sqlSquadre);
    while ($rowSquadra = mysqli_fetch_assoc($resultSquadre)) {
        $squadre[$rowSquadra['ID']] = $rowSquadra['NomeAbbreviato'];
        $squadren[$rowSquadra['ID']] = $rowSquadra['Nome'];
    }

    // Ottieni i nomi completi delle squadre per le statistiche
    $nomiSquadre = array();
    $sqlNomiSquadre = "SELECT ID, Nome FROM squadra WHERE ID_Campionato = " . $campionato;
    $resultNomiSquadre = mysqli_query($conn, $sqlNomiSquadre);
    while ($rowNomeSquadra = mysqli_fetch_assoc($resultNomiSquadre)) {
        $nomiSquadre[$rowNomeSquadra['ID']] = $rowNomeSquadra['Nome'];
    }

    $sql2 = "SELECT * FROM statistiche WHERE ID_Campionato = " . $campionato . " ORDER BY " . $tipo . " " . $o . ", PtTOT DESC, DRTOT DESC, GFTOT DESC, ID_Squadra DESC";
    $result2 = mysqli_query($conn, $sql2);
    $statistiche = array();

} else {
    header("Location: index.php");
}
?>
<head><?php include ("TAG/head.html");?></head>
<?php include("TAG/navbar.html");?>
<h1 class="text-center"><?php echo $nomeCampionato; ?></h1><br>
<h3 class="text-center">Classifica Finale</h3>
<table id="table">
    <tr>
        <th id="thdett" colspan="2" >Info</th>
        <th id="thdett" colspan="8" >Totale</th>
        <th id="thdett" colspan="8" >Casa</th>
        <th id="thdett" colspan="8" >Trasferta</th>
    </tr>
    <tr>
        <th class="classifica">#</th>
        <th class="classifica">Team</th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=PtTOT&o=<?php echo $nextO; ?>">Pt</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GTOT&o=<?php echo $nextO; ?>">G</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=VTOT&o=<?php echo $nextO; ?>">V</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=NTOT&o=<?php echo $nextO; ?>">N</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=PTOT&o=<?php echo $nextO; ?>">P</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GFTOT&o=<?php echo $nextO; ?>">GF</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GSTOT&o=<?php echo $nextO; ?>">GS</a></th>
        <th class="classifica"><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=DRTOT&o=<?php echo $nextO; ?>">DR</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=PtC&o=<?php echo $nextO; ?>">PtC</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GC&o=<?php echo $nextO; ?>">GC</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=VC&o=<?php echo $nextO; ?>">VC</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=NC&o=<?php echo $nextO; ?>">NC</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=PC&o=<?php echo $nextO; ?>">PC</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GFC&o=<?php echo $nextO; ?>">GFC</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GSC&o=<?php echo $nextO; ?>">GSC</a></th>
        <th class="classifica"><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=DRC&o=<?php echo $nextO; ?>">DRC</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=PtT&o=<?php echo $nextO; ?>">PtT</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GT&o=<?php echo $nextO; ?>">GT</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=VT&o=<?php echo $nextO; ?>">VT</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=NT&o=<?php echo $nextO; ?>">NT</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=PT&o=<?php echo $nextO; ?>">PT</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GFT&o=<?php echo $nextO; ?>">GFT</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=GST&o=<?php echo $nextO; ?>">GST</a></th>
        <th><a href="dettagli.php?ID=<?php echo $campionato; ?>&Tipo=DRT&o=<?php echo $nextO; ?>">DRT</a></th>

    </tr>
    
        <?php
        $i = 1;
        while ($row2 = mysqli_fetch_assoc($result2)) {
            $statistiche[] = $row2;
            ?>
            <tr>
            <td class="classifica"><?php echo $i; ?></td>
            <td class="classifica"><?php echo $nomiSquadre[$row2['ID_Squadra']]; ?></td>
            <td><?php echo $row2['PtTOT']; ?></td>
            <td><?php echo $row2['GTOT']; ?></td>
            <td><?php echo $row2['VTOT']; ?></td>
            <td><?php echo $row2['NTOT']; ?></td>
            <td><?php echo $row2['PTOT']; ?></td>
            <td><?php echo $row2['GFTOT']; ?></td>
            <td><?php echo $row2['GSTOT']; ?></td>
            <td class="classifica"><?php echo $row2['DRTOT']; ?></td>
            <td><?php echo $row2['PtC']; ?></td>
            <td><?php echo $row2['GC']; ?></td>
            <td><?php echo $row2['VC']; ?></td>
            <td><?php echo $row2['NC']; ?></td>
            <td><?php echo $row2['PC']; ?></td>
            <td><?php echo $row2['GFC']; ?></td>
            <td><?php echo $row2['GSC']; ?></td>
            <td class="classifica"><?php echo $row2['DRC']; ?></td>
            <td><?php echo $row2['PtT']; ?></td>
            <td><?php echo $row2['GT']; ?></td>
            <td><?php echo $row2['VT']; ?></td>
            <td><?php echo $row2['NT']; ?></td>
            <td><?php echo $row2['PT']; ?></td>
            <td><?php echo $row2['GFT']; ?></td>
            <td><?php echo $row2['GST']; ?></td>
            <td><?php echo $row2['DRT']; ?></td>
            </tr>
            <?php
            $i++;
            
        }
        ?>
</table><br><br>



<h3 class="text-center">Tabellone Incontri</h3>
<table id="table">
    <tr>
        <th >Team</th>
        <?php foreach ($squadre as $id => $abbreviazione) { ?>
            <th ><?php echo $abbreviazione; ?></th>
        <?php } ?>
    </tr>

    <?php foreach ($squadre as $idSquadra1 => $abbreviazioneSquadra1) { ?>
        <tr>
            <td class="tabellone"><?php echo $squadren[$idSquadra1]; ?></td>
            <?php foreach ($squadre as $idSquadra2 => $abbreviazioneSquadra2) { ?>
                <td class="tabellone">
                    <?php
                    // Trova il risultato della partita tra $squadra1 e $squadra2
                    $risultato = "";
                    foreach ($partite as $partita) {
                        if (($partita['ID_Squadra1'] == $idSquadra1 && $partita['ID_Squadra2'] == $idSquadra2)) {
                            $risultato = $partita['GF1'] . "-" . $partita['GF2'];
                            break;
                        } else {
                            $risultato = "-";
                        }
                    }
                    echo $risultato;
                    ?>
                </td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>
<div class="text-center mb-5"><a class="btn btn-primary" href="simula.php?ID=<?php echo $campionato ?>">Simula</a></div>
