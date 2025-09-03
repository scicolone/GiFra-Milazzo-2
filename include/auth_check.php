<?php
session_start();

// Funzione per verificare se l'utente è loggato
function checkLogin() {
    if (!isset($_SESSION['utente_id'])) {
        header("Location: auth/login.php");
        exit;
    }
}

// Funzione per verificare il ruolo
function checkRole($allowed_roles) {
    if (!isset($_SESSION['utente_id'])) {
        header("Location: auth/login.php");
        exit;
    }
    
    $user_role = $_SESSION['ruolo'];
    if (!in_array($user_role, $allowed_roles)) {
        die("Accesso negato. Ruolo non autorizzato.");
    }
}

// Funzione per verificare se è presidente o segretario
function checkPresidenteSegretario() {
    if (!isset($_SESSION['utente_id'])) {
        header("Location: auth/login.php");
        exit;
    }
    
    $user_role = $_SESSION['ruolo'];
    if ($user_role !== 'presidente' && $user_role !== 'segretario') {
        die("Accesso negato. Solo Presidente e Segretario possono accedere.");
    }
}
?>
