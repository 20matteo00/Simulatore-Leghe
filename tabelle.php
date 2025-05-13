<?php 
include("conn.php");

// Definisci gli script SQL
$sqlScripts = array(
    "MySQL/campionato.sql",
    "MySQL/squadre.sql",
    "MySQL/partita.sql",
    "MySQL/statistiche.sql",
    "MySQL/europa.sql"
);

// Esegui gli script SQL
foreach ($sqlScripts as $script) {
    // Leggi il contenuto dello script SQL
    $sql = file_get_contents($script);

    // Esegui la query
    if ($conn->multi_query($sql) === TRUE) {
        
        
        // Se ci sono risultati, scorri fino a quando sono disponibili
        while ($conn->next_result()) {}
    } else {
        echo "Errore nell'esecuzione dello script $script: " . $conn->error . "\n";
    }
}



header("Location: index.php");
?>
