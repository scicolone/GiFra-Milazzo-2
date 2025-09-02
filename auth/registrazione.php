<?php
session_start();
require_once '../config.php';

$success = '';
$error = '';

// Funzione per generare codice fiscale
function generateCodiceFiscale($nome, $cognome, $data_nascita, $sesso, $luogo_nascita) {
    $nome = strtoupper($nome);
    $cognome = strtoupper($cognome);
    $luogo_nascita = strtoupper($luogo_nascita);
    
    // Cognome: 3 consonanti/vocali
    $vocali = ['A', 'E', 'I', 'O', 'U'];
    $consonanti = [];
    $vocali_cognome = '';
    for ($i = 0; $i < strlen($cognome); $i++) {
        $char = $cognome[$i];
        if (in_array($char, $vocali)) {
            $vocali_cognome .= $char;
        } else {
            $consonanti[] = $char;
        }
    }
    $cognome_cf = substr(implode('', $consonanti) . $vocali_cognome . 'XXX', 0, 3);
    
    // Nome: 3 consonanti/vocali
    $consonanti = [];
    $vocali = '';
    for ($i = 0; $i < strlen($nome); $i++) {
        $char = $nome[$i];
        if (in_array($char, $vocali)) {
            $vocali .= $char;
        } else {
            $consonanti[] = $char;
        }
    }
    $nome_cf = substr(implode('', $consonanti) . $vocali . 'XXX', 0, 3);
    
    // Data di nascita
    $anno = substr(date('Y', strtotime($data_nascita)), -2);
    $mese = ['A', 'B', 'C', 'D', 'E', 'H', 'L', 'M', 'P', 'R', 'S', 'T'][date('n', strtotime($data_nascita)) - 1];
    $giorno = (int)date('j', strtotime($data_nascita));
    if ($sesso === 'F') {
        $giorno += 40;
    }
    $giorno_cf = str_pad($giorno, 2, '0', STR_PAD_LEFT);
    
    // Luogo di nascita
    $luogo_cf = substr($luogo_nascita . 'XXX', 0, 4);
    
    return $cognome_cf . $nome_cf . $anno . $mese . $giorno_cf . $luogo_cf;
}

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

    // Genera codice fiscale
    $codice_fiscale = generateCodiceFiscale($nome, $cognome, $data_nascita, $sesso, $luogo_nascita);

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
             cellulare_padre, email, password, tipo_utente, icona, approvato, codice_fiscale) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, FALSE, ?)
        ");
        $stmt->execute([
            $nome, $cognome, $luogo_nascita, $provincia_nascita, $data_nascita, $sesso,
            $comune_residenza, $provincia_residenza, $cap, $via_piazza, $numero_civico,
            $cittadinanza, $telefono, $cellulare_madre, $cellulare_padre, $email,
            $password, $tipo_utente, $icona, $codice_fiscale
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
        .input-group {
            position: relative;
        }
        .autocomplete-suggestions {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background: white;
            border: 1px solid #ddd;
            z-index: 1000;
            max-height: 200px;
            overflow-y: auto;
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
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="cognome" class="form-label">Cognome</label>
                    <input type="text" name="cognome" id="cognome" class="form-control" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
                    <input type="text" name="luogo_nascita" id="luogo_nascita" class="form-control" required>
                    <div id="luogo_nascita_suggestions" class="autocomplete-suggestions"></div>
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
        
        <div class="mb-3">
            <label for="sesso" class="form-label">Sesso</label>
            <select name="sesso" id="sesso" class="form-select" required>
                <option value="">Seleziona...</option>
                <option value="M">Maschio</option>
                <option value="F">Femmina</option>
            </select>
        </div>
        
        <!-- Residenza -->
        <h4 class="section-title">Residenza</h4>
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="comune_residenza" class="form-label">Comune di Residenza</label>
                    <input type="text" name="comune_residenza" id="comune_residenza" class="form-control" required>
                    <div id="comune_residenza_suggestions" class="autocomplete-suggestions"></div>
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
                    <label for="cellulare_madre" class="form-label">Cellulare Madre</label>
                    <input type="text" name="cellulare_madre" id="cellulare_madre" class="form-control">
                </div>
            </div>
            <div class="col-md-4">
                <div class="mb-3">
                    <label for="cellulare_padre" class="form-label">Cellulare Padre</label>
                    <input type="text" name="cellulare_padre" id="cellulare_padre" class="form-control">
                </div>
            </div>
        </div>
        
        <div class="mb-3">
            <label for="email" class="form-label">Email</label>
            <input type="email" name="email" id="email" class="form-control" required>
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
    <div class="mt-3 text-center">
        <a href="login.php">Hai già un account? Accedi</a>
    </div>
</div>

<script>
// Funzione per caricare suggerimenti
function loadSuggestions(inputId, suggestionsId, url) {
    const input = document.getElementById(inputId);
    const suggestions = document.getElementById(suggestionsId);
    
    input.addEventListener('input', function() {
        const value = this.value;
        if (value.length < 2) {
            suggestions.style.display = 'none';
            return;
        }
        
        fetch(url + '?comune=' + value)
            .then(response => response.json())
            .then(data => {
                suggestions.innerHTML = '';
                if (data.provincia && data.cap) {
                    const item = document.createElement('div');
                    item.className = 'autocomplete-item';
                    item.textContent = `${value} (${data.provincia}, ${data.cap})`;
                    item.onclick = function() {
                        input.value = value;
                        document.getElementById('provincia_nascita').value = data.provincia;
                        document.getElementById('cap').value = data.cap;
                        suggestions.style.display = 'none';
                    };
                    suggestions.appendChild(item);
                }
                suggestions.style.display = 'block';
            });
    });
    
    // Nascondi suggerimenti al clic fuori
    document.addEventListener('click', function(e) {
        if (!input.contains(e.target) && !suggestions.contains(e.target)) {
            suggestions.style.display = 'none';
        }
    });
}

// Carica suggerimenti per luogo di nascita
loadSuggestions('luogo_nascita', 'luogo_nascita_suggestions', 'ajax/get_comune.php');

// Carica suggerimenti per comune di residenza
loadSuggestions('comune_residenza', 'comune_residenza_suggestions', 'ajax/get_comune.php');
</script>
</body>
</html>
