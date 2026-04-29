<?php
// Standaard gebruikernummer indien niet meegegeven
$gebruikernummer = $gebruikernummer ?? 9;

// Logincontrole
require '../../code/login/auth.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Analyse filter - Tracktive</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-light">

<!-- NAVBAR -->
<nav class="navbar navbar-dark bg-black p-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-bold" href="../../index.php">Tracktive</a>

        <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" data-bs-toggle="dropdown">
                Profiel
            </button>
            <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item" href="../../code/login/login.php">Login</a></li>
                <li><a class="dropdown-item" href="../../code/login/logout.php">Loguit</a></li>
                <li><a class="dropdown-item" href="../../code/login/loginaanmaken.php">Registreer</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- TITEL -->
<header class="container text-center py-5">
    <h1 class="fw-bold">Analyse bekijken</h1>
    <p class="lead">Filter je lichaamsmetingen.</p>
</header>

<!-- FORMULIER -->
<div class="container pb-5">

    <div class="mx-auto" style="max-width: 900px;">

        <div class="card bg-secondary text-light shadow-lg p-4">

            <h2 class="text-center mb-4 fw-bold">Filterinstellingen</h2>

            <form action="analyse_lichaamsmetingenphp.php" method="post">

                <!-- Gebruikersnummer meesturen -->
                <input type="hidden" name="gebruikernummer" value="<?= htmlspecialchars($gebruikernummer, ENT_QUOTES) ?>">

                <div class="row g-4">

                    <!-- Type meting -->
                    <div class="col-md-6">
                        <label class="form-label">Type meting</label>
                        <select name="type2" class="form-select form-select-lg bg-dark text-light border-light">
                            <option value="gewicht">Gewicht</option>
                            <option value="omtrek_borst">Omtrek borst</option>
                            <option value="omtrek_buik">Omtrek buik</option>
                            <option value="omtrek_biceps">Omtrek biceps</option>
                            <option value="omtrek_been">Omtrek been</option>
                        </select>
                    </div>

                    <!-- Begin datum -->
                    <div class="col-md-6">
                        <label class="form-label">Begin datum</label>
                        <input type="date" name="begindatum" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Eind datum -->
                    <div class="col-md-6">
                        <label class="form-label">Eind datum</label>
                        <input type="date" name="einddatum" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                        Analyse bekijken
                    </button>
                </div>

            </form>

        </div>

    </div>

</div>

<!-- FOOTER -->
<footer class="text-center py-3 mt-4">
    <p class="m-0">© Tracktive</p>
</footer>

</body>
</html>
