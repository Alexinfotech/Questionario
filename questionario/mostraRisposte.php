<?php
session_start();
require 'php/conn_db.php';

if (!isset($_SESSION['user']) || $_SESSION['user']['ruolo'] !== 'admin') {
    header("Location: login.html");
    exit;
}

$conn = connessione();

$id_questionario = $_GET['id_questionario'] ?? 0;
$id_utente = $_GET['id_utente'] ?? 0;

function getRisposte($conn, $query) {
    $result = $conn->query($query);
    if (!$result) {
        echo "Errore nell'esecuzione della query: " . $conn->error;
        return [];
    }
    return $result->fetch_all(MYSQLI_ASSOC);
}

$query_domande_associate = "SELECT d.* FROM domande d INNER JOIN questionario_domande qd ON d.id = qd.id_domanda WHERE qd.id_questionario = $id_questionario";
$domande_associate = getRisposte($conn, $query_domande_associate);

$query_aperte = "SELECT * FROM risposte_aperte WHERE id_questionario = $id_questionario AND id_utente = $id_utente";
$risposte_aperte = getRisposte($conn, $query_aperte);

$query_vf = "SELECT * FROM risposte_vero_falso WHERE id_questionario = $id_questionario AND id_utente = $id_utente";
$risposte_vf = getRisposte($conn, $query_vf);

?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Risposte al Questionario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Risposte al Questionario</h1>
    
    <h2>Domande Associate al Questionario</h2>
    <?php if (!empty($domande_associate)): ?>
        <ul>
            <?php foreach ($domande_associate as $domanda): ?>
                <li><?= htmlspecialchars($domanda['testo_domanda']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nessuna domanda associata trovata per questo questionario.</p>
    <?php endif; ?>

    <h2>Risposte Aperte</h2>
    <?php if (!empty($risposte_aperte)): ?>
        <ul>
            <?php foreach ($risposte_aperte as $risposta): ?>
                <li><strong>Risposta:</strong> <?= htmlspecialchars($risposta['risposta']) ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nessuna risposta aperta trovata per questo questionario.</p>
    <?php endif; ?>

    <h2>Risposte Vero/Falso</h2>
    <?php if (!empty($risposte_vf)): ?>
        <ul>
            <?php foreach ($risposte_vf as $risposta): ?>
                <li><strong>Risposta:</strong> <?= htmlspecialchars($risposta['risposta'] === 'vero' ? 'Vero' : 'Falso') ?></li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>Nessuna risposta vero/falso trovata per questo questionario.</p>
    <?php endif; ?>
   <!-- <a href="javascript:history.back()" class="btn btn-secondary">Torna indietro</a>-->

    <a href="dashboardAdmin.php" class="btn btn-secondary">Torna nella Dashboard</a>
        <a href="Php/logout.php" class="btn btn-danger">Esci</a>
</div>


        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
