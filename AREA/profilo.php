<!DOCTYPE html>
<html lang="en">
<?php include ("TAG/head.html");?>

<body>
    <?php include("TAG/header.php");?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-10">
                <h2 class="text-center mb-4">Profilo Utente</h2>
                <hr>
                <?php
                // Verifica se l'utente è loggato
                if (!isset($_SESSION)) {
                    session_start();
                }

                if (isset($_SESSION['ID'])) {
                    // Connessione al database
                    include("conn.php");

                    $userID = $_SESSION['ID'];

                    // Recupera i dati dell'utente dal database
                    $query = "SELECT * FROM utenti WHERE ID = '$userID'";
                    $result = mysqli_query($conn, $query);

                    if ($result) {
                        $row = mysqli_fetch_assoc($result);

                        // Visualizza i dati dell'utente in campi di input modificabili
                        echo '<form action="#" method="post">';
                        echo '<div class="form-row">';
                        echo '<div class="form-group col-md-4">';
                        echo '<label for="inputNome">Nome</label>';
                        echo '<input type="text" class="form-control" name="nome" id="inputNome" value="'.$row['Nome'].'" required>';
                        echo '</div>';
                        echo '<div class="form-group col-md-4">';
                        echo '<label for="inputCognome">Cognome</label>';
                        echo '<input type="text" class="form-control" name="cognome" id="inputCognome" value="'.$row['Cognome'].'" required>';
                        echo '</div>';
                        echo '<div class="form-group col-md-4">';
                        echo '<label for="inputDataNascita">Data di Nascita</label>';
                        echo '<input type="date" class="form-control" name="dataNascita" id="inputDataNascita" value="'.$row['DataNascita'].'">';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="form-row">';
                        echo '<div class="form-group col-md-6">';
                        echo '<label for="inputUsername">Username</label>';
                        echo '<input type="text" class="form-control" name="username" id="inputUsername" value="'.$row['Username'].'" required>';
                        echo '</div>';
                        echo '<div class="form-group col-md-6">';
                        echo '<label for="inputEmail">Email</label>';
                        echo '<input type="email" class="form-control" name="email" id="inputEmail" value="'.$row['Email'].'" required>';
                        echo '</div>';
                        echo '</div>';
                        echo '<div class="form-row">';
                        echo '<div class="form-group col-md-5">';
                        echo '<label for="inputPassword">Password</label>';
                        echo '<input type="password" class="form-control" name="password" id="inputPassword">';
                        echo '</div>';
                        echo '<div class="form-group col-md-5">';
                        echo '<label for="inputConfermaPassword">Conferma Password</label>';
                        echo '<input type="password" class="form-control" name="confermaPassword" id="inputConfermaPassword">';
                        echo '</div>';
                        echo '<div class="form-group col-md-2 mt-2">';
                        echo '<label for="mostraPassword">Mostra Password</label>';
                        echo '<div class="form-check">';
                        echo '<input class="form-check-input" type="checkbox" onclick="mostraPassword()">';
                        echo '</div>';
                        echo '</div>';
                        echo '</div>';
                        echo '<input type="submit" name="submit" value="Salva Modifiche" class="btn btn-primary btn-block mt-3 text-center">';
                        echo '</form>';

                        // Chiusura della connessione al database
                        mysqli_close($conn);
                    }
                } else {
                    // Utente non loggato
                    echo '<div class="alert alert-warning">Effettua l\'accesso per visualizzare il profilo.</div>';
                }
                ?>
                <hr>
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
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    // Funzione di validazione per la password
    function isValidPassword($password) {
        // La password deve contenere almeno 8 caratteri, 1 numero e 1 carattere speciale
        return preg_match('/^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*#?&])[A-Za-z\d@$!%*#?&]{8,}$/', $password);
    }

    // Verifica se il modulo è stato inviato
    if (isset($_POST['submit'])) {
        // Connessione al database
        include("../conn.php");

        // Recupera i dati inviati dal modulo
        $nome = $_POST['nome'];
        $cognome = $_POST['cognome'];
        $dataNascita = $_POST['dataNascita'];
        $username = $_POST['username'];
        $email = $_POST['email'];
        $password = $_POST['password'];
        $confermaPassword = $_POST['confermaPassword'];

        // Esegui la validazione dei dati
        $isValidData = true;

        // Validazione della data di nascita
        if (!isValidDate($dataNascita)) {
            echo "<br><div class='container'><div class='alert alert-danger'>La data di nascita non è valida o è nel futuro.</div></div>";
            $isValidData = false;
        }

        // Validazione dell'email
        if (!isValidEmail($email)) {
            echo "<br><div class='container'><div class='alert alert-danger'>L'indirizzo email non è valido.</div></div>";
            $isValidData = false;
        }

        // Validazione della password
        if (!empty($password) && !isValidPassword($password)) {
            echo "<br><div class='container'><div class='alert alert-danger'>La password deve contenere almeno 8 caratteri, di cui almeno 1 numero e 1 carattere speciale.</div></div>";
            $isValidData = false;
        }

        // Se tutti i controlli passano, esegui l'aggiornamento dei dati nel database
        if ($isValidData) {
            // Esegui l'aggiornamento dei dati nel database...
            if (!empty($password)) {
                // Se la password è stata modificata, esegui l'hashing
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
                $queryUpdate = "UPDATE utenti SET Nome='$nome', Cognome='$cognome', DataNascita='$dataNascita', Username='$username', Email='$email', Password='$hashedPassword' WHERE ID='$userID'";
            } else {
                // Altrimenti, esegui l'aggiornamento senza modificare la password
                $queryUpdate = "UPDATE utenti SET Nome='$nome', Cognome='$cognome', DataNascita='$dataNascita', Username='$username', Email='$email' WHERE ID='$userID'";
            }
            if (isset($queryUpdate)) {
                // Esegui l'aggiornamento dei dati
                $resultUpdate = mysqli_query($conn, $queryUpdate);
    
                if ($resultUpdate) {
                    echo '<br><div class="container"><div class="alert alert-success">Modifiche salvate con successo</div></div>';
                } else {
                    echo '<br><div class="container"><div class="alert alert-danger">Errore durante il salvataggio delle modifiche</div></div>';
                }
            }
    
            // Chiusura della connessione al database
            mysqli_close($conn);
        }
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