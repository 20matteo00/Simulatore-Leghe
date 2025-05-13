<?php
// Leggi il contenuto dello script SQL da eseguire
$sqlScript = file_get_contents('MySQL/mysql.sql');
// Verifica se il file è stato letto correttamente
if ($sqlScript === false) {
    die("Impossibile leggere il file SQL");
}
// Esegui lo script
if (!($conn->multi_query($sqlScript))) {
    echo "Errore durante l'esecuzione dello script SQL: " . $conn->error;
}
?>