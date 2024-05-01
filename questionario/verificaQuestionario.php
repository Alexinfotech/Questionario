<?php
session_start();
require 'php/conn_db.php';

// Verifica se l'utente ha il ruolo di admin
if (!isset($_SESSION['user']) || $_SESSION['user']['ruolo'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$conn = connessione();

// Recuperare tutti gli utenti
$query_utenti = "SELECT id, nome, cognome FROM utenti";
$result_utenti = $conn->query($query_utenti);

$selectedUserId = $_POST['selected_user'] ?? null;

$questionari = [];
if (!empty($selectedUserId)) {
    $query_questionari = "SELECT aq.id_questionario, q.titolo, aq.data_completamento 
                          FROM assegnazioni_questionario aq
                          JOIN questionari q ON aq.id_questionario = q.id
                          WHERE aq.id_utente = ? AND aq.completato = 1";
    $stmt = $conn->prepare($query_questionari);
    $stmt->bind_param('i', $selectedUserId);
    $stmt->execute();
    $result_questionari = $stmt->get_result();
    $questionari = $result_questionari->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Verifica Risposte</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Verifica Risposte dei Questionari</h1>
    <form action="verificaQuestionario.php" method="POST">
        <div class="mb-3">
            <label for="selected_user" class="form-label">Seleziona Utente:</label>
            <select class="form-select" id="selected_user" name="selected_user" onchange="this.form.submit()">
                <option value="">Seleziona un utente</option>
                <?php foreach ($result_utenti as $utente): ?>
                    <option value="<?= htmlspecialchars($utente['id']) ?>" <?= $selectedUserId == $utente['id'] ? 'selected' : '' ?>>
                        <?= htmlspecialchars($utente['nome'] . ' ' . $utente['cognome']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
    </form>
    <?php if (!empty($questionari)): ?>
        <h2>Questionari completati:</h2>
        <ul>
            <?php foreach ($questionari as $questionario): ?>
                <li>
                    <?= htmlspecialchars($questionario['titolo']) ?> - Completato il: <?= htmlspecialchars($questionario['data_completamento']) ?>
                    <a href="mostraRisposte.php?id_questionario=<?= $questionario['id_questionario'] ?>&id_utente=<?= $selectedUserId ?>" class="btn btn-link">Visualizza Risposte</a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nessun questionario completato disponibile per questo utente.</p>
    <?php endif; ?>
    <a href="dashboardAdmin.php" class="btn btn-secondary">Torna nella Dashboard</a>
        <a href="Php/logout.php" class="btn btn-danger">Esci</a>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
