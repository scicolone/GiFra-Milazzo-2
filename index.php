<?php include 'include/header.php'; ?>

<?php
session_start();
if (!isset($_SESSION['utente_id']) || $_SESSION['ruolo'] !== 'presidente') {
    die("Accesso negato. Solo il Presidente può accedere.");
}
?>

<div class="row">
    <!-- Colonna sinistra -->
    <div class="col-md-8">
        <div class="card">
            <h2 class="card-title">TABELLE FUNZIONALITÀ GENERALI</h2>
            <a href="moduli/categorie.php" class="btn">TABELLA CATEGORIE</a>
            <a href="moduli/stagioni.php" class="btn">CREA NUOVA STAGIONE</a>
            <a href="moduli/cambio_stagione.php" class="btn">CAMBIO STAGIONE</a>
        </div>

        <div class="card">
            <h2 class="card-title">GESTIONE DISTINTE</h2>
            <a href="moduli/distinte/nuova_distinta.php" class="btn">NUOVA DISTINTA GARA</a>
            <a href="moduli/distinte/elenco_distinte.php" class="btn">ELENCO DISTINTE GARA</a>
        </div>

        <div class="card">
            <h2 class="card-title">MODULISTICA</h2>
            <a href="moduli/modulistica/visita_ideoneita.php" class="btn">RICHIESTA VISITA IDONEITÀ</a>
            <a href="moduli/modulistica/lettera_scuola.php" class="btn">LETTERA SCUOLA</a>
            <a href="moduli/modulistica/visita_specialistica.php" class="btn">VISITA SPECIALISTICA</a>
            <a href="moduli/modulistica/lettera_convocazione.php" class="btn">LETTERA CONVOCAZIONE</a>
            <a href="moduli/modulistica/dichiarazione_scuola.php" class="btn">DICHIARAZIONE PER SCUOLA</a>
            <a href="moduli/modulistica/rinvio_partite.php" class="btn">RINVIO PARTITE</a>
        </div>
    </div>

    <!-- Colonna destra -->
    <div class="col-md-4">
        <div class="card">
            <h2 class="card-title">ANAGRAFICHE STORICHE</h2>
            <a href="moduli/anagrafiche/seleziona_calciatore.php" class="btn btn-secondary">SELEZIONA CALCIATORE</a>
            <a href="moduli/anagrafiche/crea_atleta.php" class="btn btn-secondary">CREA ANAGRAFICA ATLETA</a>
            <a href="moduli/anagrafiche/allenatori.php" class="btn btn-secondary">ANAGRAFICA ALLENATORI</a>
            <a href="moduli/anagrafiche/dirigenti.php" class="btn btn-secondary">ANAGRAFICA DIRIGENTI</a>
            <a href="moduli/anagrafiche/osservatori.php" class="btn btn-secondary">ANAGRAFICA OSSERVATORI</a>
        </div>

        <div class="card">
            <h2 class="card-title">ANAGRAFICHE STAGIONE</h2>
            <a href="moduli/anagrafiche/tesserati.php" class="btn">TESSERATI - Anagrafica</a>
            <a href="moduli/anagrafiche/allenatori_stagione.php" class="btn">ALLENATORI Stagione</a>
            <a href="moduli/anagrafiche/dirigenti_stagione.php" class="btn">DIRIGENTI Stagione</a>
        </div>

        <div class="card">
            <h2 class="card-title">ALTRI GESTIONALI</h2>
            <a href="moduli/convo.php" class="btn">CONVOCAZIONI</a>
            <a href="moduli/calendario/partite.php" class="btn">PARTITE - Calend. campionato</a>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-12">
        <div class="menu-stampe">
            <a href="moduli/stampe/stampa_partite.php">MENU STAMPE</a>
        </div>
    </div>
</div>

<?php include 'include/footer.php'; ?>
