<?php
include '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $anno_inizio = $_POST['anno_inizio'];
    $anno_fine = $anno_inizio + 1;

    // Verifica se già esiste
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM stagioni WHERE anno_inizio = ? AND anno_fine = ?");
    $stmt->execute([$anno_inizio, $anno_fine]);
    if ($stmt->fetchColumn() == 0) {
        $stmt = $pdo->prepare("INSERT INTO stagioni (anno_inizio, anno_fine, attiva) VALUES (?, ?, FALSE)");
        $stmt->execute([$anno_inizio, $anno_fine]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <div class="card">
        <h2 class="card-title" style="color: #fff; background-color: #1976d2; padding: 10px; border-radius: 5px;">
            Crea Nuova Stagione Sportiva
        </h2>

        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <form method="POST" action="">
                    <div class="mb-3">
                        <label for="anno_inizio" class="form-label">Anno di Inizio della Stagione</label>
                        <input type="number" name="anno_inizio" id="anno_inizio" class="form-control" min="2000" max="2050" required>
                    </div>
                    <button type="submit" class="btn btn-success w-100">Crea Stagione</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div class="mt-4">
    <a href="javascript:history.back()" class="btn btn-secondary">← Indietro</a>
    <a href="../index.php" class="btn btn-primary">🏠 Torna alla Home</a>
</div>
<?php include '../include/footer.php'; ?>
