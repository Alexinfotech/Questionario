<?php
session_start();
require_once 'conn_db.php';

$conn = connessione();
$response = ['success' => false, 'message' => 'Errore generico'];

if (!isset($_SESSION['user'])) {
    $response['message'] = "Utente non autenticato.";
    echo json_encode($response);
    exit;
}

$user_id = $_SESSION['user']['id'];
$questionario_id = $_POST['questionario_id'] ?? null;

if (!$questionario_id) {
    $response['message'] = "ID del questionario mancante";
    echo json_encode($response);
    exit;
}

$allInsertsSuccessful = true;

foreach ($_POST as $key => $value) {
    if (strpos($key, 'risposta_vero_falso_') === 0 || strpos($key, 'risposta_aperta_') === 0) {
        $domandaId = substr($key, strrpos($key, '_') + 1);
        $tipoDomanda = $_POST["tipo_domanda_$domandaId"];
        $query = $tipoDomanda === 'vero_falso' ?
            "INSERT INTO risposte_vero_falso (risposta, id_utente, id_questionario, id_domanda, data_risposta) VALUES (?, ?, ?, ?, NOW())" :
            "INSERT INTO risposte_aperte (risposta, id_utente, id_questionario, id_domanda, data_risposta) VALUES (?, ?, ?, ?, NOW())";

        $stmt = $conn->prepare($query);
        if ($stmt) {
            $stmt->bind_param("siii", $value, $user_id, $questionario_id, $domandaId);
            if (!$stmt->execute()) {
                $allInsertsSuccessful = false;
                $response['message'] = "Errore nell'inserimento della risposta: " . $stmt->error;
            }
            $stmt->close();
        } else {
            $allInsertsSuccessful = false;
            $response['message'] = "Errore nella preparazione della query: " . $conn->error;
        }
    }
}

// Assumi che $user_id e $questionario_id siano già definiti e validati
$query_update_completato = "UPDATE assegnazioni_questionario SET completato = 1, data_completamento = NOW() WHERE id_utente = ? AND id_questionario = ?";
$stmt_update_completato = $conn->prepare($query_update_completato);
if ($stmt_update_completato) {
    error_log("Aggiornamento questionario: user_id = $user_id, questionario_id = $questionario_id");

    $stmt_update_completato->bind_param("ii", $user_id, $questionario_id);
    if (!$stmt_update_completato->execute()) {
        error_log("Errore nell'aggiornamento dello stato di completamento: " . $stmt_update_completato->error);
    }
    $stmt_update_completato->close();
} else {
    error_log("Errore nella preparazione della query di aggiornamento completamento: " . $conn->error);
}

$conn->close();
echo json_encode($response);
?>
