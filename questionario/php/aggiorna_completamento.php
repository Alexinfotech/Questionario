<?php
session_start();
require_once 'conn_db.php';

// Verifica se l'utente è loggato
if (!isset($_SESSION['user'])) {
    echo "Utente non autenticato";
    exit;
}

// Verifica se è stato passato l'ID del questionario da aggiornare
if (!isset($_GET['questionario_id'])) {
    echo "ID del questionario mancante";
    exit;
}

$questionario_id = $_GET['questionario_id'];
$user_id = $_SESSION['user']['id'];

// Stampa in console i dati per il debug
echo "ID utente: " . $user_id . "\n";
echo "ID questionario: " . $questionario_id . "\n";

// Connessione al database
$conn = connessione();

// Query per aggiornare lo stato di completamento del questionario
$query_update = "UPDATE assegnazioni_questionario SET completato = 1 WHERE id_utente = ? AND id_questionario = ?";
$stmt_update = $conn->prepare($query_update);

if ($stmt_update) {
    $stmt_update->bind_param("ii", $user_id, $questionario_id);
    if (!$stmt_update->execute()) {
        // Log e gestione dell'errore
        error_log("Errore nell'aggiornamento dello stato di completamento: " . $stmt_update->error);
        echo "Errore nell'aggiornamento dello stato di completamento del questionario: " . $stmt_update->error;
    } else {
        echo "Stato di completamento del questionario aggiornato con successo";
    }
    $stmt_update->close();
} else {
    // Log e gestione dell'errore
    error_log("Errore nella preparazione della query di aggiornamento: " . $conn->error);
    echo "Errore nella preparazione della query di aggiornamento: " . $conn->error;
}

// Chiudi la connessione al database
$conn->close();
?>
