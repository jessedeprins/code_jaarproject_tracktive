<?php
// Fouten tonen tijdens ontwikkeling
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Ingelogd zijn is verplicht
require '../../code/login/auth.php';

// Formulierdata ophalen
$gebruikernummer = isset($_POST['gebruikernummer']) ? (int)$_POST['gebruikernummer'] : 0;
$type2 = $_POST['type2'] ?? '';
$begindatum = $_POST['begindatum'] ?? '';
$einddatum = $_POST['einddatum'] ?? '';

// Basisvalidatie
if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

// Databasegegevens
$host = 'ID479738_Tracktive.db.webhosting.be';
$dbname = 'ID479738_Tracktive';
$user = 'ID479738_Tracktive';
$pass = 'Spike2021?';

// Verbinding maken
$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8mb4");

// Query opbouwen
$query = "
    SELECT YEAR(U_Datum) AS Jaar,
           MONTH(U_Datum) AS Maand,
           MAX(U_Kg) AS TotGewicht
    FROM Uitgevoerdeoef
    WHERE Gebruikernr = ?
    AND U_Datum BETWEEN ? AND ?
";

$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss";

// Extra filter indien type2 is gekozen
if (!empty($type2)) {
    $query .= " AND U_Oeftype2 = ?";
    $params[] = $type2;
    $types .= "s";
}

// Groeperen per maand
$query .= "
    GROUP BY Jaar, Maand
    ORDER BY Jaar ASC, Maand ASC
";

// Query uitvoeren
$stmt = $mysqli->prepare($query);
$stmt->bind_param($types, ...$params);
$stmt->execute();
$result = $stmt->get_result();

// Resultaten opslaan per maand
$records = [];
while ($row = $result->fetch_assoc()) {
    $key = $row['Jaar'] . '-' . str_pad($row['Maand'], 2, '0', STR_PAD_LEFT);
    $records[$key] = (float)$row['TotGewicht'];
}

// Labels en data voorbereiden
$labels = [];
$data = [];

$start = new DateTime($begindatum);
$start->modify('first day of this month');
$end = new DateTime($einddatum);
$end->modify('first day of next month');

$interval = new DateInterval('P1M');
$period = new DatePeriod($start, $interval, $end);

// Elke maand invullen, ook als er geen data is
foreach ($period as $dt) {
    $key = $dt->format('Y-m');
    $labels[] = $dt->format('M Y');
    $data[] = $records[$key] ?? 0;
}

// Opruimen
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Gewicht per maand</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Gewicht getild per maand</h2>
<canvas id="gewichtChart" width="800" height="400"></canvas>

<script>
// Data naar JavaScript sturen
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

// Grafiek tekenen
const ctx = document.getElementById('gewichtChart').getContext('2d');
const gewichtChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Gewicht (kg)',
            data: dataValues,
            borderColor: 'rgba(255, 159, 64, 1)',
            backgroundColor: 'rgba(255, 159, 64, 0.2)',
            borderWidth: 2,
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: { title: { display: true, text: 'Maand / Jaar' } },
            y: { beginAtZero: true, title: { display: true, text: 'Gewicht (kg)' } }
        }
    }
});
</script>

</body>
</html>
