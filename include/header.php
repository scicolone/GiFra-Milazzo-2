<?php
session_start();
// Verifica che config.php esista e lo include
if (!file_exists(dirname(__DIR__) . '/config.php')) {
    die("Errore critico: file config.php non trovato nella root del progetto.");
}
include dirname(__DIR__) . '/config.php';

// Recupera la stagione attiva
$stmt = $pdo->query("SELECT anno_inizio, anno_fine FROM stagioni WHERE attiva = TRUE LIMIT 1");
$stagione_attiva = $stmt->fetch(PDO::FETCH_ASSOC);
$stagione_label = $stagione_attiva ? $stagione_attiva['anno_inizio'] . '/' . $stagione_attiva['anno_fine'] : 'Nessuna';
?>
<!DOCTYPE html>
<html lang="it">
    <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['utente_id'])): ?>
                <span class="navbar-text me-3">Benvenuto, <?php echo htmlspecialchars($_SESSION['nome']); ?></span>
                <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            <?php else: ?>
                <a href="../auth/login.php" class="btn btn-outline-light btn-sm">Login</a>
            <?php endif; ?>
</div>
<head>
    <meta charset="UTF-8">
    <title>A.S.D. Gi.Fra. Milazzo - Gestione Sportiva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>
<body>
<header>
    <div class="container">
        <img src="img/logo.png" alt="Logo Gi.Fra. Milazzo" class="logo">
        <h1>A.S.D. GI.FRA. MILAZZO</h1>
        <p class="season-text mb-0">Stagione Attiva: <?php echo $stagione_label; ?></p>
    </div>
</header>
<div class="container">
