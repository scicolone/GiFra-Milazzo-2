<?php
session_start();
require_once '../../config.php';

// Verifica accesso
if (!isset($_SESSION['utente_id'])) {
    header("Location: ../../accesso.php");
    exit;
}
$tipo_utente = $_SESSION['tipo_utente'] ?? '';
if ($tipo_utente !== 'presidente' && $tipo_utente !== 'segretario') {
    die("Accesso negato. Solo Presidente e Segretario possono accedere a questa funzione.");
}

$error = '';
$success = '';

// Gestione form
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        // --- Dati Anagrafici ---
        $id_anagrafica = $_POST['id_anagrafica'] ?? null;
        $cognome = trim($_POST['cognome'] ?? '');
        $nome = trim($_POST['nome'] ?? '');
        $data_nascita = $_POST['data_nascita'] ?? null;
        $luogo_nascita = trim($_POST['luogo_nascita'] ?? '');
        $nazionalita = trim($_POST['nazionalita'] ?? 'Italiana');
        $piede = $_POST['piede'] ?? 'dx';
        $status_giocatore = $_POST['status_giocatore'] ?? 'attivo';
        $tesserato_figc = isset($_POST['tesserato_figc']) ? 1 : 0;
        $doc_riconoscimento = $_POST['doc_riconoscimento'] ?? null;
        $n_doc_riconoscimento = trim($_POST['n_doc_riconoscimento'] ?? '');
        $comune_rilascio_ci = trim($_POST['comune_rilascio_ci'] ?? '');
        $comune_residenza = trim($_POST['comune_residenza'] ?? '');
        $via_piazza = trim($_POST['via_piazza'] ?? '');
        $numero_civico = trim($_POST['numero_civico'] ?? '');
        $cap = trim($_POST['cap'] ?? '');
        $prov = trim($_POST['prov'] ?? '');
        $telefono_casa = trim($_POST['telefono_casa'] ?? '');
        $telefono_lavoro = trim($_POST['telefono_lavoro'] ?? '');
        $telefono_cellulare = trim($_POST['telefono_cellulare'] ?? '');
        $telefono_mamma = trim($_POST['telefono_mamma'] ?? '');
        $telefono_papa = trim($_POST['telefono_papa'] ?? '');
        $codice_fiscale = trim($_POST['codice_fiscale'] ?? '');
        $numero_tessera_asl = trim($_POST['numero_tessera_asl'] ?? '');
        $numero_tessera_figc = trim($_POST['numero_tessera_figc'] ?? '');
        $indirizzo_email = trim($_POST['indirizzo_email'] ?? '');
        $scuola = trim($_POST['scuola'] ?? '');
        $classe_frequenza = trim($_POST['classe_frequenza'] ?? '');
        $parrocchia_catechismo = trim($_POST['parrocchia_catechismo'] ?? '');

        // --- Foto Calciatore ---
        $foto_calciatore = null;
        if (isset($_FILES['foto_calciatore']) && $_FILES['foto_calciatore']['error'] === 0) {
            $allowed_types = ['image/jpeg', 'image/jpg', 'image/png'];
            if (in_array($_FILES['foto_calciatore']['type'], $allowed_types)) {
                $dir = 'uploads/foto_calciatori/';
                if (!is_dir($dir)) mkdir($dir, 0777, true);
                $filename = uniqid() . '_' . basename($_FILES['foto_calciatore']['name']);
                $filepath = $dir . $filename;
                if (move_uploaded_file($_FILES['foto_calciatore']['tmp_name'], $filepath)) {
                    $foto_calciatore = $filepath;
                } else {
                    throw new Exception("Errore durante il caricamento della foto.");
                }
            } else {
                throw new Exception("Tipo di file non supportato. Usa JPG, PNG.");
            }
        }

        // --- Genitore Iscrivente ---
        $genitore_iscrivente_cognome = trim($_POST['genitore_iscrivente_cognome'] ?? '');
        $genitore_iscrivente_similarity = trim($_POST['genitore_iscrivente_name'] ?? '');
        $genitore_iscrivente_luogo_nascita = trim($_POST['genitore_iscrivente_luogo_nascita'] ?? '');
        $genitore_iscrivente_data_nascita = $_POST['genitore_iscrivente_data_nascita'] ?? null;
        $genitore_iscrivente_provincia_nascita = trim($_POST['genitore_iscrivente_provincia_nascita'] ?? '');
        $genitore_iscrivente_comune_residenza = trim($_POST['genitore_iscrivente_comune_residenza'] ?? '');
        $genitore_iscrivente_provincia_residenza = trim($_POST['genitore_iscrivente_provincia_residenza'] ?? '');
        $genitore_iscrivente_cap = trim($_POST['genitore_iscrivente_cap'] ?? '');
        $genitore_iscrivente_via_piazza = trim($_POST['genitore_iscrivente_via_piazza'] ?? '');
        $genitore_iscrivente_numero_civico = trim($_POST['genitore_iscrivente_numero_civico'] ?? '');
        $genitore_iscrivente_telefono = trim($_POST['genitore_iscrivente_telefono'] ?? '');
        $genitore_iscrivente_cellulare = trim($_POST['genitore_iscrivente_cellulare'] ?? '');
        $genitore_iscrivente_email = trim($_POST['genitore_iscrivente_email'] ?? '');
        $genitore_iscrivente_codice_fiscale = trim($_POST['genitore_iscrivente_codice_fiscale'] ?? '');

        // --- Genitore Fiscale ---
        $genitori_stessi = isset($_POST['genitori_stessi']) ? 1 : 0;

        if ($genitori_stessi) {
            $genitore_fiscale_cognome = $genitore_iscrivente_cognome;
            $genitore_fiscale_name = $genitore_iscrivente_name;
            $genitore_fiscale_luogo_nascita = $genitore_iscrivente_luogo_nascita;
            $genitore_fiscale_data_nascita = $genitore_iscrivente_data_nascita;
            $genitore_fiscale_provincia_nascita = $genitore_iscrivente_provincia_nascita;
            $genitore_fiscale_comune_residenza = $genitore_iscrivente_comune_residenza;
            $genitore_fiscale_provincia_residenza = $genitore_iscrivente_provincia_residenza;
            $genitore_fiscale_cap = $genitore_iscrivente_cap;
            $genitore_fiscale_via_piazza = $genitore_iscrivente_via_piazza;
            $genitore_fiscale_numero_civico = $genitore_iscrivente_numero_civico;
            $genitore_fiscale_telefono = $genitore_iscrivente_telefono;
            $genitore_fiscale_cellulare = $genitore_iscrivente_cellulare;
            $genitore_fiscale_email = $genitore_iscrivente_email;
            $genitore_fiscale_codice_fiscale = $genitore_iscrivente_codice_fiscale;
        } else {
            $genitore_fiscale_cognome = trim($_POST['genitore_fiscale_cognome'] ?? '');
            $genitore_fiscale_name = trim($_POST['genitore_fiscale_name'] ?? '');
            $genitore_fiscale_luogo_nascita = trim($_POST['genitore_fiscale_luogo_nascita'] ?? '');
            $genitore_fiscale_data_nascita = $_POST['genitore_fiscale_data_nascita'] ?? null;
            $genitore_fiscale_provincia_nascita = trim($_POST['genitore_fiscale_provincia_nascita'] ?? '');
            $genitore_fiscale_comune_residenza = trim($_POST['genitore_fiscale_comune_residenza'] ?? '');
            $genitore_fiscale_provincia_residenza = trim($_POST['genitore_fiscale_provincia_residenza'] ?? '');
            $genitore_fiscale_cap = trim($_POST['genitore_fiscale_cap'] ?? '');
            $genitore_fiscale_via_piazza = trim($_POST['genitore_fiscale_via_piazza'] ?? '');
            $genitore_fiscale_numero_civico = trim($_POST['genitore_fiscale_numero_civico'] ?? '');
            $genitore_fiscale_telefono = trim($_POST['genitore_fiscale_telefono'] ?? '');
            $genitore_fiscale_cellulare = trim($_POST['genitore_fiscale_cellulare'] ?? '');
            $genitore_fiscale_email = trim($_POST['genitore_fiscale_email'] ?? '');
            $genitore_fiscale_codice_fiscale = trim($_POST['genitore_fiscale_codice_fiscale'] ?? '');
        }

        // Validazione base
        if (empty($cognome) || empty($nome) || empty($data_nascita) || empty($comune_residenza) || empty($via_piazza)) {
            $error = "I campi obbligatori non possono essere vuoti.";
        } else {
            // Inserisci il genitore iscrivente nella tabella utenti
            $stmt = $pdo->prepare("INSERT INTO utenti (nome, cognome, luogo_nascita, provincia_nascita, data_nascita, sesso, comune_residenza, provincia_residenza, cap, via_piazza, numero_civico, cittadinanza, telefono, cellulare, email, password, tipo_utente, approvato) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)");
            $stmt->execute([
                $genitore_iscrivente_name, $genitore_iscrivente_cognome, $genitore_iscrivente_luogo_nascita, $genitore_iscrivente_provincia_nascita, $genitore_iscrivente_data_nascita, 'M', $genitore_iscrivente_comune_residenza, $genitore_iscrivente_provincia_residenza, $genitore_iscrivente_cap, $genitore_iscrivente_via_piazza, $genitore_iscrivente_numero_civico, 'Italiana', $genitore_iscrivente_telefono, $genitore_iscrivente_cellulare, $genitore_iscrivente_email, password_hash('temp_password', PASSWORD_DEFAULT), 'genitore', TRUE
            ]);
            $id_genitore = $pdo->lastInsertId();

            // Inserisci il genitore fiscale (se diverso)
            if (!$genitori_stessi) {
                $stmt = $pdo->prepare("INSERT INTO utenti (nome, cognome, luogo_nascita, provincia_nascita, data_nascita, sesso, comune_residenza, provincia_residenza, cap, via_piazza, numero_civico, cittadinanza, telefono, cellulare, email, password, tipo_utente, approvato) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, TRUE)");
                $stmt->execute([
                    $genitore_fiscale_name, $genitore_fiscale_cognome, $genitore_fiscale_luogo_nascita, $genitore_fiscale_provincia_nascita, $genitore_fiscale_data_nascita, 'M', $genitore_fiscale_comune_residenza, $genitore_fiscale_provincia_residenza, $genitore_fiscale_cap, $genitore_fiscale_via_piazza, $genitore_fiscale_numero_civico, 'Italiana', $genitore_fiscale_telefono, $genitore_fiscale_cellulare, $genitore_fiscale_email, password_hash('temp_password', PASSWORD_DEFAULT), 'genitore', TRUE
                ]);
                $id_genitore_fiscale = $pdo->lastInsertId();
            } else {
                $id_genitore_fiscale = $id_genitore;
            }

            // Inserisci il calciatore
            $stmt = $pdo->prepare("INSERT INTO atleti (id_anagrafica, cognome, nome, data_nascita, luogo_nascita, nazionalita, piede, status_giocatore, tesserato_figc, doc_riconoscimento, n_doc_riconoscimento, comune_rilascio_ci, comune_residenza, via_piazza, numero_civico, cap, prov, telefono_casa, telefono_lavoro, telefono_cellulare, telefono_mamma, telefono_papa, codice_fiscale, numero_tessera_asl, numero_tessera_figc, indirizzo_email, scuola, classe_frequenza, parrocchia_catechismo, foto_calciatore, id_genitore, id_genitore_fiscale) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
            $stmt->execute([
                $id_anagrafica, $cognome, $nome, $data_nascita, $luogo_nascita, $nazionalita, $piede, $status_giocatore, $tesserato_figc, $doc_riconoscimento, $n_doc_riconoscimento, $comune_rilascio_ci, $comune_residenza, $via_piazza, $numero_civico, $cap, $prov, $telefono_casa, $telefono_lavoro, $telefono_cellulare, $telefono_mamma, $telefono_papa, $codice_fiscale, $numero_tessera_asl, $numero_tessera_figc, $indirizzo_email, $scuola, $classe_frequenza, $parrocchia_catechismo, $foto_calciatore, $id_genitore, $id_genitore_fiscale
            ]);

            $success = "Anagrafica atleta creata con successo!";
            $_POST = array(); // Reset dei campi
        }
    } catch (Exception $e) {
        $error = "Errore: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Crea Anagrafica Atleta</title>
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
            padding: 20px 0;
            border-bottom: 2px solid #d32f2f;
        }
        .logo {
            width: 120px;
            height: auto;
            display: block;
            margin: 0 auto 10px;
        }
        .brand-title {
            font-size: 1.8rem;
            color: #d32f2f;
            margin: 0;
            font-weight: bold;
        }
        .season-text {
            color: #eee;
            font-size: 0.9rem;
            margin: 5px 0 0;
        }
        .form-container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
        }
        .section-title {
            color: #1976d2;
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .btn-primary {
            background-color: #1976d2;
            border-color: #1976d2;
        }
        .btn-primary:hover {
            background-color: #1565c0;
            border-color: #1565c0;
        }
        .btn-success {
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-success:hover {
            background-color: #218838;
            border-color: #1e7e34;
        }
        .genitore-fiscale-section {
            display: none;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <img src="../../img/logo.png" alt="Logo Gi.Fra. Milazzo" class="logo">
        <h1 class="brand-title mb-0">A.S.D. GI.FRA. MILAZZO</h1>
        <p class="season-text mb-0">Gestione Anagrafica Atleti</p>
    </div>

    <div class="container mt-4">
        <div class="form-container">
            <h2 class="text-center mb-4">Crea Nuova Anagrafica Atleta</h2>
            
            <?php if ($success): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success); ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" enctype="multipart/form-data">
                <!-- Scheda ANAGRAFICA -->
                <h4 class="section-title">ANAGRAFICA</h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_anagrafica" class="form-label">ID Anagrafica</label>
                            <input type="text" class="form-control" id="id_anagrafica" name="id_anagrafica" value="<?php echo htmlspecialchars($_POST['id_anagrafica'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="cognome" class="form-label">Cognome *</label>
                            <input type="text" class="form-control" id="cognome" name="cognome" value="<?php echo htmlspecialchars($_POST['cognome'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome *</label>
                            <input type="text" class="form-control" id="nome" name="nome" value="<?php echo htmlspecialchars($_POST['nome'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="data_nascita" class="form-label">Data di Nascita *</label>
                            <input type="date" class="form-control" id="data_nascita" name="data_nascita" value="<?php echo htmlspecialchars($_POST['data_nascita'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
                            <input type="text" class="form-control" id="luogo_nascita" name="luogo_nascita" value="<?php echo htmlspecialchars($_POST['luogo_nascita'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="nazionalita" class="form-label">Nazionalità</label>
                            <input type="text" class="form-control" id="nazionalita" name="nazionalita" value="<?php echo htmlspecialchars($_POST['nazionalita'] ?? 'Italiana'); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="piede" class="form-label">Piede</label>
                            <select class="form-select" id="piede" name="piede">
                                <option value="dx" <?php echo (($_POST['piede'] ?? '') === 'dx') ? 'selected' : ''; ?>>Destro</option>
                                <option value="sx" <?php echo (($_POST['piede'] ?? '') === 'sx') ? 'selected' : ''; ?>>Sinistro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="status_giocatore" class="form-label">Status Giocatore</label>
                            <select class="form-select" id="status_giocatore" name="status_giocatore">
                                <option value="attivo" <?php echo (($_POST['status_giocatore'] ?? '') === 'attivo') ? 'selected' : ''; ?>>Attivo</option>
                                <option value="inattivo" <?php echo (($_POST['status_giocatore'] ?? '') === 'inattivo') ? 'selected' : ''; ?>>Inattivo</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="tesserato_figc" name="tesserato_figc" <?php echo (isset($_POST['tesserato_figc']) || (!$_POST && false)) ? 'checked' : ''; ?>>
                        <label class="form-check-label" for="tesserato_figc">
                            Tesserato FIGC
                        </label>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="doc_riconoscimento" class="form-label">Documento di Riconoscimento</label>
                            <select class="form-select" id="doc_riconoscimento" name="doc_riconoscimento">
                                <option value="">Seleziona...</option>
                                <option value="carta_id" <?php echo (($_POST['doc_riconoscimento'] ?? '') === 'carta_id') ? 'selected' : ''; ?>>Carta d'Identità</option>
                                <option value="passaporto" <?php echo (($_POST['doc_riconoscimento'] ?? '') === 'passaporto') ? 'selected' : ''; ?>>Passaporto</option>
                                <option value="altro" <?php echo (($_POST['doc_riconoscimento'] ?? '') === 'altro') ? 'selected' : ''; ?>>Altro</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="n_doc_riconoscimento" class="form-label">N. Doc. Riconoscimento</label>
                            <input type="text" class="form-control" id="n_doc_riconoscimento" name="n_doc_riconoscimento" value="<?php echo htmlspecialchars($_POST['n_doc_riconoscimento'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="comune_rilascio_ci" class="form-label">Comune Rilascio C.I.</label>
                            <input type="text" class="form-control" id="comune_rilascio_ci" name="comune_rilascio_ci" value="<?php echo htmlspecialchars($_POST['comune_rilascio_ci'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="comune_residenza" class="form-label">Comune di Residenza *</label>
                            <input type="text" class="form-control" id="comune_residenza" name="comune_residenza" value="<?php echo htmlspecialchars($_POST['comune_residenza'] ?? ''); ?>" required>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="via_piazza" class="form-label">Via/Piazza *</label>
                            <input type="text" class="form-control" id="via_piazza" name="via_piazza" value="<?php echo htmlspecialchars($_POST['via_piazza'] ?? ''); ?>" required>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="numero_civico" class="form-label">N. Civico</label>
                            <input type="text" class="form-control" id="numero_civico" name="numero_civico" value="<?php echo htmlspecialchars($_POST['numero_civico'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="cap" class="form-label">CAP</label>
                            <input type="text" class="form-control" id="cap" name="cap" value="<?php echo htmlspecialchars($_POST['cap'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="prov" class="form-label">Prov.</label>
                            <input type="text" class="form-control" id="prov" name="prov" value="<?php echo htmlspecialchars($_POST['prov'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="codice_fiscale" class="form-label">Codice Fiscale</label>
                            <input type="text" class="form-control" id="codice_fiscale" name="codice_fiscale" value="<?php echo htmlspecialchars($_POST['codice_fiscale'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="telefono_casa" class="form-label">Telefono Casa</label>
                            <input type="text" class="form-control" id="telefono_casa" name="telefono_casa" value="<?php echo htmlspecialchars($_POST['telefono_casa'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="telefono_lavoro" class="form-label">Telefono Lavoro</label>
                            <input type="text" class="form-control" id="telefono_lavoro" name="telefono_lavoro" value="<?php echo htmlspecialchars($_POST['telefono_lavoro'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="telefono_cellulare" class="form-label">Telefono Cellulare</label>
                            <input type="text" class="form-control" id="telefono_cellulare" name="telefono_cellulare" value="<?php echo htmlspecialchars($_POST['telefono_cellulare'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="telefono_mamma" class="form-label">Telefono Mamma</label>
                            <input type="text" class="form-control" id="telefono_mamma" name="telefono_mamma" value="<?php echo htmlspecialchars($_POST['telefono_mamma'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="telefono_papa" class="form-label">Telefono Papà</label>
                            <input type="text" class="form-control" id="telefono_papa" name="telefono_papa" value="<?php echo htmlspecialchars($_POST['telefono_papa'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="indirizzo_email" class="form-label">Indirizzo Email</label>
                            <input type="email" class="form-control" id="indirizzo_email" name="indirizzo_email" value="<?php echo htmlspecialchars($_POST['indirizzo_email'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="numero_tessera_asl" class="form-label">N. Tessera ASL</label>
                            <input type="text" class="form-control" id="numero_tessera_asl" name="numero_tessera_asl" value="<?php echo htmlspecialchars($_POST['numero_tessera_asl'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="numero_tessera_figc" class="form-label">N. Tessera FIGC</label>
                            <input type="text" class="form-control" id="numero_tessera_figc" name="numero_tessera_figc" value="<?php echo htmlspecialchars($_POST['numero_tessera_figc'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="scuola" class="form-label">Scuola</label>
                            <input type="text" class="form-control" id="scuola" name="scuola" value="<?php echo htmlspecialchars($_POST['scuola'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="classe_frequenza" class="form-label">Classe di Frequenza</label>
                            <input type="text" class="form-control" id="classe_frequenza" name="classe_frequenza" value="<?php echo htmlspecialchars($_POST['classe_frequenza'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="parrocchia_catechismo" class="form-label">Parrocchia del Catechismo</label>
                            <input type="text" class="form-control" id="parrocchia_catechismo" name="parrocchia_catechismo" value="<?php echo htmlspecialchars($_POST['parrocchia_catechismo'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Foto Calciatore -->
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="foto_calciatore" class="form-label">Foto Calciatore</label>
                            <input type="file" class="form-control" id="foto_calciatore" name="foto_calciatore" accept="image/*">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="id_anagrafica" class="form-label">ID Anagrafica</label>
                            <input type="text" class="form-control" id="id_anagrafica" name="id_anagrafica" value="<?php echo htmlspecialchars($_POST['id_anagrafica'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Genitore Iscrivente -->
                <h4 class="section-title">GENITORE ISCRIVENTE</h4>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_cognome" class="form-label">Cognome</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_cognome" name="genitore_iscrivente_cognome" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_cognome'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_name" class="form-label">Nome</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_name" name="genitore_iscrivente_name" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_name'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_data_nascita" class="form-label">Data di Nascita</label>
                            <input type="date" class="form-control" id="genitore_iscrivente_data_nascita" name="genitore_iscrivente_data_nascita" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_data_nascita'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_luogo_nascita" class="form-label">Luogo di Nascita</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_luogo_nascita" name="genitore_iscrivente_luogo_nascita" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_luogo_nascita'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_provincia_nascita" class="form-label">Provincia di Nascita</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_provincia_nascita" name="genitore_iscrivente_provincia_nascita" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_provincia_nascita'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_comune_residenza" class="form-label">Comune di Residenza</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_comune_residenza" name="genitore_iscrivente_comune_residenza" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_comune_residenza'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_provincia_residenza" class="form-label">Provincia Res.</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_provincia_residenza" name="genitore_iscrivente_provincia_residenza" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_provincia_residenza'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_cap" class="form-label">CAP</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_cap" name="genitore_iscrivente_cap" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_cap'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_via_piazza" class="form-label">Via/Piazza</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_via_piazza" name="genitore_iscrivente_via_piazza" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_via_piazza'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_numero_civico" class="form-label">N. Civico</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_numero_civico" name="genitore_iscrivente_numero_civico" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_numero_civico'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_codice_fiscale" class="form-label">Codice Fiscale</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_codice_fiscale" name="genitore_iscrivente_codice_fiscale" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_codice_fiscale'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_telefono" class="form-label">Telefono</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_telefono" name="genitore_iscrivente_telefono" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_telefono'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_cellulare" class="form-label">Cellulare</label>
                            <input type="text" class="form-control" id="genitore_iscrivente_cellulare" name="genitore_iscrivente_cellulare" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_cellulare'] ?? ''); ?>">
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="mb-3">
                            <label for="genitore_iscrivente_email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="genitore_iscrivente_email" name="genitore_iscrivente_email" value="<?php echo htmlspecialchars($_POST['genitore_iscrivente_email'] ?? ''); ?>">
                        </div>
                    </div>
                </div>

                <!-- Checkbox per genitori stessi -->
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="genitori_stessi" name="genitori_stessi" <?php echo (isset($_POST['genitori_stessi']) || (!$_POST && false)) ? 'checked' : ''; ?> onchange="toggleGenitoreFiscale()">
                        <label class="form-check-label" for="genitori_stessi">
                            Il genitore iscrivente è anche il genitore per lo scarico fiscale
                        </label>
                    </div>
                </div>

                <!-- Genitore Fiscale -->
                <div id="genitoreFiscaleSection" class="genitore-fiscale-section">
                    <h4 class="section-title">GENITORE FISCALE</h4>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="genitore_fiscale_cognome" class="form-label">Cognome</label>
                                <input type="text" class="form-control" id="genitore_fiscale_cognome" name="genitore_fiscale_cognome" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_cognome'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="genitore_fiscale_name" class="form-label">Nome</label>
                                <input type="text" class="form-control" id="genitore_fiscale_name" name="genitore_fiscale_name" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_name'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genitore_fiscale_data_nascita" class="form-label">Data di Nascita</label>
                                <input type="date" class="form-control" id="genitore_fiscale_data_nascita" name="genitore_fiscale_data_nascita" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_data_nascita'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genitore_fiscale_luogo_nascita" class="form-label">Luogo di Nascita</label>
                                <input type="text" class="form-control" id="genitore_fiscale_luogo_nascita" name="genitore_fiscale_luogo_nascita" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_luogo_nascita'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genitore_fiscale_provincia_nascita" class="form-label">Provincia di Nascita</label>
                                <input type="text" class="form-control" id="genitore_fiscale_provincia_nascita" name="genitore_fiscale_provincia_nascita" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_provincia_nascita'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="genitore_fiscale_comune_residenza" class="form-label">Comune di Residenza</label>
                                <input type="text" class="form-control" id="genitore_fiscale_comune_residenza" name="genitore_fiscale_comune_residenza" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_comune_residenza'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="genitore_fiscale_provincia_residenza" class="form-label">Provincia Res.</label>
                                <input type="text" class="form-control" id="genitore_fiscale_provincia_residenza" name="genitore_fiscale_provincia_residenza" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_provincia_residenza'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="genitore_fiscale_cap" class="form-label">CAP</label>
                                <input type="text" class="form-control" id="genitore_fiscale_cap" name="genitore_fiscale_cap" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_cap'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="genitore_fiscale_via_piazza" class="form-label">Via/Piazza</label>
                                <input type="text" class="form-control" id="genitore_fiscale_via_piazza" name="genitore_fiscale_via_piazza" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_via_piazza'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="genitore_fiscale_numero_civico" class="form-label">N. Civico</label>
                                <input type="text" class="form-control" id="genitore_fiscale_numero_civico" name="genitore_fiscale_numero_civico" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_numero_civico'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="mb-3">
                                <label for="genitore_fiscale_codice_fiscale" class="form-label">Codice Fiscale</label>
                                <input type="text" class="form-control" id="genitore_fiscale_codice_fiscale" name="genitore_fiscale_codice_fiscale" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_codice_fiscale'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genitore_fiscale_telefono" class="form-label">Telefono</label>
                                <input type="text" class="form-control" id="genitore_fiscale_telefono" name="genitore_fiscale_telefono" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_telefono'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genitore_fiscale_cellulare" class="form-label">Cellulare</label>
                                <input type="text" class="form-control" id="genitore_fiscale_cellulare" name="genitore_fiscale_cellulare" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_cellulare'] ?? ''); ?>">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="mb-3">
                                <label for="genitore_fiscale_email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="genitore_fiscale_email" name="genitore_fiscale_email" value="<?php echo htmlspecialchars($_POST['genitore_fiscale_email'] ?? ''); ?>">
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-4 text-center">
                    <button type="submit" class="btn btn-success">💾 Salva Anagrafica</button>
                    <a href="../../index.php" class="btn btn-secondary">🏠 Torna alla Home</a>
                </div>
            </form>
        </div>
    </div>

    <footer class="text-center py-3 text-muted mt-4">
        &copy; 2025 A.S.D. Gi.Fra. Milazzo - Tutti i diritti riservati
    </footer>

    <script>
        function toggleGenitoreFiscale() {
            const checkbox = document.getElementById('genitori_stessi');
            const section = document.getElementById('genitoreFiscaleSection');
            if (checkbox.checked) {
                section.style.display = 'none';
            } else {
                section.style.display = 'block';
            }
        }
        document.addEventListener('DOMContentLoaded', toggleGenitoreFiscale);
    </script>
</body>
</html>
