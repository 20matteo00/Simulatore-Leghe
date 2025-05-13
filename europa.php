<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("TAG/head.html"); ?>
</head>

<body>
    <?php include("conn.php");
    include("TAG/navbar.html");
    $GCL = array();
    $QCL = array();
    $GEL = array();
    $QEL = array();
    $QECL = array();

    $sql = "SELECT count(*) as total FROM campionato";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $campionati = $row['total'];

    for ($i = 1; $i <= $campionati; $i++) {
        $sql2 = "SELECT * FROM statistiche WHERE id_campionato = $i ORDER BY PtTOT DESC, DRTOT DESC, GFTOT DESC, ID_Squadra ASC";

        $result2 = $conn->query($sql2);
        $c = 1;

        while ($row2 = $result2->fetch_assoc()) {
            // Creiamo un array associativo con ID del campionato e ID della squadra
            $squadra = array("campionato" => $i, "squadra" => $row2['ID_Squadra']);


            if ($i <= 4) {
                if ($c <= 4) {
                    $GCL[] = $squadra;
                } elseif ($c <= 6) {
                    $GEL[] = $squadra;
                } elseif ($c <= 8) {
                    $QECL[] = $squadra;
                }
            } elseif ($i <= 6) {
                if ($c <= 2) {
                    $GCL[] = $squadra;
                } elseif ($c <= 4) {
                    $QCL[] = $squadra;
                } elseif ($c <= 6) {
                    $GEL[] = $squadra;
                } elseif ($c <= 8) {
                    $QECL[] = $squadra;
                }
            } elseif ($i <= 9) {
                if ($c <= 1) {
                    $GCL[] = $squadra;
                } elseif ($c <= 4) {
                    $QCL[] = $squadra;
                } elseif ($c <= 6) {
                    $GEL[] = $squadra;
                } elseif ($c <= 8) {
                    $QECL[] = $squadra;
                }
            } elseif ($i <= 10) {
                if ($c <= 1) {
                    $GCL[] = $squadra;
                } elseif ($c <= 4) {
                    $QCL[] = $squadra;
                } elseif ($c <= 6) {
                    $GEL[] = $squadra;
                } elseif ($c <= 7) {
                    $QECL[] = $squadra;
                }
            } elseif ($i <= 13) {
                if ($c <= 2) {
                    $QCL[] = $squadra;
                } elseif ($c <= 4) {
                    $QEL[] = $squadra;
                } elseif ($c <= 5) {
                    $QECL[] = $squadra;
                }
            } else {
                if ($c <= 1) {
                    $QCL[] = $squadra;
                } elseif ($c <= 2) {
                    $QEL[] = $squadra;
                } elseif ($c <= 3) {
                    $QECL[] = $squadra;
                }
            }

            $c++;
        }
    }


    // Per GCL
    foreach ($GCL as $squadra) {
        $campionato = $squadra['campionato'];
        $squadraID = $squadra['squadra'];

        $sql = "INSERT INTO GCL (ID_Campionato, ID_Squadra) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $campionato, $squadraID);
        $stmt->execute();
    }

    // Per GEL
    foreach ($GEL as $squadra) {
        $campionato = $squadra['campionato'];
        $squadraID = $squadra['squadra'];

        $sql = "INSERT INTO GEL (ID_Campionato, ID_Squadra) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $campionato, $squadraID);
        $stmt->execute();
    }

    // Per QCL
    foreach ($QCL as $squadra) {
        $campionato = $squadra['campionato'];
        $squadraID = $squadra['squadra'];

        $sql = "INSERT INTO QCL (ID_Campionato, ID_Squadra) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $campionato, $squadraID);
        $stmt->execute();
    }

    // Per QEL
    foreach ($QEL as $squadra) {
        $campionato = $squadra['campionato'];
        $squadraID = $squadra['squadra'];

        $sql = "INSERT INTO QEL (ID_Campionato, ID_Squadra) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $campionato, $squadraID);
        $stmt->execute();
    }

    // Per QECL
    foreach ($QECL as $squadra) {
        $campionato = $squadra['campionato'];
        $squadraID = $squadra['squadra'];

        $sql = "INSERT INTO QECL (ID_Campionato, ID_Squadra) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $campionato, $squadraID);
        $stmt->execute();
    }
    header("Location: menueuropa.php");  
    ?>
    

</body>

</html>