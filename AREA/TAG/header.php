<div class="container">
    <header
        class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12 mb-2 mb-md-0 text-center">
            <a href="/AREA/index.php" class="d-inline-flex link-body-emphasis text-decoration-none">
                <img id="brand" src="/AREA/IMG/Logo.jpg" alt="Simulatore Leghe">
            </a>
        </div>

        <ul class="nav col-lg-6 col-md-6 col-sm-12 col-xs-12 col-12 mb-2 justify-content-center mb-md-0">
            <li><a href="/AREA/squadra.php" class="nav-link px-2">Squadre</a></li>
            <li><a href="/AREA/campionato.php" class="nav-link px-2">Campionati</a></li>
            <li><a href="/AREA/bacheca.php" class="nav-link px-2">Bacheca</a></li>
            <li><a href="/AREA/Accesso/area.php" class="nav-link px-2">Area Riservata <i
                        class="bi bi-house-lock-fill"></i></a></li>
        </ul>

        <div class="col-lg-3 col-md-3 col-sm-12 col-xs-12 col-12 text-center">
            <?php
            if (!isset($_SESSION)) {
                session_start();
            }
            if (isset($_SESSION['ID'])) {
                // Utente loggato
                echo '<a href="/AREA/Accesso/logout.php" type="button" class="btn btn-outline-primary me-2 mb-1">Logout</a>';
                echo '<a href="/AREA/profilo.php" type="button" class="btn btn-primary mb-1">'.$_SESSION['Nome']." ".$_SESSION['Cognome'].'</a>';
            } else {
                // Utente non loggato
                echo '<a href="/AREA/Accesso/login.php" type="button" class="btn btn-outline-primary me-2 mb-1">Accedi</a>';
                echo '<a href="/AREA/Accesso/registrazione.php" type="button" class="btn btn-primary mb-1">Registrati</a>';
            }
            ?>
        </div>
    </header>
</div>