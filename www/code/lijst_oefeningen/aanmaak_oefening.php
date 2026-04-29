<?php
// Logincontrole
require '../../code/login/auth.php';

// Gebruikersnummer ophalen
$gebruikernummer = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nieuwe oefening aanmaken - Tracktive</title>

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
    <h1 class="fw-bold">Nieuwe oefening aanmaken</h1>
    <p class="lead">Vul hieronder de gegevens van je oefening in.</p>
</header>

<!-- FORMULIER -->
<div class="container pb-5">

    <div class="mx-auto" style="max-width: 900px;">

        <div class="card bg-secondary text-light shadow-lg p-4">

            <h2 class="text-center mb-4 fw-bold">Oefening gegevens</h2>

            <form action="aanmaak_oefeningphp.php" method="post">

                <!-- Gebruikersnummer meesturen -->
                <input type="hidden" name="Gebruikernr" value="<?= htmlspecialchars($gebruikernummer) ?>">

                <div class="row g-4">

                    <!-- Type 1 -->
                    <div class="col-md-6">
                        <label class="form-label">Type 1</label>
                        <select name="Type1" class="form-select form-select-lg bg-dark text-light border-light">
                            <option value="Fitness" selected>Fitness</option>
                        </select>
                    </div>

                    <!-- Type 2 -->
                    <div class="col-md-6">
                        <label class="form-label">Type 2</label>
                        <select name="Type2" class="form-select form-select-lg bg-dark text-light border-light">
                            <option value="Bovenlichaam">Bovenlichaam</option>
                            <option value="Benen">Benen</option>
                            <option value="Rug">Rug</option>
                        </select>
                    </div>

                    <!-- Naam oefening -->
                    <div class="col-md-12">
                        <label class="form-label">Naam oefening</label>
                        <input type="text" name="Oefnaam" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Gewicht -->
                    <div class="col-md-6">
                        <label class="form-label">Gewicht (kg)</label>
                        <input type="number" name="Kg" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                    <!-- Aantal reps -->
                    <div class="col-md-6">
                        <label class="form-label">Aantal reps</label>
                        <input type="number" name="Aantal" class="form-control form-control-lg bg-dark text-light border-light" required>
                    </div>

                </div>

                <div class="d-grid mt-5">
                    <button type="submit" class="btn btn-primary btn-lg py-3 fw-bold">
                        Opslaan
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
