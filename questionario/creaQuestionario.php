<?php
session_start();
include 'php/conn_db.php';
$conn = connessione();

// Verifica se l'utente ha il ruolo di admin
if (!isset($_SESSION['user']) || $_SESSION['user']['ruolo'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Funzione per gestire l'inserimento di un nuovo questionario e le sue domande
function inserisciQuestionario($titolo, $domande) {
    global $conn;
    $stmt = $conn->prepare("INSERT INTO questionari (titolo) VALUES (?)");
    $stmt->bind_param("s", $titolo);
    $stmt->execute();
    $idQuestionario = $stmt->insert_id;
    $stmt->close();

    if (count($domande) > 0) {
        foreach ($domande as $domanda) {
            $tipo = $domanda['tipo'];
            $testo = $domanda['testo'];

            $stmt = $conn->prepare("INSERT INTO domande (testo_domanda, tipo_domanda) VALUES (?, ?)");
            $stmt->bind_param("ss", $testo, $tipo);
            $stmt->execute();
            $idDomanda = $stmt->insert_id;

            $stmt = $conn->prepare("INSERT INTO questionario_domande (id_questionario, id_domanda, tipo_domanda) VALUES (?, ?, ?)");
            $stmt->bind_param("iis", $idQuestionario, $idDomanda, $tipo);
            $stmt->execute();
            $stmt->close();
        }
    } else {
        // Rimuovi il questionario se non ha domande
        $stmt = $conn->prepare("DELETE FROM questionari WHERE id = ?");
        $stmt->bind_param("i", $idQuestionario);
        $stmt->execute();
        $stmt->close();
        echo "<script>alert('Impossibile creare un questionario senza domande.');</script>";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit_new_questionario'])) {
    $titolo = $_POST['titolo_questionario'];
    $domandeText = $_POST['domande'];
    $domandeType = $_POST['tipo_domanda'];

    $domande = [];
    foreach ($domandeText as $index => $text) {
        if (!empty($text) && isset($domandeType[$index])) {
            $domande[] = [
                'testo' => $text,
                'tipo' => $domandeType[$index]
            ];
        }
    }

    inserisciQuestionario($titolo, $domande);
}
?>


<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Aggiungi Questionario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Aggiungi un nuovo Questionario</h1>
    <form method="POST" action="">
        <div class="mb-3">
            <label for="titolo_questionario" class="form-label">Titolo del Questionario:</label>
            <input type="text" class="form-control" id="titolo_questionario" name="titolo_questionario" required>
        </div>
        <div id="domandeContainer">
            <h4>Domande</h4>
            <!-- Campi per le domande -->
            <div class="mb-3">
                <label for="domanda1" class="form-label">Testo della Domanda:</label>
                <input type="text" class="form-control" id="domanda1" name="domande[]" required>
                <select class="form-select mt-2" name="tipo_domanda[]">
                    <option value="aperta">Aperta</option>
                    <option value="vero_falso">Vero o Falso</option>
                </select>
            </div>
        </div>
        <button type="button" onclick="aggiungiDomanda()" class="btn btn-secondary mb-3">Aggiungi altra domanda</button>
        <button type="submit" name="submit_new_questionario" class="btn btn-primary">Crea Questionario</button>
       
    </form>
    <a href="dashboardAdmin.php" class="btn btn-secondary">Torna nella Dashboard</a>
    <a href="Php/logout.php" class="btn btn-danger">Esci</a>
</div>

<script>
    let domandaIndex = 2;
    function aggiungiDomanda() {
    let container = document.getElementById('domandeContainer');
    let newField = document.createElement('div');
    newField.classList.add('mb-3');
    newField.innerHTML = `
        <label for="domanda${domandaIndex}" class="form-label">Testo della Domanda:</label>
        <input type="text" class="form-control" name="domande[]" id="domanda${domandaIndex}" required>
        <select class="form-select mt-2" name="tipo_domanda[]">
            <option value="aperta">Aperta</option>
            <option value="vero_falso">Vero o Falso</option>
        </select>
    `;
    container.appendChild(newField);
    domandaIndex++;
}

</script>
</body>
</html>
