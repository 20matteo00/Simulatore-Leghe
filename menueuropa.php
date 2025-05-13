<?php
    include("TAG/navbar3.html");
?>
<head><?php include ("TAG/head.html");?></head>
<?php
    include("conn.php");
    $cl = array();
    $el = array();
    $ecl = array();

    $sql = "SELECT * FROM GCL, squadra WHERE GCL.ID_Squadra=squadra.ID AND GCL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
    $result = mysqli_query($conn, $sql);
    while ($row = $result->fetch_assoc()) {        $cl[] = $row;    }    
    $sql1 = "SELECT * FROM QCL, squadra WHERE QCL.ID_Squadra=squadra.ID AND QCL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
    $result1 = mysqli_query($conn, $sql1);
    while ($row1 = $result1->fetch_assoc()) {        $cl[] = $row1;    }
    $sql2 = "SELECT * FROM GEL, squadra WHERE GEL.ID_Squadra=squadra.ID AND GEL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
    $result2 = mysqli_query($conn, $sql2);
    while ($row2 = $result2->fetch_assoc()) {        $el[] = $row2;    }
    $sql3 = "SELECT * FROM QEL, squadra WHERE QEL.ID_Squadra=squadra.ID AND QEL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
    $result3 = mysqli_query($conn, $sql3);
    while ($row3 = $result3->fetch_assoc()) {        $el[] = $row3;    }
    $sql4 = "SELECT * FROM QECL, squadra WHERE QECL.ID_Squadra=squadra.ID AND QECL.ID_Campionato=squadra.ID_Campionato AND Eliminato=1000";
    $result4 = mysqli_query($conn, $sql4);
    while ($row4 = $result4->fetch_assoc()) {        $ecl[] = $row4;    }
?>

<div class="container mt-5 text-center">
    <div class="row mb-5">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0"><a id="qual" href="CL/dettagli.php">Champions League</a></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0"><a id="qual" href="EL/dettagli.php">Europa League</a></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title mb-0"><a id="qual" href="ECL/dettagli.php">Conference League</a></h5>
                </div>
            </div>
        </div>
    </div>
    <div class="row mb-5">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center">Vincitore: <?php echo $cl[0]['Nome']; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center">Vincitore: <?php echo $el[0]['Nome']; ?></h5>
                </div>
            </div>
        </div>
        <div class="col-lg-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="text-center">Vincitore: <?php echo $ecl[0]['Nome']; ?></h5>
                </div>
            </div>
        </div>
    </div>
</div>
