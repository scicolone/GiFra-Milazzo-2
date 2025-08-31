<?php

require_once __DIR__ . '/../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_stagione = $_POST['stagione_id'];

    // Disattiva tutte le altre stagioni
    $stmt = $pdo->prepare("UPDATE stagioni SET attiva = FALSE");
    $stmt->execute();

    // Attiva la nuova stagione
    $stmt = $pdo->prepare("UPDATE stagioni SET attiva = TRUE WHERE id = ?");
    $stmt->execute([$id_stagione]);

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

// Recupera tutte le stagioni
$stmt = $pdo->query("SELECT id, anno_inizio, anno_fine, attiva FROM stagioni ORDER BY anno_inizio DESC");
$stagioni = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Trova la stagione attiva
$stagione_attiva = null;
foreach ($stagioni as $s) {
    if ($s['attiva']) {
        $stagione_attiva = $s;
        break;
    }
}
?>

<?php include '../include/header.php'; ?>

<div class="container">
    <div class="card">
        <h2 class="card-title" style="color: #fff; background-color: #1976d2; padding: 10px; border-radius: 5px;">
            Tabella Stagioni - Stagione attiva <?php echo $stagione_attiva ? $stagione_attiva['anno_inizio'] . '/' . $stagione_attiva['anno_fine'] : 'Nessuna'; ?>
        </h2>

        <div class="row mt-4">
            <div class="col-md-6 offset-md-3">
                <p class="text-center">Per cambiare la stagione attiva, seleziona la nuova stagione nel campo sottostante</p>
                
                <form method="POST" action="">
                    <div class="mb-3">
                        <select name="stagione_id" class="form-select" required>
                            <?php foreach ($stagioni as $s): ?>
                                <option value="<?php echo $s['id']; ?>" 
                                        <?php echo $s['attiva'] ? 'selected' : ''; ?>>
                                    <?php echo $s['anno_inizio'] . '/' . $s['anno_fine']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Invia</button>
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
