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
    $mesi = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E', 6 => 'H', 7 => 'L', 8 => 'M', 9 => 'P', 10 => 'R', 11 => 'S', 12 => 'T'];
    
    // Estrai consonanti e vocali per cognome
    $cognome_cf = estraiCF($cognome, 3);
    
    // Estrai consonanti e vocali per nome
    $nome_cf = estraiCF($nome, 3);
    
    // Data di nascita
    $data_obj = new DateTime($data_nascita);
    $anno = substr($data_obj->format('Y'), -2);
    $mese = $mesi[(int)$data_obj->format('n')];
    $giorno = (int)$data_obj->format('j');
    
    // Se femmina, aggiungi 40
    if ($sesso === 'F') {
        $giorno += 40;
    }
    $giorno_cf = str_pad($giorno, 2, '0', STR_PAD_LEFT);
    
    // Codice catastale
    $comune_cf = substr(strtoupper($codice_catastale), 0, 4);
    
    // Componi codice fiscale parziale
    $cf_parziale = $cognome_cf . $nome_cf . $anno . $mese . $giorno_cf . $comune_cf;
    
    // Carattere di controllo (semplificato)
    $controllo = 'X';
    
    return $cf_parziale . $controllo;
}

function estraiCF($stringa, $lunghezza) {
    $stringa = strtoupper($stringa);
    $vocali = ['A', 'E', 'I', 'O', 'U'];
    $consonanti = '';
    $vocali_str = '';
    
    // Separa consonanti e vocali
    for ($i = 0; $i < strlen($stringa); $i++) {
        $char = $stringa[$i];
        if (in_array($char, $vocali)) {
            $vocali_str .= $char;
        } else {
            $consonanti .= $char;
        }
    }
    
    // Componi il risultato
    $risultato = $consonanti . $vocali_str;
    return str_pad(substr($risultato, 0, $lunghezza), $lunghezza, 'X');
}

$cf = generateCodiceFiscale($nome, $cognome, $data, $sesso, $codice_catastale);
echo $cf;
?>
