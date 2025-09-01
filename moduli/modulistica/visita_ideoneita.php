include dirname(__DIR__) . 'include/header.php';

<div class="card">
    <h2 class="card-title">Richiesta Visita Idoneità</h2>
    <form action="#" method="POST">
        <div class="mb-3">
            <label for="nome">Nome Atleta</label>
            <input type="text" name="nome" id="nome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="cognome">Cognome</label>
            <input type="text" name="cognome" id="cognome" class="form-control" required>
        </div>
        <div class="mb-3">
            <label for="data_nascita">Data di Nascita</label>
            <input type="date" name="data_nascita" id="data_nascita" class="form-control" required>
        </div>
        <button type="submit" class="btn">Invia Richiesta</button>
    </form>
</div>

<?php include '../include/footer.php'; ?>
