<!DOCTYPE html>
<html lang="en">

<head>
    <?php include("TAG/head.html");
    $rowsPerPage = isset($_GET['rowsPerPage']) ? (int)$_GET['rowsPerPage'] : 10; // Numero predefinito di righe per pagina
    $orderBy = isset($_GET['orderBy']) ? $_GET['orderBy'] : "ID"; // Ordina per ID, Nome o Partecipanti
    $order = isset($_GET['order']) ? $_GET['order'] : "asc"; // Ordine ascendente o discendente
    ?>
</head>

<body>
    <?php include("conn.php"); include("TAG/navbar.html"); ?>
    <div class="container mt-5">
        <form class="form-inline" id="paginationForm" method="GET">
            <div class="row">

                <div class="col-lg-auto form-group flex-fill mr-2">
                    <label for="rowsPerPage" class="mr-2"># for page:</label>
                    <select class="form-select" id="rowsPerPage" name="rowsPerPage">
                        <option value="5" <?php if ($rowsPerPage == 5) echo "selected"; ?>>5</option>
                        <option value="10" <?php if ($rowsPerPage == 10) echo "selected"; ?>>10</option>
                        <option value="20" <?php if ($rowsPerPage == 20) echo "selected"; ?>>20</option>
                        <option value="30" <?php if ($rowsPerPage == 30) echo "selected"; ?>>30</option>
                        <option value="40" <?php if ($rowsPerPage == 40) echo "selected"; ?>>40</option>
                        <option value="50" <?php if ($rowsPerPage == 50) echo "selected"; ?>>50</option>
                    </select>
                </div>
                <div class="col-lg-auto form-group flex-fill mr-2">
                    <label for="orderBy" class="mr-2">Ordina per:</label>
                    <select class="form-select" id="orderBy" name="orderBy">
                        <option value="ID" <?php if ($orderBy == "ID") echo "selected"; ?>>ID</option>
                        <option value="Nome" <?php if ($orderBy == "Nome") echo "selected"; ?>>Nome</option>
                        <option value="Partecipanti" <?php if ($orderBy == "Partecipanti") echo "selected"; ?>>Partecipanti</option>
                    </select>
                </div>
                <div class="col-lg-auto mt-4">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="order" id="asc" value="asc" <?php if ($order == "asc") echo "checked"; ?>>
                        <label class="form-check-label" for="asc">Asc</label>
                    </div>

                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="order" id="desc" value="desc" <?php if ($order == "desc") echo "checked"; ?>>
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
                    <th scope="col">Partecipanti</th>
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
                if ($currentPage < 1) {
                    $currentPage = 1; // Imposta il valore minimo consentito per la pagina corrente
                }

                $offset = ($currentPage - 1) * $rowsPerPage;

                $sql = "SELECT * FROM campionato ORDER BY $orderBy $order LIMIT $offset, $rowsPerPage";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    // output dei dati di ogni riga
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr><td>" . $row["ID"] . "</td><td>" . $row["Nome"] . "</td><td>" . $row["Partecipanti"] . "</td></tr>";
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
                // Calcola il numero di pagine
                $sql = "SELECT COUNT(*) AS total FROM campionato";
                $result = $conn->query($sql);
                $row = $result->fetch_assoc();
                $totalRows = $row['total'];
                $totalPages = ceil($totalRows / $rowsPerPage);

                // Calcola la pagina precedente e successiva
                $prevPage = ($currentPage > 1) ? $currentPage - 1 : 1;
                $nextPage = ($currentPage < $totalPages) ? $currentPage + 1 : $totalPages;

                // Calcola il range di pagine da visualizzare (5 pagine precedenti e 5 pagine successive)
                $pagesToShow = 5;
                $startPage = max(1, $currentPage - $pagesToShow);
                $endPage = min($totalPages, $currentPage + $pagesToShow);

                // Link "First"
                if ($currentPage != 1) {
                    echo "<li class='page-item'><a class='page-link' href='?page=1&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order'>First</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>First</span></li>";
                }

                // Pagina precedente
                if ($currentPage > 1) {
                    echo "<li class='page-item'><a class='page-link' href='?page=$prevPage&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order'>Prev</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Prev</span></li>";
                }

                // Pagine numerate
                for ($i = $startPage; $i <= $endPage; $i++) {
                    if ($i == $currentPage) {
                        echo "<li class='page-item active'><span class='page-link'>$i</span></li>";
                    } else {
                        echo "<li class='page-item'><a class='page-link' href='?page=$i&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order'>$i</a></li>";
                    }
                }

                // Pagina successiva
                if ($currentPage < $totalPages) {
                    echo "<li class='page-item'><a class='page-link' href='?page=$nextPage&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order'>Next</a></li>";
                } else {
                    echo "<li class='page-item disabled'><span class='page-link'>Next</span></li>";
                }

                // Link "Last"
                if ($currentPage != $totalPages) {
                    echo "<li class='page-item'><a class='page-link' href='?page=$totalPages&rowsPerPage=$rowsPerPage&orderBy=$orderBy&order=$order'>Last</a></li>";
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