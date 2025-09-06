<?php
session_start();
require_once '../config.php';

// Verifica che l'utente sia loggato
if (!isset($_SESSION['utente_id'])) {
    header("Location: login.php");
    exit;
}

// Verifica che sia presidente o segretario
$tipo_utente = $_SESSION['tipo_utente'] ?? ''; // Usa ?? per sicurezza
if ($tipo_utente !== 'presidente' && $tipo_utente !== 'segretario') {
    die("Accesso negato. Solo Presidente e Segretario possono approvare utenti.");
}

// Approvazione utente
if (isset($_GET['approva'])) {
    $id_utente = (int)$_GET['approva']; // Cast a intero per sicurezza

    // Logica opzionale: il segretario potrebbe avere restrizioni diverse
    // Per ora, sia Presidente che Segretario possono approvare
    
    $stmt = $pdo->prepare("UPDATE utenti SET approvato = TRUE WHERE id = ?");
    if ($stmt->execute([$id_utente])) {
        // Messaggio di successo (opzionale)
        $_SESSION['message'] = "Utente approvato con successo.";
    } else {
        $_SESSION['error'] = "Errore durante l'approvazione dell'utente.";
    }
    
    // Reindirizza per evitare refresh e duplicati
    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Recupera utenti non approvati
$stmt = $pdo->query("SELECT id, nome, cognome, email, tipo_utente, icona FROM utenti WHERE approvato = FALSE ORDER BY created_at DESC");
$utenti = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Approvazione Utenti - A.S.D. Gi.Fra. Milazzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1976d2, #d32f2f);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
        }
        .container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            margin: 30px auto;
            padding: 30px;
            color: #333;
        }
        .header {
            background: linear-gradient(135deg, #1976d2, #d32f2f);
            color: white;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 30px;
            text-align: center;
        }
        .user-card {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            border-left: 5px solid #1976d2;
        }
        .btn-approve {
            background: linear-gradient(135deg, #28a745, #218838);
            color: white;
            border: none;
            padding: 8px 20px;
            border-radius: 20px;
            font-weight: bold;
            text-decoration: none; /* Per i link */
            display: inline-block;
        }
        .btn-approve:hover {
            background: linear-gradient(135deg, #218838, #1e7e34);
            transform: scale(1.05);
            color: white;
            text-decoration: none;
        }
        .user-icon {
            font-size: 1.5rem;
            margin-right: 10px;
        }
        .alert-custom {
            position: relative;
            padding: 1rem 1rem;
            margin-bottom: 1rem;
            border: 1px solid transparent;
            border-radius: 0.375rem;
        }
        .alert-success-custom {
            color: #0f5132;
            background-color: #d1e7dd;
            border-color: #badbcc;
        }
        .alert-danger-custom {
            color: #842029;
            background-color: #f8d7da;
            border-color: #f5c2c7;
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h2>Approvazione Utenti</h2>
        <p>Benvenuto, <?php echo htmlspecialchars($_SESSION['nome'] ?? 'Utente'); ?> (<?php echo ucfirst(htmlspecialchars($tipo_utente)); ?>)</p>
    </div>
    
    <?php if (isset($_SESSION['message'])): ?>
        <div class="alert-custom alert-success-custom">
            <strong><?php echo htmlspecialchars($_SESSION['message']); ?></strong>
        </div>
        <?php unset($_SESSION['message']); ?>
    <?php endif; ?>
    
    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert-custom alert-danger-custom">
            <strong><?php echo htmlspecialchars($_SESSION['error']); ?></strong>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <?php if (empty($utenti)): ?>
        <div class="alert alert-info text-center">
            <strong>Nessun utente in attesa di approvazione.</strong>
        </div>
    <?php else: ?>
        <h4>Utenti in attesa di approvazione:</h4>
        <?php foreach ($utenti as $utente): ?>
            <div class="user-card">
                <div class="row align-items-center">
                    <div class="col-md-1">
                        <span class="user-icon"><?php echo htmlspecialchars($utente['icona']); ?></span>
                    </div>
                    <div class="col-md-4">
                        <strong><?php echo htmlspecialchars($utente['cognome'] . ' ' . $utente['nome']); ?></strong><br>
                        <small><?php echo htmlspecialchars($utente['email']); ?></small>
                    </div>
                    <div class="col-md-3">
                        <span class="badge bg-primary"><?php echo ucfirst(htmlspecialchars($utente['tipo_utente'])); ?></span>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="?approva=<?php echo (int)$utente['id']; ?>" class="btn-approve" 
                           onclick="return confirm('Sei sicuro di voler approvare <?php echo htmlspecialchars($utente['nome'] . ' ' . $utente['cognome']); ?>?');">
                            Approva Utente
                        </a>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <div class="mt-4 text-center">
        <a href="../index.php" class="btn btn-primary">🏠 Torna alla Home</a>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</div>
</body>
</html>
