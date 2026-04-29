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

// Check of schema is meegestuurd
if (!isset($_POST['Fitness_schemasnr'])) {
    die("Geen schema geselecteerd.");
}

$fitness_schema = (int) $_POST['Fitness_schemasnr'];
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <title>Tracktive - Oefeningen</title>
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

    <h2 class="fw-bold mb-4">Overzicht oefeningen</h2>

    <?php
    // Oefeningen ophalen die bij het schema horen
    $stmt = $conn->prepare("
        SELECT tfo.Tijdelijk_f_oef_nr, tfo.Fitness_schema_oefnr, tfo.Fitness_schemasnr,
               tfo.Stanoefnr, tfo.Gebruikernr,
               so.Type1, so.Type2, so.Oefnaam, so.Kg, so.Aantal
        FROM Tijdelijk_f_oef tfo
        JOIN Standaardoef so ON tfo.Stanoefnr = so.Stanoefnr
        WHERE tfo.Fitness_schemasnr = ?
    ");

    $stmt->bind_param("i", $fitness_schema);
    $stmt->execute();
    $result = $stmt->get_result();

    // Oefeningen gevonden
    if ($result->num_rows > 0) {

        echo "
        <div class='table-responsive'>
            <table class='table table-dark table-striped table-hover align-middle'>
                <thead class='table-light text-dark'>
                    <tr>
                        <th>Type</th>
                        <th>Naam</th>
                        <th>Kg</th>
                        <th>#</th>
                        <th class='text-center'>Afvinken</th>
                    </tr>
                </thead>
                <tbody>";

        // Elke oefening tonen
        while ($row = $result->fetch_assoc()) {

            echo "
            <tr>
                <td>".htmlspecialchars($row["Type2"])."</td>
                <td>".htmlspecialchars($row["Oefnaam"])."</td>
                <td>".htmlspecialchars($row["Kg"])."</td>
                <td>".htmlspecialchars($row["Aantal"])."</td>

                <td class='text-center'>
                    <form method='post' action='afvinken.php' class='d-inline'>
                        <input type='hidden' name='Stanoefnr' value='".htmlspecialchars($row['Stanoefnr'])."'>
                        <input type='hidden' name='Fitness_schemasnr' value='".htmlspecialchars($fitness_schema)."'>
                        <input type='hidden' name='Tijdelijk_f_oef_nr' value='".htmlspecialchars($row['Tijdelijk_f_oef_nr'])."'>
                        <button type='submit' class='btn btn-outline-success btn-sm'>
                            Afvinken
                        </button>
                    </form>
                </td>
            </tr>";
        }

        echo "</tbody></table></div>";

    } else {
        // Geen oefeningen aanwezig
        echo "<div class='alert alert-warning'>Geen oefeningen gevonden.</div>";
    }

    // Opruimen
    $stmt->close();
    $conn->close();
    ?>

</div>

</body>
</html>
