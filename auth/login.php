<?php
session_start();
include '../config.php';

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

<?php include '../include/header.php'; ?>

<div class="card mx-auto" style="max-width: 400px;">
    <h2 class="card-title text-center">Login</h2>
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

<?php include '../include/footer.php'; ?>
