<?php
session_start();

// Verifica se l'utente ha il ruolo di admin
if (!isset($_SESSION['user']) || $_SESSION['user']['ruolo'] !== 'admin') {
    header('Location: login.php'); // Reindirizza al login se non autorizzato
    exit();
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dashboard Amministratore</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
</head>
<body>
<div class="container mt-5">
    <h1>Dashboard Amministratore</h1>
    <div class="mt-4">
    <div class="alert alert-success" role="alert">
            <h4 class="alert-heading">Benvenuto!</h4>
            <p><strong><?= htmlspecialchars($_SESSION['user']['nome']) ?> <?= htmlspecialchars($_SESSION['user']['cognome']) ?></strong></p>
            <p>Email: <strong><?= htmlspecialchars($_SESSION['user']['email']) ?></strong> &nbsp; 
            <!--Id: <?= htmlspecialchars($_SESSION['user']['id']) ?><br>-->
            Ruolo: <strong><?= htmlspecialchars($_SESSION['user']['ruolo']) ?></strong></p>
        <a href="creaQuestionario.php" class="btn btn-primary">Crea Questionario</a>
        <a href="assegnaQuestionario.php" class="btn btn-secondary">Assegna Questionario</a>
        <a href="verificaQuestionario.php" class="btn btn-secondary">Verifica Questionario</a>
        <a href="Php/logout.php" class="btn btn-danger">Esci</a>

    </div>
</div>
</body>
</html>
