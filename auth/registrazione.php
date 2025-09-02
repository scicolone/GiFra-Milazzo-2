<?php
require 'config.php'; // file connessione DB (usa PDO)

// eventuale gestione POST per salvataggio dati
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = $_POST['nome'];
    $cognome = $_POST['cognome'];
    $data_nascita = $_POST['data_nascita'];
    $comune_nascita = $_POST['comune_nascita'];
    $provincia_nascita = $_POST['provincia_nascita'];
    $cap_nascita = $_POST['cap_nascita'];
    $codice_catastale_nascita = $_POST['codice_catastale_nascita'];

    $comune_residenza = $_POST['comune_residenza'];
    $provincia_residenza = $_POST['provincia_residenza'];
    $cap_residenza = $_POST['cap_residenza'];
    $codice_fiscale = $_POST['codice_fiscale'];

    // esempio insert
    $stmt = $pdo->prepare("INSERT INTO atleti (nome, cognome, data_nascita, categoria, tessera) 
                           VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nome, $cognome, $data_nascita, 'categoria_default', 'tessera_default']);

    echo "<p>Registrazione completata!</p>";
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione Atleta</title>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/codicefiscale-js@2.1.1/dist/codicefiscale.min.js"></script>
</head>
<body>
    <h1>Registrazione Atleta</h1>
    <form method="post">
        <label>Nome: <input type="text" id="nome" name="nome" required></label><br>
        <label>Cognome: <input type="text" id="cognome" name="cognome" required></label><br>
        <label>Data di nascita: <input type="date" id="data_nascita" name="data_nascita" required></label><br>

        <!-- Comune di nascita -->
        <label>Comune di nascita: <input type="text" id="comune_nascita" name="comune_nascita" required></label><br>
        <label>Provincia nascita: <input type="text" id="provincia_nascita" name="provincia_nascita" readonly></label><br>
        <label>CAP nascita: <input type="text" id="cap_nascita" name="cap_nascita" readonly></label><br>
        <input type="hidden" id="codice_catastale_nascita" name="codice_catastale_nascita">

        <!-- Comune di residenza -->
        <label>Comune di residenza: <input type="text" id="comune_residenza" name="comune_residenza" required></label><br>
        <label>Provincia residenza: <input type="text" id="provincia_residenza" name="provincia_residenza" readonly></label><br>
        <label>CAP residenza: <input type="text" id="cap_residenza" name="cap_residenza" readonly></label><br>

        <!-- Codice Fiscale -->
        <label>Codice Fiscale: <input type="text" id="codice_fiscale" name="codice_fiscale" readonly></label><br>

        <button type="submit">Registrati</button>
    </form>

    <script>
    // funzione AJAX per recupero dati comuni
    function cercaComune(campoComune, campoProvincia, campoCap, campoCatastale) {
        let comune = $(campoComune).val();
        if (comune.length > 2) {
            $.getJSON("get_comune.php", { q: comune }, function(data) {
                if (data) {
                    $(campoProvincia).val(data.provincia);
                    $(campoCap).val(data.cap);
                    $(campoCatastale).val(data.codice_catastale);
                    generaCF(); // aggiorna codice fiscale se è comune di nascita
                }
            });
        }
    }

    // attiva ricerca comune nascita
    $("#comune_nascita").on("blur", function() {
        cercaComune("#comune_nascita", "#provincia_nascita", "#cap_nascita", "#codice_catastale_nascita");
    });

    // attiva ricerca comune residenza
    $("#comune_residenza").on("blur", function() {
        cercaComune("#comune_residenza", "#provincia_residenza", "#cap_residenza", null);
    });

    // funzione per generare il CF
    function generaCF() {
        let dataNascita = $("#data_nascita").val();
        if (!dataNascita) return;

        let [anno, mese, giorno] = dataNascita.split("-");
        let sesso = "M"; // TODO: aggiungere selezione sesso

        if ($("#nome").val() && $("#cognome").val() && $("#codice_catastale_nascita").val()) {
            let cf = new CodiceFiscale({
                name: $("#nome").val(),
                surname: $("#cognome").val(),
                gender: sesso,
                day: giorno,
                month: mese,
                year: anno,
                birthplace: $("#comune_nascita").val(),
                birthplaceCode: $("#codice_catastale_nascita").val()
            });
            $("#codice_fiscale").val(cf.cf);
        }
    }

    $("#nome, #cognome, #data_nascita").on("change", generaCF);
    </script>
</body>
</html>
