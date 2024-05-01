<?php
session_start();
include 'db_connect.php';  // Include il file di connessione al database

$id_utente = $_SESSION['user_id'];

// Preparazione dell'output HTML
$output = "<ul>";

// Recupero risposte aperte
$sql = "SELECT risposta FROM risposte_aperte WHERE id_utente = $id_utente";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<li>Domanda aperta: " . htmlspecialchars($row['risposta']) . "</li>";
    }
}

// Recupero risposte vero/falso
$sql = "SELECT risposta FROM risposte_vero_falso WHERE id_utente = $id_utente";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<li>Domanda Vero/Falso: " . htmlspecialchars($row['risposta']) . "</li>";
    }
}

// Recupero risposte multiple
$sql = "SELECT testo_risposta FROM risposte_multipla WHERE id_utente = $id_utente";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= "<li>Domanda Multipla: " . htmlspecialchars($row['testo_risposta']) . "</li>";
    }
}

$output .= "</ul>";
$conn->close();

echo $output;
?>
