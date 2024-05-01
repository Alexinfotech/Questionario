<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
include 'conn_db.php';
$conn = connessione(); // Assumi che questa funzione instauri una connessione al database

header('Content-Type: application/json'); // Imposta l'header per la risposta JSON

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['password'])) {
    // Preparazione della query SQL con parametri
    $stmt = $conn->prepare("SELECT id, nome, cognome, email, ruolo FROM utenti WHERE email = ? AND password = ?");
    $stmt->bind_param("ss", $_POST['email'], $_POST['password']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $_SESSION['user'] = $user; // Salva l'utente in sessione
        
        // Restituisce un JSON di successo includendo il ruolo dell'utente
        echo json_encode(['success' => true, 'role' => $user['ruolo']]);
    } else {
        // Restituisce un JSON di errore per email o password invalidi
        echo json_encode(['success' => false, 'message' => 'Email o password non validi']);
    }

    $stmt->close();
} else {
    // Restituisce un JSON di errore se non sono stati inviati dati
    echo json_encode(['success' => false, 'message' => 'Nessun dato inviato']);
}

$conn->close();
?>
