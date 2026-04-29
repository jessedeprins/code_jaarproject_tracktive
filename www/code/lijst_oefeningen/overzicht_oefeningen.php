<?php
// Logincontrole
require '../../code/login/auth.php';

// Gebruikersnummer ophalen
$gebruikernummer = $_SESSION['user_id'];
?>
<!DOCTYPE html>
<html lang="en">

<head>  
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>

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
    // Databaseconfig
    include 'config.php';

    // Oefeningen ophalen van de gebruiker
    $sql = "SELECT Stanoefnr, Type2, Oefnaam, Kg, Aantal 
            FROM Standaardoef 
            WHERE Gebruikernr = $gebruikernummer";
    // voert sql query uit
    $result = $conn->query($sql);

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
                        <th class='text-center'>X</th>
                    </tr>
                </thead>
                <tbody>";

        // Elke oefening tonen
        while($row = $result->fetch_assoc()) {
            // .htmlspecialchars = zet speciale HTML‑tekens om in veilige tekst zodat ze niet als HTML uitgevoerd worden.
            echo "
            <tr>
                <td>".htmlspecialchars($row["Type2"])."</td> 
                <td>".htmlspecialchars($row["Oefnaam"])."</td>
                <td>".htmlspecialchars($row["Kg"])."</td>
                <td>".htmlspecialchars($row["Aantal"])."</td>

                <td class='text-center'>
                    <form method='post' action='verwijderen_oefening.php' class='d-inline'>
                        <input type='hidden' name='Stanoefnr' value='".htmlspecialchars($row['Stanoefnr'])."'>
                        <button type='submit' class='btn btn-outline-danger btn-sm'>
                            X
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

    $conn->close();
    ?>

    <!-- Knop om nieuwe oefening toe te voegen -->
    <a href='aanmaak_oefening.php' class='btn btn-primary mt-3'>
        Oefening toevoegen
    </a>

</div>

</body>
</html>
