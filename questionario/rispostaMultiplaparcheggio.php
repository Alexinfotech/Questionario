<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $risposta = $conn->real_escape_string($_POST['vero_falso']);
    $id_utente = $_SESSION['user_id'];

    // Salva la risposta nel database
    $sql = "INSERT INTO risposte_vero_falso (risposta, id_utente) VALUES ('$risposta', $id_utente)";
    $conn->query($sql);
    $conn->close();

    // Reindirizza alla prossima domanda
    header("Location: domanda_multipla.html");
    exit;
}
?>
