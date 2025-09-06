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
        <p><strong>Benvenuto nel sistema di gestione dell'A.S.D. Gi.Fra. Milazzo</strong></p>
        <p>Accedi o registrati per continuare</p>
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
        <script>
// Reindirizza alla home dopo 7 secondi
setTimeout(function() {
    window.location.href = '../index.php';
}, 7000);
</script>
    </div>
</div>
</body>
</html>
