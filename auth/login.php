<?php
session_start();
require_once '../config.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Verifica utente approvato
    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE email = ? AND approvato = TRUE");
    $stmt->execute([$email]);
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utente && password_verify($password, $utente['password'])) {
        $_SESSION['utente_id'] = $utente['id'];
        $_SESSION['nome'] = $utente['nome'];
        $_SESSION['tipo_utente'] = $utente['tipo_utente']; // Usa il campo tipo_utente
        
        // Reindirizza in base al tipo_utente
        if ($utente['tipo_utente'] === 'presidente' || $utente['tipo_utente'] === 'segretario') {
            header("Location: ../index.php"); // Home page completa
        } else {
            // Per ora reindirizza alla home, ma puoi creare pagine specifiche
            header("Location: ../index.php"); // Da personalizzare per ogni tipo di utente
        }
        exit;
    } else {
        $error = "Credenziali non valide o utente non approvato.";
    }
}
?>
