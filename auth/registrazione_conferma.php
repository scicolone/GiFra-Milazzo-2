<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione Completata - A.S.D. Gi.Fra. Milazzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #1976d2, #d32f2f);
            color: white;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            background: rgba(255, 255, 255, 0.95);
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.3);
        }
        .success-message {
            background-color: #e8f5e8;
            color: #2e7d32;
            padding: 20px;
            border-radius: 10px;
            text-align: center;
            margin-bottom: 30px;
            font-size: 1.1rem;
        }
        .btn-home {
            background-color: #1976d2;
            color: white;
            border: none;
            padding: 12px 30px;
            border-radius: 50px;
            font-weight: bold;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-home:hover {
            background-color: #1565c0;
            transform: scale(1.05);
        }
        h2 {
            text-align: center;
            color: #1976d2;
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Registrazione Completata</h2>
    
    <div class="success-message">
        <p><strong>Registrazione completata.</strong> In attesa di approvazione da parte del Presidente o del Segretario.</p>
        <p>Sei stato reindirizzato alla Home Page tra 7 secondi...</p>
    </div>

    <div class="text-center mt-4">
        <a href="../index.php" class="btn-home">Torna alla Home</a>
    </div>
</div>

<script>
// Reindirizza alla home dopo 7 secondi
setTimeout(function() {
    window.location.href = '../index.php';
}, 7000);
</script>
</body>
</html>
