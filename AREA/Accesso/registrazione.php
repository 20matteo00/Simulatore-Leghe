<!DOCTYPE html>
<html lang="en">
<?php session_start(); include ("../TAG/head.html");?>

<body>
    <?php include("../TAG/header.php");?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center mb-4">Registrazione</h2>
                <hr>
                <form action="#" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-4">
                            <label for="inputNome">Nome</label>
                            <input type="text" class="form-control" name="nome" id="inputNome" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputCognome">Cognome</label>
                            <input type="text" class="form-control" name="cognome" id="inputCognome" required>
                        </div>
                        <div class="form-group col-md-4">
                            <label for="inputDataNascita">Data di Nascita</label>
                            <input type="date" class="form-control" name="dataNascita" id="inputDataNascita">
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="inputUsername">Username</label>
                            <input type="text" class="form-control" name="username" id="inputUsername" required>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="inputEmail">Email</label>
                            <input type="email" class="form-control" name="email" id="inputEmail" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-5">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" name="password" id="inputPassword" required>
                        </div>
                        <div class="form-group col-md-5">
                            <label for="inputConfermaPassword">Conferma Password</label>
                            <input type="password" class="form-control" name="confermaPassword"
                                id="inputConfermaPassword" required>
                        </div>
                        <div class="form-group col-md-2 mt-2">
                            <label for="mostraPassword">Mostra Password</label>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" onclick="mostraPassword()">
                            </div>
                        </div>
                    </div>
                    <input id="accesso_bottoneinvio" type="submit" name="submit" value="invia"
                        class="btn btn-primary btn-block mt-3 text-center">
                </form>
                <hr>
                <div class="mt-3">
                    <p class="text-center">Se hai già un account <a href="login.php">clicca qui</a>.</p>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Funzione di validazione per la data di nascita
    function isValidDate($date) {
        $currentDate = date('Y-m-d');
        $minDate = date('1900-01-01');
        
        return ($date >= $minDate && $date <= $currentDate);
    }

    // Funzione di validazione per l'email
    function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false && strpos($email, '@') !== false && strpos($email, '.') !== false;
    }

    // Funzione di validazione per la password
    function isValidPassword($password) {
        // La password deve contenere almeno 8 caratteri, 1 numero e 1 carattere speciale
        return strlen($password) >= 8 && preg_match("/[0-9]/", $password) && preg_match("/[!@#$%^&*()_+]+/", $password);
    }

    // Altri controlli e connessione al database...

    if (!isset($_SESSION['ID'])) {
        if (isset($_POST['submit'])) {
            include("../conn.php");
            if (isset($_POST['nome']) && isset($_POST['cognome']) && isset($_POST['dataNascita']) && isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confermaPassword'])) {
                $nome = $_POST['nome'];
                $cognome = $_POST['cognome'];
                $dataNascita = $_POST['dataNascita'];
                $username = $_POST['username'];
                $email = strtolower($_POST['email']);
                $password = $_POST['password'];
                $confermaPassword = $_POST['confermaPassword'];

                // Verifica se l'username è unico
                $queryUsername = "SELECT * FROM utenti WHERE Username = '$username'";
                $resultUsername = mysqli_query($conn, $queryUsername);

                // Verifica se l'email è unica
                $queryEmail = "SELECT * FROM utenti WHERE Email = '$email'";
                $resultEmail = mysqli_query($conn, $queryEmail);

                // Altri controlli
                $currentDate = date('Y-m-d');
                $minDate = '1900-01-01';

                if (mysqli_num_rows($resultUsername) > 0) {
                    echo "<br><div class='container'><div class='alert alert-danger'>Username già in uso. Scegli un altro username.</div></div>";
                } elseif (mysqli_num_rows($resultEmail) > 0) {
                    echo "<br><div class='container'><div class='alert alert-danger'>Email già in uso. Scegli un'altra email.</div></div>";
                } elseif (!isValidDate($dataNascita)) {
                    echo "<br><div class='container'><div class='alert alert-danger'>La data di nascita non è valida o è nel futuro.</div></div>";
                } elseif (!isValidEmail($email)) {
                    echo "<br><div class='container'><div class='alert alert-danger'>Formato email non valido.</div></div>";
                } elseif (!isValidPassword($password)) {
                    echo "<br><div class='container'><div class='alert alert-danger'>La password deve essere di almeno 8 caratteri e contenere almeno un numero e un carattere speciale.</div></div>";
                } elseif ($password != $confermaPassword) {
                    echo "<br><div class='container'><div class='alert alert-danger'>Le password non corrispondono.</div></div>";
                } else {
                    // Tutti i controlli passati, procedi con l'inserimento nel database
                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT); // Cifra la password

                    $queryInsert = "INSERT INTO utenti (Nome, Cognome, DataNascita, Username, Email, Password) VALUES ('$nome', '$cognome', '$dataNascita', '$username', '$email', '$hashedPassword')";
                    $resultInsert = mysqli_query($conn, $queryInsert);

                    if ($resultInsert) {
                        $query = "SELECT * FROM utenti WHERE Username = '$username'";
                        $result = mysqli_query($conn, $query);
                        if (mysqli_num_rows($result) == 1) {
                            $row = mysqli_fetch_array($result);
                            $_SESSION['Username'] = $row['Username'];
                            $_SESSION['Email'] = $row['Email'];
                            $_SESSION['ID'] = $row['ID'];
                            $_SESSION['Nome'] = $row['Nome'];
                            $_SESSION['Cognome'] = $row['Cognome'];
                            $_SESSION['DataNascita'] = $row['DataNascita'];
                        }
                        echo "<br><div class='container'><div class='alert alert-success'>Registrazione Effettuata Con Successo</div></div>";
                        echo "<script>window.location.href='/AREA/index.php';</script>";
                        exit();
                    } else {
                        echo "<br><div class='container'><div class='alert alert-danger'>Registrazione Fallita</div></div>";
                    }
                }
            }
        }
    } else {
        echo "<script>window.location.href='/AREA/index.php';</script>";
        exit();
    }
    ?>

    <script>
    function mostraPassword() {
        var x = document.getElementById("inputPassword");
        var y = document.getElementById("inputConfermaPassword");
        if (x.type === "password") {
            x.type = "text";
            y.type = "text";
        } else {
            x.type = "password";
            y.type = "password";
        }
    }
    </script>
</body>

</html>