<?php
session_start();
require_once '../config.php';

// Funzione per generare codice fiscale corretto
function generateCodiceFiscale($nome, $cognome, $data_nascita, $sesso, $codice_catastale) {
    $mesi = ['A', 'B', 'C', 'D', 'E', 'H', 'L', 'M', 'P', 'R', 'S', 'T'];
    
    $cognome_cf = estraiConsonantiVocali($cognome, 3);
    $nome_cf = estraiConsonantiVocali($nome, 3);
    
    $data = new DateTime($data_nascita);
    $anno = substr($data->format('Y'), -2);
    $mese = $mesi[$data->format('n') - 1];
    $giorno = (int)$data->format('j');
    
    if ($sesso === 'F') {
        $giorno += 40;
    }
    $giorno_cf = str_pad($giorno, 2, '0', STR_PAD_LEFT);
    
    $comune_cf = substr(strtoupper($codice_catastale), 0, 4);
    $cf_parziale = $cognome_cf . $nome_cf . $anno . $mese . $giorno_cf . $comune_cf;
    $controllo = 'X'; // Semplificato
    
    return $cf_parziale . $controllo;
}

function estraiConsonantiVocali($stringa, $lunghezza) {
    $stringa = strtoupper($stringa);
    $vocali = ['A', 'E', 'I', 'O', 'U'];
    $consonanti = '';
    $vocali_str = '';
    
    for ($i = 0; $i < strlen($stringa); $i++) {
        $char = $stringa[$i];
        if (in_array($char, $vocali)) {
            $vocali_str .= $char;
        } else {
            $consonanti .= $char;
        }
    }
    
    $risultato = $consonanti . $vocali_str;
    return str_pad(substr($risultato, 0, $lunghezza), $lunghezza, 'X');
}

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
    $cellulare = $_POST['cellulare'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $tipo_utente = $_POST['tipo_utente'];
    $codice_fiscale = $_POST['codice_fiscale'];

    $icone = [
        'segretario' => '👩‍💼',
        'cassiere' => '💰',
        'dirigente' => '👨‍💼',
        'socio' => '👥',
        'allenatore' => '🏃‍♂️',
        'genitore' => '👨‍👩‍👧‍👦'
    ];
    $icona = $icone[$tipo_utente] ?? '👤';

    $stmt = $pdo->prepare("SELECT COUNT(*) FROM utenti WHERE email = ?");
    $stmt->execute([$email]);
    if ($stmt->fetchColumn() > 0) {
        $error = "Email già registrata.";
    } else {
        $stmt = $pdo->prepare("
            INSERT INTO utenti 
            (nome, cognome, luogo_nascita, provincia_nascita, data_nascita, sesso, comune_residenza, 
             provincia_residenza, cap, via_piazza, numero_civico, cittadinanza, telefono, cellulare, 
             email, password, tipo_utente, icona, approvato, codice_fiscale) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, ?)
        ");
        $stmt->execute([
            $nome, $cognome, $luogo_nascita, $provincia_nascita, $data_nascita, $sesso,
            $comune_residenza, $provincia_residenza, $cap, $via_piazza, $numero_civico,
            $cittadinanza, $telefono, $cellulare, $email, $password, $tipo_utente, $icona, $codice_fiscale
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
            max-width: 800px;
            margin: 50px auto;
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
        .section-title {
            color: #1976d2;
            border-bottom: 2px solid #d32f2f;
            padding-bottom: 5px;
            margin-top: 20px;
            margin-bottom: 15px;
        }
        .autocomplete-suggestions {
            position: absolute;
            background: white;
            border: 1px solid #ddd;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
            width: 100%;
            display: none;
        }
        .autocomplete-item {
            padding: 8px 12px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
        }
        .autocomplete-item:hover {
            background-color: #f0f0f0;
        }
        .position-relative {
            position: relative;
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
        <!-- Dati Anagrafici -->
        <h4 class="section-title">Dati Anagrafici</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="cognome" class="form-label">Cognome</label>
                    <input type="text" name="cognome" id="cognome" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 position-relative">
                <div class="mb-3">
                    <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
                    <input type="text" name="luogo_nascita" id="luogo_nascita" class="form-control" required>
                    <div id="luogo_suggestions" class="autocomplete-suggestions"></div>
                    <input type="hidden" id="codice_catastale_nascita" value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="provincia_nascita" class="form-label">Provincia di Nascita</label>
                    <input type="text" name="provincia_nascita" id="provincia_nascita" class="form-control" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="data_nascita" class="form-label">Data di Nascita</label>
                    <input type="date" name="data_nascita" id="data_nascita" class="form-control" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sesso" class="form-label">Sesso</label>
                    <select name="sesso" id="sesso" class="form-select" required>
                        <option value="">Seleziona...</option>
                        <option value="M">Maschio</option>
                        <option value="F">Femmina</option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="codice_fiscale" class="form-label">Codice Fiscale</label>
                    <input type="text" name="codice_fiscale" id="codice_fiscale" class="form-control" readonly>
                </div>
            </div>
        </div>
        
        <!-- Residenza -->
        <h4 class="section-title">Residenza</h4>
        <div class="row">
            <div class="col-md-6 position-relative">
                <div class="mb-3">
                    <label for="comune_residenza" class="form-label">Comune di Residenza</label>
                    <input type="text" name="comune_residenza" id="comune_residenza" class="form-control" required>
                    <div id="comune_suggestions" class="autocomplete-suggestions"></div>
                    <input type="hidden" id="codice_catastale_residenza" value="">
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="provincia_residenza" class="form-label">Provincia di Residenza</label>
                    <input type="text" name="provincia_residenza" id="provincia_residenza" class="form-control" readonly>
                </div>
            </div>
            <div class="col-md-3">
                <div class="mb-3">
                    <label for="cap" class="form-label">CAP</label>
                    <input type="text" name="cap" id="cap" class="form-control" readonly>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-8">
                <div class="mb-3">
                    <label for="via_piazza" class="form-label">Via/Piazza</label>
                    <input type="text" name="via_piazza" id="via_piazza" class="form-control" required>
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="numero_civico" class="form-label">Numero Civico</label>
                    <input type="text" name="numero_civico" id="numero_civico" class="form-control" required>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="cittadinanza" class="form-label">Cittadinanza</label>
            <input type="text" name="cittadinanza" id="cittadinanza" class="form-control" required value="Italiana">
        </div>
        
        <!-- Contatti -->
        <h4 class="section-title">Contatti</h4>
        <div class="row">
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="telefono" class="form-label">Telefono</label>
                    <input type="text" name="telefono" id="telefono" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="cellulare" class="form-label">Cellulare</label>
                    <input type="text" name="cellulare" id="cellulare" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="password" class="form-label">Password</label>
            <input type="password" name="password" id="password" class="form-control" required>
        </div>
        
        <!-- Tipo Utente -->
        <h4 class="section-title">Tipo Utente</h4>
        <div class="mb-3">
            <label for="tipo_utente" class="form-label">Seleziona il tuo ruolo</label>
            <select name="tipo_utente" id="tipo_utente" class="form-select" required>
                <option value="">Seleziona...</option>
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
    
    <!-- Pulsanti Indietro e Home -->
    <div class="mt-4 text-center">
        <a href="javascript:history.back()" class="btn btn-secondary">← Indietro</a>
        <a href="../index.php" class="btn btn-primary">🏠 Torna alla Home</a>
    </div>
    
    <div class="mt-3 text-center">
        <a href="login.php">Hai già un account? Accedi</a>
    </div>
</div>

<script>
// Funzione per mostrare suggerimenti
function showSuggestions(inputId, suggestionsId, url, onSelectCallback) {
    const input = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    
    let timeout;
    
    input.addEventListener('input', function() {
        clearTimeout(timeout);
        const term = this.value;
        
        if (term.length < 2) {
            suggestions.style.display = 'none';
            return;
        }
        
        timeout = setTimeout(() => {
            fetch(url + '?term=' + encodeURIComponent(term))
                .then(response => response.json())
                .then(data => {
                    suggestions.innerHTML = '';
                    if (data.length > 0) {
                        data.forEach(item => {
                            const div = document.createElement('div');
                            div.className = 'autocomplete-item';
                            div.textContent = `${item.nome} (${item.provincia}, ${item.cap})`;
                            div.onclick = function() {
                                input.value = item.nome;
                                onSelectCallback(item);
                                suggestions.style.display = 'none';
                            };
                            suggestions.appendChild(div);
                        });
                        suggestions.style.display = 'block';
                    } else {
                        suggestions.style.display = 'none';
                    }
                });
        }, 300);
    });
    
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
}

// Callback per selezione luogo di nascita
function onSelectLuogo(item) {
    document.getElementById('provincia_nascita').value = item.provincia;
    document.getElementById('codice_catastale_nascita').value = item.codice_catastale;
    updateCodiceFiscale();
}

// Callback per selezione comune di residenza
function onSelectComune(item) {
    document.getElementById('provincia_residenza').value = item.provincia;
    document.getElementById('cap').value = item.cap;
    document.getElementById('codice_catastale_residenza').value = item.codice_catastale;
}

// Inizializza suggerimenti
showSuggestions('luogo_nascita', 'luogo_suggestions', 'ajax/get_comune_data.php', onSelectLuogo);
showSuggestions('comune_residenza', 'comune_suggestions', 'ajax/get_comune_data.php', onSelectComune);

// Funzione per aggiornare codice fiscale
function updateCodiceFiscale() {
    const nome = document.getElementById('nome').value;
    const cognome = document.getElementById('cognome').value;
    const data = document.getElementById('data_nascita').value;
    const sesso = document.getElementById('sesso').value;
    const codiceCatastale = document.getElementById('codice_catastale_nascita').value;
    
    if (nome && cognome && data && sesso && codiceCatastale) {
        // Chiamata AJAX per generare codice fiscale
        const params = new URLSearchParams({
            nome: nome,
            cognome: cognome,
            data: data,
            sesso: sesso,
            codice_catastale: codiceCatastale
        });
        
        fetch('ajax/generate_cf.php?' + params)
            .then(response => response.text())
            .then(cf => {
                document.getElementById('codice_fiscale').value = cf;
            });
    }
}

// Event listeners per aggiornare codice fiscale
document.getElementById('nome').addEventListener('input', updateCodiceFiscale);
document.getElementById('cognome').addEventListener('input', updateCodiceFiscale);
document.getElementById('data_nascita').addEventListener('change', updateCodiceFiscale);
document.getElementById('sesso').addEventListener('change', updateCodiceFiscale);
document.getElementById('luogo_nascita').addEventListener('input', updateCodiceFiscale);
</script>
</body>
</html>
