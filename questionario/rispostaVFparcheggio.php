<?php
session_start();
include 'db_connect.php';

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['multipla'])) {
    $id_utente = $_SESSION['user_id'];
    foreach ($_POST['multipla'] as $risposta) {
        $risposta = $conn->real_escape_string($risposta);
        // Salva ogni risposta nel database
        $sql = "INSERT INTO risposte_multipla (id_domanda, testo_risposta, corretta, id_utente) VALUES (1, '$risposta', 1, $id_utente)"; // Assume domanda ID=1 e risposte corrette
        $conn->query($sql);
    }
    $conn->close();

    // Reindirizza alla pagina di riepilogo
    header("Location: riepilogo_risposte.html");
    exit;
}
?>
