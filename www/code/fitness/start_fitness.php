<?php

// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Logincontrole
require '../../code/login/auth.php';

// Databaseconfig
include 'config.php';

// Check of gebruiker is ingelogd
if (!isset($_SESSION['user_id'])) {
    die("Je bent niet ingelogd.");
}

$gebruikernummer = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Tracktive - Overzicht schema's</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-light">

<!-- Navigatie -->
<nav class="navbar navbar-dark bg-black p-3 shadow-sm">
    <div class="container d-flex justify-content-between align-items-center">

        <a class="navbar-brand fw-bold" href="../../index.php">Tracktive</a>

        <!-- Profielmenu -->
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

<div class="container py-4">

    <h2 class="fw-bold mb-4">Overzicht schema's</h2>

    <?php
    // Schema's ophalen van de gebruiker
    $stmt = $conn->prepare("
        SELECT Fitness_schemasnr, Schema_naam 
        FROM Fitnessschema 
        WHERE Gebruikernr = ?
    ");
    $stmt->bind_param("i", $gebruikernummer);
    $stmt->execute();
    $result = $stmt->get_result();

    // Schema's gevonden
    if ($result->num_rows > 0) {

        echo "
        <div class='table-responsive'>
            <table class='table table-dark table-striped table-hover align-middle'>
                <thead class='table-light text-dark'>
                    <tr>
                        <th>Schema naam</th>
                        <th class='text-center'>Openen</th>
                    </tr>
                </thead>
                <tbody>";

        // Elke rij tonen
        while ($row = $result->fetch_assoc()) {

            echo "
            <tr>
                <td>" . htmlspecialchars($row["Schema_naam"]) . "</td>
                <td class='text-center'>
                    <form method='post' action='vul_tijdelijke_tabel.php' class='d-inline'>
                        <input type='hidden' name='Fitness_schemasnr' value='" . htmlspecialchars($row['Fitness_schemasnr']) . "'>
                        <button type='submit' class='btn btn-outline-success btn-sm'>
                            Openen
                        </button>
                    </form>
                </td>
            </tr>";
        }

        echo "</tbody></table></div>";

    } else {
        // Geen schema's aanwezig
        echo "<div class='alert alert-warning'>
                Er zijn nog geen schema's aangemaakt.
              </div>";
    }

    // Opruimen
    $stmt->close();
    $conn->close();
    ?>

</div>

</body>
</html>
