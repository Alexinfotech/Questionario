<?php
session_start();
include 'php/conn_db.php';
$conn = connessione();

// Verifica se l'utente ha il ruolo di admin
if (!isset($_SESSION['user']) || $_SESSION['user']['ruolo'] !== 'admin') {
    header('Location: login.php');
    exit();
}

// Recuperare tutti gli utenti
$query_utenti = "SELECT id, nome, cognome FROM utenti";
$result_utenti = $conn->query($query_utenti);

// Recuperare tutti i questionari
$query_questionari = "SELECT id, titolo FROM questionari";
$result_questionari = $conn->query($query_questionari);

// Preparare un array per escludere i questionari già assegnati
$questionari_esclusi = [];
$query_assegnazioni = "SELECT DISTINCT id_questionario FROM assegnazioni_questionario";
$result_assegnazioni = $conn->query($query_assegnazioni);
while ($row = $result_assegnazioni->fetch_assoc()) {
    $questionari_esclusi[] = $row['id_questionario'];
}

// Gestione del submit del form
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $idUtente = $_POST['id_utente'];
    $idQuestionario = $_POST['id_questionario'];
    $stmt = $conn->prepare("INSERT INTO assegnazioni_questionario (id_utente, id_questionario) VALUES (?, ?)");
    $stmt->bind_param("ii", $idUtente, $idQuestionario);
    $stmt->execute();
    $stmt->close();
    echo "<script>alert('Questionario assegnato correttamente.');</script>";
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Assegna Questionario</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Assegna Questionario agli Utenti</h1>
    <form method="POST">
        <div class="mb-3">
            <label for="id_utente" class="form-label">Seleziona Utente:</label>
            <select class="form-select" id="id_utente" name="id_utente" required>
                <?php while ($row = $result_utenti->fetch_assoc()): ?>
                    <option value="<?= $row['id'] ?>"><?= $row['nome'] ?> <?= $row['cognome'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
        <div class="mb-3">
            <label for="id_questionario" class="form-label">Seleziona Questionario:</label>
            <select class="form-select" id="id_questionario" name="id_questionario" required>
                <?php while ($row = $result_questionari->fetch_assoc()): ?>
                    <?php if (!in_array($row['id'], $questionari_esclusi)): ?>
                        <option value="<?= $row['id'] ?>"><?= $row['titolo'] ?></option>
                    <?php endif; ?>
                <?php endwhile; ?>
            </select>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Assegna Questionario</button>
        <a href="dashboardAdmin.php" class="btn btn-secondary">Torna nella Dashboard</a>
        <a href="Php/logout.php" class="btn btn-danger">Esci</a>
    </form>
</div>
</body>
</html>
