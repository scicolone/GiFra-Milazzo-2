<?php
session_start();
// Percorso corretto per includere config.php dalla root
require_once '../config.php'; // ← questo punta alla root

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM utenti WHERE email = ? AND approvato = TRUE");
    $stmt->execute([$email]);
    $utente = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($utente && password_verify($password, $utente['password'])) {
        $_SESSION['utente_id'] = $utente['id'];
        $_SESSION['nome'] = $utente['nome'];
        $_SESSION['ruolo'] = $utente['ruolo'];
        header("Location: ../index.php");
        exit;
    } else {
        $error = "Credenziali non valide o utente non approvato.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - A.S.D. Gi.Fra. Milazzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="login-container">
    <h2 class="text-center">Login</h2>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary w-100">Accedi</button>
    </form>
    <div class="mt-3 text-center">
        <a href="registrazione.php">Non hai un account? Registrati</a>
    </div>
</div>
</body>
</html>
