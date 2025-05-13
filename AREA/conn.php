<?php
$servername = "localhost"; // Nome del server MySQL
$username = "simulatoreleghe"; // Nome utente del database
$password = ""; // Password del database
$dbname = "my_simulatoreleghe"; // Nome del database a cui connettersi

/*
$username = "root"; // Nome utente del database
$password = "Matteo00"; // Password del database
$dbname = "lega"; // Nome del database a cui connettersi
*/

// Creare una connessione al database
$conn = new mysqli($servername, $username, $password, $dbname);

// Controlla la connessione
if ($conn->connect_error) {
    die("Connessione al database fallita: " . $conn->connect_error);
}

// Ora $conn è una connessione attiva al tuo database MySQL
?>

