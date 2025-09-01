<?php
session_start();
require_once '../config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $luogo_nascita = $_POST['luogo_nascita'];
    $provincia_nascita = $_POST['provincia_nascita'];
    $data_nascita = $_POST['data_nascita'];
    $sesso = $_POST['sesso'];
    $comune_residenza = $_POST['comune_residenza'];
    $provincia_residenza = $_POST['provincia_residenza'];
    $cap = $_POST['cap'];
    $via_piazza = $_POST['via_piazza'];
    $numero_civico = $_POST['numero_civico'];
    $cittadinanza = $_POST['cittadinanza'];
    $telefono = $_POST['telefono'];
    $cellulare_madre = $_POST['cellulare_madre'];
    $cellulare_padre = $_POST['cellulare_padre'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo_utente = $_POST['tipo_utente'];

    // Icone per tipo utente
    $icone = [
        'segretario' => '👩‍💼',
        'cassiere' => '💰',
        'dirigente' => '👨‍💼',
        'socio' => '👥',
        'allenatore' => '🏃‍♂️',
        'genitore' => '👨‍👩‍👧‍👦'
    ];
    $icona = $icone[$tipo_utente] ?? '👤';

    // Verifica se email già esiste
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utenti WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $error = "Email già registrata.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO utenti 
            (nome, cognome, luogo_nascita, provincia_nascita, data_nascita, sesso, comune_residenza, 
             provincia_residenza, cap, via_piazza, numero_civico, cittadinanza, telefono, cellulare_madre, 
             cellulare_padre, email, password, tipo_utente, icona, approvato) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE)
        ");
        $stmt->execute([
            $nome, $cognome, $luogo_nascita, $provincia_nascita, $data_nascita, $sesso,
            $comune_residenza, $provincia_residenza, $cap, $via_piazza, $numero_civico,
            $cittadinanza, $telefono, $cellulare_madre, $cellulare_padre, $email,
            $password, $tipo_utente, $icona
        ]);
        $success = "Registrazione completata. In attesa di approvazione.";
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - A.S.D. Gi.Fra. Milazzo</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .register-container {
            max-width: 600px;
            margin: 100px auto;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .form-label {
            font-weight: bold;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #218838;
        }
        .btn-success:hover {
            background-color: #218838;
        }
    </style>
</head>
<body>
<div class="register-container">
    <h2 class="text-center">Registrazione</h2>
    <?php if ($success): ?>
        <div class="alert alert-success"><?php echo $success; ?></div>
    <?php endif; ?>
    <?php if ($error): ?>
        <div class="alert alert-danger"><?php echo $error; ?></div>
    <?php endif; ?>
    <form method="POST">
        <div class="mb-3">
            <label for="nome" class="form-label">Nome</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cognome" class="form-label">Cognome</label>
            <input type="text" name="cognome" id="cognome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
            <input type="text" name="luogo_nascita" id="luogo_nascita" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="provincia_nascita" class="form-label">Provincia di Nascita</label>
            <input type="text" name="provincia_nascita" id="provincia_nascita" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="data_nascita" class="form-label">Data di Nascita</label>
            <input type="date" name="data_nascita" id="data_nascita" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="sesso" class="form-label">Sesso</label>
            <select name="sesso" id="sesso" class="form-select" required>
                <option value="M">Maschio</option>
                <option value="F">Femmina</option>
            </select>
        </div>
        <div class="mb-3">
            <label for="comune_residenza" class="form-label">Comune di Residenza</label>
            <input type="text" name="comune_residenza" id="comune_residenza" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="provincia_residenza" class="form-label">Provincia di Residenza</label>
            <input type="text" name="provincia_residenza" id="provincia_residenza" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cap" class="form-label">CAP</label>
            <input type="text" name="cap" id="cap" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="via_piazza" class="form-label">Via/Piazza</label>
            <input type="text" name="via_piazza" id="via_piazza" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="numero_civico" class="form-label">Numero Civico</label>
            <input type="text" name="numero_civico" id="numero_civico" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cittadinanza" class="form-label">Cittadinanza</label>
            <input type="text" name="cittadinanza" id="cittadinanza" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="telefono" class="form-label">Telefono</label>
            <input type="text" name="telefono" id="telefono" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cellulare_madre" class="form-label">Cellulare Madre</label>
            <input type="text" name="cellulare_madre" id="cellulare_madre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cellulare_padre" class="form-label">Cellulare Padre</label>
            <input type="text" name="cellulare_padre" id="cellulare_padre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="tipo_utente" class="form-label">Tipo Utente</label>
            <select name="tipo_utente" id="tipo_utente" class="form-select" required>
                <option value="segretario">Segretario (👩‍💼)</option>
                <option value="cassiere">Cassiere (💰)</option>
                <option value="dirigente">Dirigente (👨‍💼)</option>
                <option value="socio">Socio (👥)</option>
                <option value="allenatore">Allenatore (🏃‍♂️)</option>
                <option value="genitore">Genitore (👨‍👩‍👧‍👦)</option>
            </select>
        </div>
        <button type="submit" class="btn btn-success w-100">Registrati</button>
    </form>
    <div class="mt-3 text-center">
        <a href="login.php">Hai già un account? Accedi</a>
    </div>
</div>
</body>
</html>
