<?php
session_start();
include 'config.php';

// Recupera la stagione attiva
$stmt = $pdo->query("SELECT anno_inizio, anno_fine FROM stagioni WHERE attiva = TRUE LIMIT 1");
$stagione_attiva = $stmt->fetch(PDO::FETCH_ASSOC);
$stagione_label = $stagione_attiva ? $stagione_attiva['anno_inizio'] . '/' . $stagione_attiva['anno_fine'] : 'Nessuna';
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>A.S.D. Gi.Fra. Milazzo - Gestione Sportiva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
</head>

<body>
<header class="bg-light py-3 border-bottom">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-6">
                <img src="../img/logo.png" alt="Logo Gi.Fra. Milazzo" class="logo">
                <h1 class="brand-title mb-0">A.S.D. GI.FRA. MILAZZO</h1>
                <p class="season-text mb-0">Stagione Attiva: <?php echo $stagione_label; ?></p>
            </div>
            <div class="col-md-6 text-end">
                <?php if (isset($_SESSION['utente_id'])): ?>
                    <span class="text-muted me-3">Benvenuto, <?php echo htmlspecialchars($_SESSION['nome']); ?></span>
                    <a href="../auth/logout.php" class="btn btn-sm btn-outline-primary">Logout</a>
                <?php else: ?>
                    <a href="../auth/login.php" class="btn btn-sm btn-primary">Login</a>
                <?php endif; ?>
            </div>
        </div>
    </div>
</header>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            <i class="bi bi-house"></i> Home
        </a>
        <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['utente_id'])): ?>
                <span class="navbar-text me-3">Benvenuto, <?php echo htmlspecialchars($_SESSION['nome']); ?></span>
                <a class="btn btn-outline-light btn-sm" href="../auth/logout.php">Logout</a>
            <?php else: ?>
                <a class="btn btn-outline-light btn-sm" href="../auth/login.php">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-4">
