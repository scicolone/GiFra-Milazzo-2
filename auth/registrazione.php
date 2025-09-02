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
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0,0,0,0.1);
        }
        .autocomplete-item {
            padding: 10px 15px;
            cursor: pointer;
            border-bottom: 1px solid #eee;
            transition: background-color 0.2s;
        }
        .autocomplete-item:hover {
            background-color: #f8f9fa;
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
            <input type="password" name="password" id="password" class="form-control" required minlength="8">
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
                })
                .catch(() => {
                    suggestions.style.display = 'none';
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
    updateCodiceFiscale();
}

// Callback per selezione comune di residenza
function onSelectComune(item) {
    document.getElementById('provincia_residenza').value = item.provincia;
    document.getElementById('cap').value = item.cap;
}

// Inizializza suggerimenti
showSuggestions('luogo_nascita', 'luogo_suggestions', 'ajax/search_comuni.php', onSelectLuogo);
showSuggestions('comune_residenza', 'comune_suggestions', 'ajax/search_comuni.php', onSelectComune);

// Funzione per autocompletare dati quando si esce dal campo
function autocompleteField(inputId, provinciaId, capId = null) {
    document.getElementById(inputId).addEventListener('blur', function() {
        const comune = this.value;
        if (comune.length > 0) {
            fetch('ajax/get_comune_data.php?term=' + encodeURIComponent(comune))
                .then(response => response.json())
                .then(data => {
                    if (data && !data.error) {
                        if (provinciaId) {
                            document.getElementById(provinciaId).value = data.provincia || '';
                        }
                        if (capId && data.cap) {
                            document.getElementById(capId).value = data.cap || '';
                        }
                        if (inputId === 'luogo_nascita') {
                            updateCodiceFiscale();
                        }
                    }
                })
                .catch(() => {});
        }
    });
}

// Funzione per calcolare il codice fiscale
async function updateCodiceFiscale() {
    const nome = document.getElementById('nome').value.trim();
    const cognome = document.getElementById('cognome').value.trim();
    const data = document.getElementById('data_nascita').value;
    const sesso = document.getElementById('sesso').value;
    const luogo = document.getElementById('luogo_nascita').value.trim();
    
    if (nome && cognome && data && sesso && luogo) {
        try {
            // Ottieni il codice catastale
            const response = await fetch('ajax/get_comune_data.php?term=' + encodeURIComponent(luogo));
            const dataComune = await response.json();
            
            if (dataComune && dataComune.codice_catastale) {
                const cf = calculateFiscalCode(cognome, nome, data, sesso, dataComune.codice_catastale);
                document.getElementById('codice_fiscale').value = cf;
            } else {
                document.getElementById('codice_fiscale').value = '';
            }
        } catch (error) {
            console.error('Errore:', error);
            document.getElementById('codice_fiscale').value = '';
        }
    } else {
        document.getElementById('codice_fiscale').value = '';
    }
}

// Funzione completa per calcolare il codice fiscale
function calculateFiscalCode(cognome, nome, data, sesso, codiceCatastale) {
    // Calcola il codice del cognome
    function calculateSurnameCode(cognome) {
        cognome = cognome.toUpperCase();
        const consonanti = cognome.replace(/[^BCDFGHJKLMNPQRSTVWXYZ]/g, '');
        const vocali = cognome.replace(/[^AEIOU]/g, '');
        
        let code = '';
        if (consonanti.length >= 3) {
            code = consonanti.substring(0, 3);
        } else if (consonanti.length === 2) {
            code = consonanti + (vocali[0] || 'X');
        } else if (consonanti.length === 1) {
            code = consonanti + (vocali.substring(0, 2) || 'XX').substring(0, 2);
        } else {
            code = (vocali + 'XXX').substring(0, 3);
        }
        return code;
    }
    
    // Calcola il codice del nome
    function calculateNameCode(nome) {
        nome = nome.toUpperCase();
        const consonanti = nome.replace(/[^BCDFGHJKLMNPQRSTVWXYZ]/g, '');
        
        let code = '';
        if (consonanti.length > 3) {
            code = consonanti[0] + consonanti[2] + consonanti[3];
        } else if (consonanti.length === 3) {
            code = consonanti;
        } else if (consonanti.length === 2) {
            const vocali = nome.replace(/[^AEIOU]/g, '');
            code = consonanti + (vocali[0] || 'X');
        } else {
            const vocali = nome.replace(/[^AEIOU]/g, '');
            code = consonanti + (vocali.substring(0, 2) || 'XX').substring(0, 2);
        }
        return code;
    }
    
    // Calcola il codice della data
    function calculateDateCode(data, sesso) {
        const mesi = ['A', 'B', 'C', 'D', 'E', 'H', 'L', 'M', 'P', 'R', 'S', 'T'];
        const anno = data.substring(2, 4);
        const mese = mesi[parseInt(data.substring(5, 7)) - 1];
        const giorno = sesso === 'F' ? parseInt(data.substring(8, 10)) + 40 : parseInt(data.substring(8, 10));
        return anno + mese + (giorno < 10 ? '0' + giorno : giorno);
    }
    
    // Calcola il carattere di controllo
    function calculateCheckCode(cfParziale) {
        const dispari = {
            '0': 1, '1': 0, '2': 5, '3': 7, '4': 9, '5': 13, '6': 15, '7': 17, '8': 19, '9': 21,
            'A': 1, 'B': 0, 'C': 5, 'D': 7, 'E': 9, 'F': 13, 'G': 15, 'H': 17, 'I': 19, 'J': 21,
            'K': 2, 'L': 4, 'M': 18, 'N': 20, 'O': 11, 'P': 3, 'Q': 6, 'R': 8, 'S': 12, 'T': 14,
            'U': 16, 'V': 10, 'W': 22, 'X': 25, 'Y': 24, 'Z': 23
        };
        
        const pari = {
            '0': 0, '1': 1, '2': 2, '3': 3, '4': 4, '5': 5, '6': 6, '7': 7, '8': 8, '9': 9,
            'A': 0, 'B': 1, 'C': 2, 'D': 3, 'E': 4, 'F': 5, 'G': 6, 'H': 7, 'I': 8, 'J': 9,
            'K': 10, 'L': 11, 'M': 12, 'N': 13, 'O': 14, 'P': 15, 'Q': 16, 'R': 17, 'S': 18, 'T': 19,
            'U': 20, 'V': 21, 'W': 22, 'X': 23, 'Y': 24, 'Z': 25
        };
        
        let somma = 0;
        for (let i = 0; i < 15; i++) {
            const char = cfParziale[i];
            if (i % 2 === 0) {
                somma += dispari[char] || 0;
            } else {
                somma += pari[char] || 0;
            }
        }
        
        const resto = somma % 26;
        return String.fromCharCode(65 + resto);
    }
    
    const cfCognome = calculateSurnameCode(cognome);
    const cfNome = calculateNameCode(nome);
    const cfData = calculateDateCode(data, sesso);
    const cfComune = codiceCatastale.toUpperCase();
    
    const cfParziale = cfCognome + cfNome + cfData + cfComune;
    const carattereControllo = calculateCheckCode(cfParziale);
    
    return cfParziale + carattereControllo;
}

// Event listeners
document.getElementById('nome').addEventListener('input', updateCodiceFiscale);
document.getElementById('cognome').addEventListener('input', updateCodiceFiscale);
document.getElementById('data_nascita').addEventListener('change', updateCodiceFiscale);
document.getElementById('sesso').addEventListener('change', updateCodiceFiscale);
document.getElementById('luogo_nascita').addEventListener('input', updateCodiceFiscale);

// Autocomplete on blur
autocompleteField('luogo_nascita', 'provincia_nascita');
autocompleteField('comune_residenza', 'provincia_residenza', 'cap');
</script>
</body>
</html>
