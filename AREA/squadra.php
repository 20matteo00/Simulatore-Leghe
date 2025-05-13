<!DOCTYPE html>
<html lang="en">

<?php include("TAG/head.html");
$rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 10; // Numero predefinito di righe per pagina
$orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : "ID"; // Ordina per ID, Nome o Partecipanti
$order = isset($_GET['order']) ? $_GET['order'] : "asc"; // Ordine ascendente o discendente
$campionato = isset($_GET['campionato']) ? $_GET['campionato'] : "ALL"; // Ordine ascendente o discendente
session_start();
if(!isset($_SESSION['ID'])) {
    header("Location: /AREA/Accesso/login.php");
}
?>


<body>
    <?php include("conn.php"); include("TAG/header.php"); ?>
    <div class="container mt-5">
        <form class="form-inline" id="paginationForm" method="GET">
            <div class="row">

                <div class="col-lg-auto form-group flex-fill mr-2">
                    <label for="rowsPerPage" class="mr-2"># for page:</label>
                    <select class="form-select" id="rowsPerPage" name="rowsPerPage">
                        <option value="5" <?php if ($rowsPerPage == 5) echo "selected"; ?>>5</option>
                        <option value="10" <?php if ($rowsPerPage == 10) echo "selected"; ?>>10</option>
                        <option value="20" <?php if ($rowsPerPage == 20) echo "selected"; ?>>20</option>
                        <option value="50" <?php if ($rowsPerPage == 50) echo "selected"; ?>>50</option>
                        <option value="100" <?php if ($rowsPerPage == 100) echo "selected"; ?>>100</option>
                        <option value="250" <?php if ($rowsPerPage == 250) echo "selected"; ?>>250</option>
                    </select>
                </div>
                <div class="col-lg-auto form-group flex-fill mr-2">
                    <label for="orderBy" class="mr-2">Ordina per:</label>
                    <select class="form-select" id="orderBy" name="orderBy">
                        <option value="ID" <?php if ($orderBy == "ID") echo "selected"; ?>>ID</option>
                        <option value="Nome" <?php if ($orderBy == "Nome") echo "selected"; ?>>Nome</option>
                        <option value="Forza" <?php if ($orderBy == "Forza") echo "selected"; ?>>Forza</option>
                        <option value="ID_Campionato" <?php if ($orderBy == "ID_Campionato") echo "selected"; ?>>
                            Campionato</option>
                    </select>
                </div>
                <div class="col-lg-auto form-group flex-fill mr-2">
                    <label for="orderBy" class="mr-2">Campionato:</label>
                    <select class="form-select" id="campionato" name="campionato">
                        <option value="0" <?php if ($campionato == "ALL") echo "selected"; ?>>ALL</option>
                        <?php

                        $camp = "SELECT * FROM campionato order by ID";
                        $result2 = $conn->query($camp);
                        if ($result2->num_rows > 0) {
                            // output dei dati di ogni riga
                            while ($row2 = $result2->fetch_assoc()) {
                                echo "<option value='" . $row2["ID"] . "' " . ($campionato == $row2["ID"] ? "selected" : "") . ">" . $row2["Nome"] . "</option>";
                            }
                        } else {
                            echo "0 results";
                        }
                        ?>
                    </select>
                </div>
                <div class="col-lg-auto mt-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="order" id="asc" value="asc"
                            <?php if ($order == "asc") echo "checked"; ?>>
                        <label class="form-check-label" for="asc">Asc</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="order" id="desc" value="desc"
                            <?php if ($order == "desc") echo "checked"; ?>>
                        <label class="form-check-label" for="desc">Desc</label>
                    </div>

                    <input type="hidden" name="page" value="<?php echo $currentPage; ?>">
                    <button type="submit" class="btn btn-primary" name="submit">Invia</button>
                </div>
            </div>

        </form>


        <!-- Tabella del campionato -->
        <table class="table mt-3" id="campionatoTable">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Nome</th>
                    <th scope="col">Forza</th>
                    <th scope="col">Campionato</th>
                </tr>
            </thead>
            <tbody>
                <!-- Inserire qui i dati dal database -->
                <?php
                //if (isset($_GET['submit'])) {
                $rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 10; // Numero predefinito di righe per pagina
                $currentPage = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : "ID"; // Ordina per ID, Nome o Partecipanti
                $order = isset($_GET['order']) ? $_GET['order'] : "asc"; // Ordine ascendente o discendente
                $campionato = isset($_GET['campionato']) ? $_GET['campionato'] : "0"; // Ordine ascendente o discendente
                if ($currentPage < 1) {
                    $currentPage = 1; // Imposta il valore minimo consentito per la pagina corrente
                }

                $offset = ($currentPage - 1) * $rowsPerPage;
                
                // Calcola il numero totale di squadre in base ai criteri di ricerca correnti
                if ($campionato == "0") {
                    $countQuery = "SELECT COUNT(*) AS total FROM squadra";
                } else {
                    $countQuery = "SELECT COUNT(*) AS total FROM squadra WHERE ID_Campionato = $campionato";
                }
                
                $countResult = $conn->query($countQuery);
                $countRow = $countResult->fetch_assoc();
                $totalRows = $countRow['total'];
                
                // Calcola il numero di pagine
                $totalPages = ceil($totalRows / $rowsPerPage);
                
                if ($campionato == "0") {
                    $sql = "SELECT squadra.*, campionato.Nome AS NomeCampionato 
                            FROM squadra
                            LEFT JOIN campionato ON squadra.ID_Campionato = campionato.ID
                            ORDER BY $orderBy $order LIMIT $offset, $rowsPerPage";
                } else {
                    $sql = "SELECT squadra.*, campionato.Nome AS NomeCampionato 
                            FROM squadra
                            LEFT JOIN campionato ON squadra.ID_Campionato = campionato.ID
                            WHERE squadra.ID_Campionato = $campionato
                            ORDER BY $orderBy $order LIMIT $offset, $rowsPerPage";
                }
                
                $result = $conn->query($sql);
                
                if ($result->num_rows > 0) {
                    // output dei dati di ogni riga
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Nome"] . "</td><td>" . $row["Forza"] . "</td><td>" . $row["NomeCampionato"] . "</td></tr>";
                    }
                } else {
                    echo "0 results";
                }
                
                //}
                ?>
            </tbody>
        </table>

        <!-- Paginazione -->
        <nav aria-label="Paginazione" id="paginationNav">
            <ul class="pagination justify-content-center mt-3">
                <?php
                //if (isset($_GET['submit'])) {
                // Calcola la pagina precedente e successiva
                $prevPage = ($currentPage > 1) ? $currentPage - 1 : 1;
                $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : $totalPages;

                // Calcola il range di pagine da visualizzare (5 pagine precedenti e 5 pagine successive)
                $pagesToShow = 5;
                $startPage = max(1, $currentPage - $pagesToShow);
                $endPage = min($totalPages, $currentPage + $pagesToShow);

                // Link "First"
                if ($currentPage != 1) {
                    echo "<li class='page-item'><a class='page-link' href='?page=1&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order&campionato=$campionato'>First</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>First</span></li>";
                }

                // Pagina precedente
                if ($currentPage > 1) {
                    echo "<li class='page-item'><a class='page-link' href='?page=$prevPage&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order&campionato=$campionato'>Prev</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
                }

                // Pagine numerate
                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $currentPage) {
                        echo "<li class='page-item active'><span class='page-link'>$i</span></li>";
                    } else {
                        echo "<li class='page-item'><a class='page-link' href='?page=$i&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order&campionato=$campionato'>$i</a></li>";
                    }
                }

                // Pagina successiva
                if ($currentPage < $totalPages) {
                    echo "<li class='page-item'><a class='page-link' href='?page=$nextPage&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order&campionato=$campionato'>Next</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
                }

                // Link "Last"
                if ($currentPage != $totalPages) {
                    echo "<li class='page-item'><a class='page-link' href='?page=$totalPages&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order&campionato=$campionato'>Last</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Last</span></li>";
                }

                //}
                ?>
            </ul>
        </nav>
    </div>
</body>

</html>