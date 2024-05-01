
<?php
session_start(); // Inizia la sessione
require 'php/conn_db.php'; // Include il file di connessione al database

// Controlla se l'utente è loggato verificando l'esistenza di una variabile di sessione specifica
if (!isset($_SESSION['user'])) {
    header("Location: login.html"); // Reindirizza al login se non autenticato
    exit;
}

$conn = connessione(); // Chiama la funzione di connessione al database
$user_id = $_SESSION['user']['id']; // Prende l'ID dell'utente dalla sessione

// Prepara la query per ottenere i questionari non completati
$query = "SELECT aq.id_questionario, q.titolo, aq.completato 
          FROM assegnazioni_questionario aq
          JOIN questionari q ON aq.id_questionario = q.id
          WHERE aq.id_utente = ? AND aq.completato = 0"; // Solo i questionari non completati
$stmt = $conn->prepare($query);
if (!$stmt) {
    error_log("Errore nella preparazione della query: " . $conn->error);
    exit;
}

$stmt->bind_param("i", $user_id); // Associa l'ID dell'utente alla query
$stmt->execute(); // Esegue la query
$result = $stmt->get_result(); // Ottiene il risultato della query

if ($result->num_rows == 0) {
    $has_questionnaires = false; // Imposta a false se non ci sono questionari
} else {
    $has_questionnaires = true; // Imposta a true se ci sono questionari
    $questionnaires = $result->fetch_all(MYSQLI_ASSOC); // Recupera tutti i questionari
}

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Dashboard</h1>
        <div class="mt-4">
    <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Benvenuto!</h4>
            <p><strong><?= htmlspecialchars($_SESSION['user']['nome']) ?> <?= htmlspecialchars($_SESSION['user']['cognome']) ?></strong></p>
            <p>Email: <strong><?= htmlspecialchars($_SESSION['user']['email']) ?></strong> &nbsp; 
            <!--Id: <?= htmlspecialchars($_SESSION['user']['id']) ?><br>-->
            Ruolo: <strong><?= htmlspecialchars($_SESSION['user']['ruolo']) ?></strong></p>
            <?php if ($has_questionnaires): ?>
                <?php foreach ($questionnaires as $questionnaire): ?>
                    <a href="questionario.php?id=<?= htmlspecialchars($questionnaire['id_questionario']) ?>&user_id=<?= htmlspecialchars($user_id) ?>" class="btn btn-primary"><?= htmlspecialchars($questionnaire['titolo']) ?></a>
                    <!-- Aggiungi data attribute per l'ID del questionario -->
                    <span data-questionario-id="<?= htmlspecialchars($questionnaire['id_questionario']) ?>"></span>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Non hai nessun questionario da compilare al momento.</p>
            <?php endif; ?>
            <a href="Php/logout.php" class="btn btn-danger">Esci</a>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script per stampare in console gli ID dei questionari
        document.addEventListener('DOMContentLoaded', function() {
            const ids = document.querySelectorAll('[data-questionario-id]');
            ids.forEach(function(item) {
                console.log('ID Questionario:', item.getAttribute('data-questionario-id'));
            });
        });
    </script>
</body>
</html>
