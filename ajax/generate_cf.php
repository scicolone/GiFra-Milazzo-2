<?php
require_once '../config.php';

$nome = $_GET['nome'] ?? '';
$cognome = $_GET['cognome'] ?? '';
$data = $_GET['data'] ?? '';
$sesso = $_GET['sesso'] ?? '';
$codice_catastale = $_GET['codice_catastale'] ?? '';

if (!$nome || !$cognome || !$data || !$sesso || !$codice_catastale) {
    echo '';
    exit;
}

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

$cf = generateCodiceFiscale($nome, $cognome, $data, $sesso, $codice_catastale);
echo $cf;
?>
