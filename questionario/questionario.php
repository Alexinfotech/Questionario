<?php
session_start();
require_once 'php/conn_db.php';

$conn = connessione();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit;
}

$user_id = $_SESSION['user']['id'];

// Recupera l'ID del questionario assegnato all'utente corrente
$query_questionario = "SELECT id_questionario FROM assegnazioni_questionario WHERE id_utente = ?";
$stmt_questionario = $conn->prepare($query_questionario);

if (!$stmt_questionario) {
    echo "Errore nella preparazione della query di assegnazione: " . $conn->error;
    $conn->close();
    exit;
}

$stmt_questionario->bind_param("i", $user_id);
$stmt_questionario->execute();
$result_questionario = $stmt_questionario->get_result();

if ($result_questionario->num_rows == 0) {
    echo "<p>Non ci sono questionari assegnati a questo utente.</p>";
    $conn->close();
    exit;
}

$row_questionario = $result_questionario->fetch_assoc();
$questionario_id = $row_questionario['id_questionario'];
$stmt_questionario->close();

// Ottieni l'ID del questionario dall'URL
$questionario_id_form = $_GET['id'];

// Verifica se l'ID del questionario dal form è stato fornito
if (!isset($questionario_id_form) || !is_numeric($questionario_id_form)) {
    echo "<p>Errore: ID del questionario non valido.</p>";
    exit;
}

// Recupera tutte le domande per il questionario specifico
$query_questions = "SELECT qd.id, d.testo_domanda, d.tipo_domanda
                    FROM questionario_domande qd
                    JOIN domande d ON qd.id_domanda = d.id
                    WHERE qd.id_questionario = ?";
$stmt_questions = $conn->prepare($query_questions);

if (!$stmt_questions) {
    echo "Errore nella preparazione della query delle domande: " . $conn->error;
    $conn->close();
    exit;
}

$stmt_questions->bind_param("i", $questionario_id_form);
$stmt_questions->execute();
$result_questions = $stmt_questions->get_result();

if ($result_questions === false || $result_questions->num_rows == 0) {
    echo "<p>Non ci sono domande disponibili per questo questionario.</p>";
    $conn->close();
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Questionario</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Questionario</h1>
    <form id="questionarioForm" method="POST">
        <input type="hidden" name="questionario_id" value="<?php echo htmlspecialchars($questionario_id_form); ?>">
        <?php while ($row = $result_questions->fetch_assoc()) {
            $domandaId = htmlspecialchars($row['id']);
            $testoDomanda = htmlspecialchars($row['testo_domanda']);
            $tipoDomanda = $row['tipo_domanda'];
            
            echo "<div class='mb-4'>";
            echo "<h2>$testoDomanda</h2>";
            echo "<input type='hidden' name='tipo_domanda_{$domandaId}' value='{$tipoDomanda}'>";
            
            if ($tipoDomanda === 'vero_falso') {
                echo "<div class='form-check'><input class='form-check-input' type='radio' name='risposta_vero_falso_{$domandaId}' value='vero' id='vero_{$domandaId}'><label class='form-check-label' for='vero_{$domandaId}'>Vero</label></div>";
                echo "<div class='form-check'><input class='form-check-input' type='radio' name='risposta_vero_falso_{$domandaId}' value='falso' id='falso_{$domandaId}'><label class='form-check-label' for='falso_{$domandaId}'>Falso</label></div>";
            } else {
                echo "<textarea class='form-control' name='risposta_aperta_{$domandaId}' id='risposta_aperta_{$domandaId}'></textarea>";
            }
            
            echo "</div>";
        } ?>
<button type="submit" id="inviaRisposte" class="btn btn-primary">Invia Risposte</button>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.getElementById("inviaRisposte").addEventListener("click", function() {
    var formData = new FormData(document.getElementById("questionarioForm"));
    
    // Invia le risposte a invioRisposte.php
    fetch("php/invioRisposte.php", {
        method: "POST",
        body: formData
    }).then(response => {
        if (!response.ok) {
            throw new Error("Errore nell'invio delle risposte");
        }
        return response.text();
    }).then(data => {
        console.log(data); // Puoi gestire la risposta dal server qui
       window.location.href = "saluti.html"; // Redirect to a thank you page after submitting
    }).catch(error => {
        console.error(error);
    });
});

</script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var questionarioId = document.getElementById('questionarioForm').querySelector('[name="questionario_id"]').value;
    console.log('ID del questionario: ' + questionarioId);
});
</script>

</body>
</html>
