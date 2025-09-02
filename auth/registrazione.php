<?php
session_start();
require_once '../config.php';

$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // ... (codice di inserimento rimane uguato)
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
        }
        .autocomplete-item:hover {
            background-color: #f8f9fa;
        }
        .position-relative {
            position: relative;
        }
        .form-control[readonly] {
            background-color: #f8f9fa;
            cursor: not-allowed;
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
                    <input type="text" name="cognome" id="cognome" class="form-control cf-input" required>
                </div>
            </div>
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="nome" class="form-label">Nome</label>
                    <input type="text" name="nome" id="nome" class="form-control cf-input" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6 position-relative">
                <div class="mb-3">
                    <label for="luogo_nascita" class="form-label">Luogo di Nascita</label>
                    <input type="text" name="luogo_nascita" id="luogo_nascita" class="form-control cf-input" required>
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
                    <input type="date" name="data_nascita" id="data_nascita" class="form-control cf-input" required>
                </div>
            </div>
        </div>
        
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label for="sesso" class="form-label">Sesso</label>
                    <select name="sesso" id="sesso" class="form-select cf-input" required>
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
        
        <!-- ... resto del form identico ... -->

<script>
// Sistema di autocompletamento con AJAX
class AutocompleteManager {
    constructor(inputId, suggestionsId, onSelectCallback) {
        this.input = document.getElementById(inputId);
        this.suggestions = document.getElementById(suggestionsId);
        this.onSelectCallback = onSelectCallback;
        this.currentData = null;
        
        this.init();
    }
    
    init() {
        this.input.addEventListener('input', (e) => this.handleInput(e));
        this.input.addEventListener('keydown', (e) => this.handleKeydown(e));
        document.addEventListener('click', (e) => this.handleClickOutside(e));
    }
    
    handleInput(e) {
        const value = e.target.value.trim();
        if (value.length < 2) {
            this.hideSuggestions();
            return;
        }
        
        this.fetchSuggestions(value);
    }
    
    async fetchSuggestions(term) {
        try {
            const response = await fetch(`ajax/search_comuni.php?term=${encodeURIComponent(term)}`);
            const data = await response.json();
            this.displaySuggestions(data);
        } catch (error) {
            console.error('Errore nel caricamento:', error);
            this.hideSuggestions();
        }
    }
    
    displaySuggestions(data) {
        this.suggestions.innerHTML = '';
        
        if (data.length === 0) {
            this.hideSuggestions();
            return;
        }
        
        data.forEach(item => {
            const div = document.createElement('div');
            div.className = 'autocomplete-item';
            div.textContent = `${item.nome} (${item.provincia}, ${item.cap})`;
            div.addEventListener('click', () => this.selectItem(item));
            this.suggestions.appendChild(div);
        });
        
        this.showSuggestions();
    }
    
    selectItem(item) {
        this.input.value = item.nome;
        this.onSelectCallback(item);
        this.hideSuggestions();
    }
    
    showSuggestions() {
        this.suggestions.style.display = 'block';
    }
    
    hideSuggestions() {
        this.suggestions.style.display = 'none';
    }
    
    handleKeydown(e) {
        if (e.key === 'Escape') {
            this.hideSuggestions();
        }
    }
    
    handleClickOutside(e) {
        if (!e.target.closest('.position-relative')) {
            this.hideSuggestions();
        }
    }
}

// Gestione autocompletamento
const luogoAutocomplete = new AutocompleteManager('luogo_nascita', 'luogo_suggestions', (item) => {
    document.getElementById('provincia_nascita').value = item.provincia;
    updateCodiceFiscale();
});

const comuneAutocomplete = new AutocompleteManager('comune_residenza', 'comune_suggestions', (item) => {
    document.getElementById('provincia_residenza').value = item.provincia;
    document.getElementById('cap').value = item.cap;
});

// Calcolo codice fiscale
async function getComuneData(comune) {
    try {
        const response = await fetch(`ajax/get_comune_data.php?term=${encodeURIComponent(comune)}`);
        return await response.json();
    } catch (error) {
        console.error('Errore:', error);
        return null;
    }
}

async function updateCodiceFiscale() {
    const nome = document.getElementById('nome').value.trim();
    const cognome = document.getElementById('cognome').value.trim();
    const data = document.getElementById('data_nascita').value;
    const sesso = document.getElementById('sesso').value;
    const luogo = document.getElementById('luogo_nascita').value.trim();
    
    if (nome && cognome && data && sesso && luogo) {
        const comuneData = await getComuneData(luogo);
        if (comuneData && comuneData.codice_catastale) {
            const cf = calculateFiscalCode(cognome, nome, data, sesso, comuneData.codice_catastale);
            document.getElementById('codice_fiscale').value = cf;
        }
    } else {
        document.getElementById('codice_fiscale').value = '';
    }
}

// Funzioni di calcolo codice fiscale (identiche alla versione precedente)
function calculateFiscalCode(cognome, nome, data, sesso, codiceCatastale) {
    // ... [funzione identica alla versione precedente]
}

// Event listeners per calcolo automatico
const cfInputs = document.querySelectorAll('.cf-input');
cfInputs.forEach(input => {
    input.addEventListener('input', updateCodiceFiscale);
    input.addEventListener('change', updateCodiceFiscale);
});
</script>
</body>
</html>
