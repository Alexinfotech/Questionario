<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
session_start();
include 'conn_db.php';
$conn = connessione();

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['email'], $_POST['password'])) {
    $check_stmt = $conn->prepare("SELECT id FROM utenti WHERE email = ?");
    $check_stmt->bind_param("s", $_POST['email']);
    $check_stmt->execute();
    $check_result = $check_stmt->get_result();

    if ($check_result->num_rows > 0) {
        // Email già registrata, reindirizza a registrazione.php con il messaggio di errore
        header("Location: ../registrazione.php?error_message=Email%20gi%C3%A0%20registrata");
        exit();
    } else {
        $insert_stmt = $conn->prepare("INSERT INTO utenti (nome, cognome, email, password) VALUES (?, ?, ?, ?)");
        $insert_stmt->bind_param("ssss", $_POST['nome'], $_POST['cognome'], $_POST['email'], $_POST['password']);

        if ($insert_stmt->execute()) {
            // Successo, reindirizza a una pagina di successo o a dove desideri
            header("Location: ../login.html");
            exit();
        } else {
            // Errore durante l'inserimento, reindirizza a registrazione.php con il messaggio di errore
            header("Location: ../registrazione.php?error_message=Errore%20durante%20la%20registrazione");
            exit();
        }

        $insert_stmt->close();
    }

    $check_stmt->close();
} else {
    // Nessun dato inviato, reindirizza a registrazione.php con il messaggio di errore
    header("Location: ../registrazione.php?error_message=Nessun%20dato%20inviato");
    exit();
}

$conn->close();
?>
