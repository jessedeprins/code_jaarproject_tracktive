<?php
// Zet foutmeldingen aan (handig tijdens ontwikkelen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Controleert of gebruiker ingelogd is
require '../../code/login/auth.php';
$ingelogd = isset($_SESSION['user_id']);

// Haal gebruikernummer uit POST (formulier)
$gebruikernummer = isset($_POST['gebruikernummer']) ? (int)$_POST['gebruikernummer'] : 0;

// Haal type op (lopen/fietsen)
$type2 = $_POST['type2'] ?? '';

// Haal begin- en einddatum op
$begindatum = $_POST['begindatum'] ?? '';
$einddatum  = $_POST['einddatum'] ?? '';

// Controle of verplichte velden ingevuld zijn
if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

// Database gegevens
$host   = 'ID479738_Tracktive.db.webhosting.be';
$dbname = 'ID479738_Tracktive';
$user   = 'ID479738_Tracktive';
$pass   = 'Spike2021?';

// Maak verbinding met database
$mysqli = new mysqli($host, $user, $pass, $dbname);

// Controle of verbinding gelukt is
if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}

// Zet encoding op UTF-8
$mysqli->set_charset("utf8");

// SQL query: berekent totale afstand per maand
$query = "
SELECT YEAR(U_Datum) AS Jaar,
       MONTH(U_Datum) AS Maand,
       SUM(U_Afstand) AS TotAfstand
FROM Uitgevoerdeoef
WHERE Gebruikernr = ?
AND U_Datum BETWEEN ? AND ?
";

$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss";

// Als type gekozen is (lopen/fietsen), voeg filter toe
if (!empty($type2)) {
    $query .= " AND U_Oeftype2 = ?";
    $params[] = $type2;
    $types .= "s";
}

$query .= "
GROUP BY Jaar, Maand
ORDER BY Jaar ASC, Maand ASC
";

$stmt = $mysqli->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

$records = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['Jaar'] . '-' . str_pad($row['Maand'], 2, '0', STR_PAD_LEFT);
    $records[$key] = (float)$row['TotAfstand'];
}

$labels = [];
$data   = [];

$start = new DateTime($begindatum);
$end   = new DateTime($einddatum);
$end->modify('first day of next month');

$interval = new DateInterval('P1M');
$period   = new DatePeriod($start, $interval, $end);

foreach ($period as $dt) {
    $key = $dt->format('Y-m');
    $labels[] = $dt->format('M Y');
    $data[] = $records[$key] ?? 0;
}

$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>   
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body class="bg-dark text-white">

<!-- NAVBAR (identiek aan eerste code) -->
<nav class="navbar navbar-dark bg-black p-3">
    <div class="container d-flex justify-content-between align-items-center">
        
        <a class="navbar-brand" href="../../index.php">Tracktive</a>

        <div class="dropdown">
            <button class="btn btn-outline-light dropdown-toggle" type="button" id="profielDropdown" data-bs-toggle="dropdown">
                Profiel
            </button>

            <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="profielDropdown">
                <?php if ($ingelogd): ?>
                    <li><a class="dropdown-item" href="../../code/login/logout.php">Loguit</a></li>
                <?php else: ?>
                    <li><a class="dropdown-item" href="../../code/login/login.php">Login</a></li>
                    <li><a class="dropdown-item" href="../../code/login/loginaanmaken.php">Registreer</a></li>
                <?php endif; ?>
            </ul>
        </div>

    </div>
</nav>

<!-- PAGINA INHOUD (zelfde container-stijl als eerste code) -->
<div class="container">

    <h2>Sportafstand per maand (km)</h2>

    <canvas id="afstandChart" width="800" height="400"></canvas>

</div>

<script>
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('afstandChart').getContext('2d');

const afstandChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Totale afstand (km)',
            data: dataValues,
            borderColor: 'rgba(75, 192, 192, 1)',
            backgroundColor: 'rgba(75, 192, 192, 0.2)',
            borderWidth: 2,
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        animation: false,
        scales: {
            x: {
                title: { display: true, text: 'Maand / Jaar' }
            },
            y: {
                beginAtZero: true,
                title: { display: true, text: 'Afstand (km)' }
            }
        }
    }
});
</script>

</body>
</html>
