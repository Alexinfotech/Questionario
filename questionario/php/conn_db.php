<?php
function connessione() {
    $host = '151.30.251.67:3306';  // IP e porta del server MySQL
    $dbname = 'questionario';  // Nome del database
    $username = 'alex';  // Username dell'utente
    $password = 'Tmax2011!';  // Password dell'utente

    // Crea la connessione al database
    $conn = new mysqli($host, $username, $password, $dbname);

    // Controlla la connessione
    if ($conn->connect_error) {
        die('Connessione fallita: ' . $conn->connect_error);
    }

    return $conn;
}
?>
 