<!DOCTYPE html>
<html lang="en">
<?php session_start(); include ("../TAG/head.html");?>

<body>
    <?php include("../TAG/header.php");?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Recupero Password</h2>
                <hr>
                <form action="#" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputUsername">Username</label>
                            <input type="text" class="form-control" name="username" id="inputUsername"
                                value="<?php if(isset($_POST['username'])) echo $_POST['username']?>" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputEmail">Email</label>
                            <input type="text" class="form-control" name="email" id="inputEmail"
                                value="<?php if(isset($_POST['username'])) echo $_POST['email']?>" required>
                        </div>
                    </div>
                    <input id="accesso_bottoneinvio" type="submit" name="submit" value="invia"
                        class="btn btn-primary btn-block mt-3 text-center">
                </form>
            </div>
        </div>
    </div>
    <?php
        include("../conn.php");

        if(!isset($_SESSION['ID'])){
            if (isset($_POST['submit'])) {
                // Ottenere i dati inviati dal form
                $email = strtolower($_POST['email']);
                $username = $_POST['username'];

                // Verifica se l'email e l'username corrispondono a quelli nel database
                $query = "SELECT * FROM utenti WHERE Email = '$email' AND Username = '$username'";
                $result = mysqli_query($conn, $query);

                if (mysqli_num_rows($result) == 1) {
                    // Utente trovato, mostra il form per la nuova password
    ?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <hr>
                <form action="#" method="post">
                    <div class="form-group">
                        <label for="inputPassword">Nuova Password:</label>
                        <input type="password" class="form-control" id="inputPassword" name="newPassword" required>
                    </div>
                    <div class="form-group">
                        <label for="inputConfermaPassword">Conferma Password:</label>
                        <input type="password" class="form-control" id="inputConfermaPassword" name="confirmPassword"
                            required>
                    </div>
                    <div class="form-group">
                        <label for="mostraPassword">Mostra Password</label>
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" onclick="mostraPassword()">
                        </div>
                    </div>
                    <input type="hidden" name="email"
                        value="<?php if(isset($_POST['email'])) echo $_POST['email']?>">
                    <input type="hidden" name="username"
                        value="<?php if(isset($_POST['username'])) echo $_POST['username']?>">
                    <input id="accesso_bottoneinvio" type="submit" name="submit2" value="invia"
                        class="btn btn-primary btn-block mt-3 text-center">
                </form>
            </div>
        </div>
    </div>
    <?php
                } else {
                    echo "<div class='container'><div class='alert alert-danger'>Le credenziali fornite non corrispondono a nessun account.</div></div>";
                }

                mysqli_close($conn);
            } elseif(isset($_POST['submit2'])){
                // Ottenere i dati inviati dal form
                $email = strtolower($_POST['email']);
                $username = $_POST['username'];
                $newPassword = $_POST['newPassword'];
                $confirmPassword = $_POST['confirmPassword'];

                if ($newPassword == $confirmPassword  && isValidPassword($newPassword)) {
                    // Hash della nuova password
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            
                    // Aggiorna la password nel database
                    $query = "UPDATE utenti SET Password = '$hashedPassword' WHERE Email = '$email' AND Username = '$username'";


                    $result = mysqli_query($conn, $query);
            
                    if ($result) {
                        echo "<div class='container'><div class='alert alert-success'>La password è stata aggiornata con successo.</div></div>";
                        //echo "<script>window.location.href='/AREA/login.php';</script>";
                        echo $query;
                        //exit();
                    } else {
                        echo "<div class='container'><div class='alert alert-danger'>Si è verificato un errore durante l'aggiornamento della password.</div></div>";
                    }
                } else {
                    echo "<div class='container'><div class='alert alert-danger'>Le password non corrispondono o non rispettano le regole.</div></div>";
                }
            }
        } else {
            header("Location: /AREA/index.php");
            exit();
        }

        // Funzione di validazione per la password
        function isValidPassword($password) {
            // La password deve contenere almeno 8 caratteri, 1 numero e 1 carattere speciale
            return strlen($password) >= 8 && preg_match("/[0-9]/", $password) && preg_match("/[!@#$%^&*()_+]+/", $password);
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