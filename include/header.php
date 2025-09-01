<?php
session_start();
require_once dirname(__DIR__) . '/config.php';

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
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background-color: #1976d2;
            color: white;
            text-align: center;
            padding: 30px 0;
            border-bottom: 2px solid #d32f2f;
        }
        .logo {
            width: 180px;
            height: auto;
            display: block;
            margin: 0 auto 15px;
        }
        .brand-title {
            font-size: 2rem;
            color: #d32f2f;
            margin: 0;
            font-weight: bold;
        }
        .season-text {
            color: #eee;
            font-size: 0.9rem;
            margin: 5px 0 0;
        }
        .navbar {
            background-color: #1976d2 !important;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        .btn-primary {
            background-color: #d32f2f;
            border-color: #d32f2f;
        }
        .btn-primary:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>
<header class="header">
    <img src="../img/logo.png" alt="Logo Gi.Fra. Milazzo" class="logo">
    <h1 class="brand-title mb-0">A.S.D. GI.FRA. MILAZZO</h1>
    <p class="season-text mb-0">Stagione Attiva: <?php echo $stagione_label; ?></p>
</header>

<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="../index.php">
            🏠 Home
        </a>
        <div class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['utente_id'])): ?>
                <span class="navbar-text me-3">Benvenuto, <?php echo htmlspecialchars($_SESSION['nome']); ?></span>
                <a href="../auth/logout.php" class="btn btn-outline-light btn-sm">Logout</a>
            <?php else: ?>
                <a href="../auth/login.php" class="btn btn-outline-light btn-sm">Login</a>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-4">
