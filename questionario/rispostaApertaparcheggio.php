<?php
session_start();
include 'db_connect.php';  // Include il file di connessione al database

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $risposta = $conn->real_escape_string($_POST['risposta_libera']);
    $id_utente = $_SESSION['user_id'];  // Assumendo che l'ID utente sia già in sessione

    // Salva la risposta nel database
    $sql = "INSERT INTO risposte_aperte (risposta, id_utente) VALUES ('$risposta', $id_utente)";
    $conn->query($sql);
    $conn->close();

    // Reindirizza alla prossima domanda
    header("Location: domanda_vero_falso.html");
    exit;
}
?>
