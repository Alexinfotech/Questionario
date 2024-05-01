<?php
session_start(); // Inizia la sessione per accedere alle variabili di sessione

// Distrugge la sessione
session_destroy(); // Distrugge tutti i dati associati alla sessione corrente

// Reindirizza l'utente alla pagina di login
header("Location: ../login.html"); // Assicurati che il percorso alla pagina di login sia corretto
exit; // Termina lo script per evitare che venga eseguito altro codice
?>
