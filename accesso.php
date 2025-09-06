<?php
session_start();
// Non avviamo sessione qui, la gestiamo dopo il login
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Accesso - A.S.D. Gi.Fra. Milazzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1976d2, #d32f2f);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
        }
        .access-container {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
            padding: 40px;
            color: #333;
            max-width: 500px;
            margin: 0 auto;
            text-align: center;
        }
        .btn-access {
            background: linear-gradient(135deg, #1976d2, #d32f2f);
            color: white;
            border: none;
            padding: 15px 30px;
            font-weight: bold;
            margin: 10px;
            border-radius: 50px;
            font-size: 1.1rem;
        }
        .btn-access:hover {
            background: linear-gradient(135deg, #1565c0, #c62828);
            transform: scale(1.05);
        }
        .logo {
            width: 120px;
            height: auto;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="access-container">
    <img src="img/logo.png" alt="Logo Gi.Fra. Milazzo" class="logo">
    <h2>A.S.D. GI.FRA. MILAZZO</h2>
    <p class="lead">Gestione Sportiva</p>
    <div class="mt-4">
        <?php if (isset($_SESSION['access_denied_reason']) && $_SESSION['access_denied_reason'] === 'role' && isset($_SESSION['utente_id'])): ?>
            <!-- Area del messaggio di accesso negato (righe 66-72) -->
            <div style="background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; border-radius: 5px; padding: 15px; margin-bottom: 20px;">
                <h4 style="margin-top: 0; color: #d32f2f;">Accesso Negato</h4>
                <p><strong>Solo Presidente e Segretario possono accedere alla gestione completa.</strong></p>
                <p>Il tuo account è stato registrato e approvato. Le funzionalità specifiche per il tuo ruolo saranno disponibili a breve.</p>
                <a href="auth/logout.php" style="display: inline-block; background-color: #dc3545; color: white; padding: 8px 16px; border-radius: 5px; text-decoration: none; margin-top: 10px;">🚪 Logout</a>
            </div>
            <?php
            // Cancella la variabile di sessione dopo averla usata
            unset($_SESSION['access_denied_reason']);
            ?>
        <?php else: ?>
            <p><strong>Benvenuto nel sistema di gestione dell'A.S.D. Gi.Fra. Milazzo</strong></p>
            <p>Accedi o registrati per continuare</p>
        <?php endif; ?>
    </div>
    <div class="mt-4">
        <a href="auth/login.php" class="btn-access">🔑 Accedi</a>
        <a href="auth/registrazione.php" class="btn-access">📝 Registrati</a>
    </div>
    <div class="mt-4">
        <small class="text-muted">
            Solo Presidente e Segretario possono accedere alla gestione completa.<br>
            Altri utenti registrati avranno accesso alle funzioni specifiche.
        </small>
    </div>
</div>
</body>
</html>
