<?php
// Funzione per generare codice fiscale corretto secondo le regole italiane
function generateCodiceFiscale($nome, $cognome, $data_nascita, $sesso, $codice_catastale) {
    // Tabella conversione mese
    $mesi = ['A', 'B', 'C', 'D', 'E', 'H', 'L', 'M', 'P', 'R', 'S', 'T'];
    
    // 1. Cognome: 3 caratteri (consonanti + vocali)
    $cognome_cf = estraiConsonantiVocali($cognome, 3);
    
    // 2. Nome: 3 caratteri (consonanti + vocali)
    $nome_cf = estraiConsonantiVocali($nome, 3);
    
    // 3. Data di nascita e sesso
    $data = new DateTime($data_nascita);
    $anno = substr($data->format('Y'), -2);
    $mese = $mesi[$data->format('n') - 1];
    $giorno = (int)$data->format('j');
    
    // Se femmina, aggiungi 40
    if ($sesso === 'F') {
        $giorno += 40;
    }
    $giorno_cf = str_pad($giorno, 2, '0', STR_PAD_LEFT);
    
    // 4. Codice catastale del comune
    $comune_cf = substr(strtoupper($codice_catastale), 0, 4);
    
    // 5. Codice di controllo (semplificato)
    $cf_parziale = $cognome_cf . $nome_cf . $anno . $mese . $giorno_cf . $comune_cf;
    $controllo = calcolaCarattereControllo($cf_parziale);
    
    return $cf_parziale . $controllo;
}

// Funzione per estrarre consonanti e vocali
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

// Funzione semplificata per carattere di controllo
function calcolaCarattereControllo($cf) {
    // Tabella di controllo semplificata
    return 'X'; // In produzione implementare l'algoritmo completo
}
?>
