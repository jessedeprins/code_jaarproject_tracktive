<?php
// Logincontrole + user ophalen
require '../../code/login/auth.php';
$gebruikernummer = $_SESSION['user_id'];
 
// DB-config laden
include 'config.php';

// Schema-ID ophalen (veilig naar int)
$nr = isset($_POST['Fitness_schemasnr']) ? intval($_POST['Fitness_schemasnr']) : 0;
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <title>Tracktive - Oefeningen toevoegen</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-light">

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

    <h2 class="fw-bold mb-4">Oefeningen toevoegen aan schema</h2>

    <?php
    // Haalt alle standaard-oefeningen van deze gebruiker op
    $sql = "SELECT Stanoefnr, Type2, Oefnaam, Kg, Aantal 
            FROM Standaardoef 
            WHERE Gebruikernr = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $gebruikernummer);
    $stmt->execute();
    $result = $stmt->get_result();

    // Checkt of er oefeningen bestaan
    if ($result->num_rows > 0) {

        echo "<form method='post'>";
        echo "<input type='hidden' name='Fitness_schemasnr' value='".htmlspecialchars($nr)."'>";

        echo "
        <div class='table-responsive'>
            <table class='table table-dark table-striped table-hover align-middle'>
                <thead class='table-light text-dark'>
                    <tr>
                        <th>Type</th>
                        <th>Naam</th>
                        <th>Kg</th>
                        <th>#</th>
                        <th class='text-center'>+</th>
                    </tr>
                </thead>
                <tbody>";

        // Toont elke oefening in de lijst
        while($row = $result->fetch_assoc()) {

            echo "
            <tr>
                <td>".htmlspecialchars($row['Type2'])."</td>
                <td>".htmlspecialchars($row['Oefnaam'])."</td>
                <td>".htmlspecialchars($row['Kg'])."</td>
                <td>".htmlspecialchars($row['Aantal'])."</td>

                <!-- Toevoegen-knop stuurt oefening naar schema -->
                <td class='text-center'>
                    <button type='submit'
                            name='Stanoefnr'
                            value='".$row['Stanoefnr']."'
                            formaction='voeg_oefeningphp.php'
                            class='btn btn-outline-success btn-sm'>
                        +
                    </button>
                </td>
            </tr>";
        }

        echo "</tbody></table></div>";
        echo "</form>";

    } else {
        // Geen oefeningen gevonden
        echo "<div class='alert alert-warning'>Geen oefeningen gevonden.</div>";
    }

    // DB sluiten
    $conn->close();
    ?>

</div>

</body>
</html>