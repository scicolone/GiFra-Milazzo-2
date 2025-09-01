<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>A.S.D. Gi.Fra. Milazzo - Gestione Sportiva</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="icon" href="img/logo.png">
    <style>
        body {
            background-color: #f5f5f5;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
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
        .menu-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 0 20px;
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
    <p class="season-text mb-0">Stagione Attiva: 2025/26</p>
</div>

<!-- Login Button -->
<a href="auth/login.php" class="login-btn">Login</a>

<!-- Menu Principale -->
<div class="menu-container">
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
                <a href="moduli/anagrafiche/seleziona_calciatore.php" class="btn">SELEZIONA CALCIATORE</a>
                <a href="moduli/anagrafiche/crea_atleta.php" class="btn">CREA ANAGRAFICA ATLETA</a>
                <a href="moduli/anagrafiche/allenatori.php" class="btn">ANAGRAFICA ALLENATORI</a>
                <a href="moduli/anagrafiche/dirigenti.php" class="btn">ANAGRAFICA DIRIGENTI</a>
                <a href="moduli/anagrafiche/osservatori.php" class="btn">ANAGRAFICA OSSERVATORI</a>
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
