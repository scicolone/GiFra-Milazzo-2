<?php
session_start();
// Verifica che l'utente sia loggato
if (!isset($_SESSION['utente_id'])) {
    header("Location: accesso.php");
    exit;
}
// Verifica che sia presidente o segretario
$tipo_utente = $_SESSION['tipo_utente'] ?? ''; // Corretto il nome della variabile
if ($tipo_utente !== 'presidente' && $tipo_utente !== 'segretario') {
    // Se non è autorizzato, lo reindirizzo alla pagina di accesso con un messaggio
    // Possiamo usare una variabile di sessione per mostrare il messaggio
    $_SESSION['login_message'] = "Benvenuto " . htmlspecialchars($_SESSION['nome'] ?? 'Utente') . ". Al momento la tua area riservata è in costruzione. Puoi effettuare il logout.";
    header("Location: accesso.php");
    exit;
    // NOTA: In futuro, qui si reindirizzerà a una dashboard specifica per il tipo di utente
}

// --- Logica per determinare l'anno sportivo corrente ---
function getAnnoSportivoCorrente() {
    $today = new DateTime();
    $currentYear = (int)$today->format('Y');
    $currentMonth = (int)$today->format('m');

    // Se siamo da luglio (mese 7) a dicembre, l'anno sportivo inizia quest'anno
    if ($currentMonth >= 7) {
        $anno_inizio = $currentYear;
    } else {
        // Se siamo da gennaio a giugno, l'anno sportivo inizia l'anno scorso
        $anno_inizio = $currentYear - 1;
    }

    return $anno_inizio;
}

// --- Selezione/Creazione dell'Anno Sportivo ---
require_once 'config.php';

// Determina l'anno sportivo corrente
$anno_sportivo_corrente_inizio = getAnnoSportivoCorrente();
$anno_sportivo_corrente_fine = $anno_sportivo_corrente_inizio + 1;
$stagione_label = $anno_sportivo_corrente_inizio . '/' . $anno_sportivo_corrente_fine;

// 1. Controlla se l'anno sportivo esiste già nel database
$stmt_check = $pdo->prepare("SELECT id, attiva FROM stagioni WHERE anno_inizio = ? AND anno_fine = ?");
$stmt_check->execute([$anno_sportivo_corrente_inizio, $anno_sportivo_corrente_fine]);
$stagione_esistente = $stmt_check->fetch(PDO::FETCH_ASSOC);

if (!$stagione_esistente) {
    // 2a. Se non esiste, creala (inizialmente non attiva)
    $stmt_insert = $pdo->prepare("INSERT INTO stagioni (anno_inizio, anno_fine, attiva) VALUES (?, ?, FALSE)");
    $stmt_insert->execute([$anno_sportivo_corrente_inizio, $anno_sportivo_corrente_fine]);
    $id_nuova_stagione = $pdo->lastInsertId();
    $stagione_corrente_id = $id_nuova_stagione;
    // Potresti voler attivarla subito, oppure no. Per ora la creiamo.
    // Attiviamola subito per questo esempio.
    // Disattiva eventuali altre stagioni attive
    $stmt_update_altre = $pdo->prepare("UPDATE stagioni SET attiva = FALSE WHERE attiva = TRUE");
    $stmt_update_altre->execute();
    // Attiva la nuova stagione
    $stmt_update_nuova = $pdo->prepare("UPDATE stagioni SET attiva = TRUE WHERE id = ?");
    $stmt_update_nuova->execute([$id_nuova_stagione]);
} else {
    // 2b. Se esiste, controlla se è attiva
    $stagione_corrente_id = $stagione_esistente['id'];
    if (!$stagione_esistente['attiva']) {
        // Se esiste ma non è attiva, attivala (e disattiva le altre)
        $stmt_update_altre = $pdo->prepare("UPDATE stagioni SET attiva = FALSE WHERE attiva = TRUE");
        $stmt_update_altre->execute();
        $stmt_update_esistente = $pdo->prepare("UPDATE stagioni SET attiva = TRUE WHERE id = ?");
        $stmt_update_esistente->execute([$stagione_corrente_id]);
    }
    // Se esiste ed è già attiva, non fare nulla.
}

// Recupera la stagione attiva (dovrebbe essere quella appena gestita)
$stmt = $pdo->query("SELECT anno_inizio, anno_fine FROM stagioni WHERE attiva = TRUE LIMIT 1");
$stagione_attiva = $stmt->fetch(PDO::FETCH_ASSOC);
$stagione_label_visualizzata = $stagione_attiva ? $stagione_attiva['anno_inizio'] . '/' . $stagione_attiva['anno_fine'] : 'Nessuna';
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>A.S.D. Gi.Fra. Milazzo - Gestione Sportiva</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .header {
            background-color: #1976d2;
            color: white;
            text-align: center;
            padding: 30px 0;
            border-bottom: 2px solid #d32f2f;
        }
        .logo {
            width: 180px;
            height: auto;
            display: block;
            margin: 0 auto 15px;
        }
        .brand-title {
            font-size: 2rem;
            color: #d32f2f;
            margin: 0;
            font-weight: bold;
        }
        .season-text {
            color: #eee;
            font-size: 0.9rem;
            margin: 5px 0 0;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            padding: 15px;
            border: 1px solid #ddd;
        }
        .card-title {
            color: #1976d2;
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 8px;
            margin-top: 0;
            font-size: 1.2rem;
        }
        .btn {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 8px 0;
            background-color: #1976d2;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            transition: background-color 0.3s ease;
        }
        .btn:hover {
            background-color: #1565c0;
        }
        .btn-secondary {
            background-color: #b0bec5;
            color: #333;
        }
        .btn-secondary:hover {
            background-color: #90a4ae;
        }
        .menu-stampe {
            background-color: #fdd835;
            color: #333;
            font-weight: bold;
            padding: 10px;
            margin-top: 10px;
            text-align: center;
            border-radius: 5px;
        }
        .login-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: transparent;
            color: white;
            border: 1px solid white;
            padding: 5px 10px;
            font-size: 12px;
            cursor: pointer;
        }
    </style>
</head>
<body>
<!-- Header con logo e stagione -->
<div class="header">
    <img src="img/logo.png" alt="Logo Gi.Fra. Milazzo" class="logo">
    <h1 class="brand-title mb-0">A.S.D. GI.FRA. MILAZZO</h1>
    <p class="season-text mb-0">Stagione Attiva: <?php echo $stagione_label_visualizzata; ?></p>
</div>

<!-- Login Button -->
<a href="auth/logout.php" class="login-btn">Logout</a>

<!-- Menu Principale -->
<div class="container mt-4">
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

    <div class="menu-stampe">
        <a href="moduli/stampe/stampa_partite.php">MENU STAMPE</a>
    </div>
</div>

<footer class="text-center py-3 text-muted">
    &copy; 2025 A.S.D. Gi.Fra. Milazzo - Tutti i diritti riservati
</footer>
</body>
</html>
