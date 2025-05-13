<!DOCTYPE html>
<html lang="en">
<?php session_start(); include ("../TAG/head.html"); if(!isset($_SESSION['ID'])||$_SESSION['ID']!=1){?>

<body>
    <?php include("../TAG/header.php");?>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <h2 class="text-center mb-4">Area Riservata</h2>
                <hr>
                <form action="#" method="post">
                    <div class="form-row">
                        <div class="form-group col-md-12">
                            <label for="inputUsername">Username o Email</label>
                            <input type="text" class="form-control" name="username" id="inputUsername" required>
                        </div>
                    </div>
                    <div class="form-row">
                        <div class="form-group col-md-10">
                            <label for="inputPassword">Password</label>
                            <input type="password" class="form-control" name="password" id="inputPassword" required>
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
            </div>
        </div>
    </div>
    <?php 
        
            if(isset($_POST['submit'])){
                include("../conn.php");
                if (isset($_POST['username']) && isset($_POST['password'])) {
                    $username = $_POST['username'];
                    $password = $_POST['password'];
                    $email = strtolower($_POST['username']);
                    
                    // Verifica se l'utente esiste nel database
                    $query = "SELECT * FROM utenti WHERE Username = '$username' OR Email = '$email'";
                    $result = mysqli_query($conn, $query);

                    if ($result) {
                        if (mysqli_num_rows($result) == 1) {
                            $row = mysqli_fetch_assoc($result);
                            $hashedPassword = $row['Password'];
                            
                            // Verifica la password utilizzando password_verify
                            if (password_verify($password, $hashedPassword)) {
                                // Password corretta, esegui l'accesso
                                $_SESSION['ID'] = $row['ID'];
                                $_SESSION['Nome'] = $row['Nome'];
                                $_SESSION['Cognome'] = $row['Cognome'];
                                $_SESSION['Username'] = $row['Username'];
                                $_SESSION['Email'] = $row['Email'];
                                $_SESSION['DataNascita'] = $row['DataNascita'];
                                if($_SESSION['ID']==1){
                                    echo "<script>window.location.href='/AREA/index.php';</script>";
                                    exit();
                                } else {
                                    echo "<br><div class='container'><div class='alert alert-danger'>Non sei abilitato.</div></div>";
                                    exit();
                                }
                                
                            } else {
                                // Password non corretta
                                echo "<br><div class='container'><div class='alert alert-danger'>Credenziali non valide. Riprova.</div></div>";
                            }
                        } else {
                            // Utente non trovato
                            echo "<br><div class='container'><div class='alert alert-danger'>Credenziali non valide. Riprova.</div></div>";
                        }
                    } else {
                        // Errore nella query
                        echo "<br><div class='container'><div class='alert alert-danger'>Errore nella query di accesso.</div></div>";
                    }
                }
            }
        } else {
            header("Location: /AREA/index.php");
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