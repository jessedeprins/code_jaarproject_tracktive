<?php
// Zet foutmeldingen aan (handig tijdens ontwikkelen)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Controleert of gebruiker ingelogd is
require '../../code/login/auth.php';

// Haal gebruikernummer uit POST (formulier)
$gebruikernummer = isset($_POST['gebruikernummer']) ? (int)$_POST['gebruikernummer'] : 0;

// Haal type op (lopen/fietsen)
$type2          = $_POST['type2'] ?? '';

// Haal begin- en einddatum op
$begindatum     = $_POST['begindatum'] ?? '';
$einddatum      = $_POST['einddatum'] ?? '';

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


// SQL query: berekent totale afstand per maand (in km)
$query = "
SELECT YEAR(U_Datum) AS Jaar,
       MONTH(U_Datum) AS Maand,
       SUM(U_Afstand) AS TotAfstand
FROM Uitgevoerdeoef
WHERE Gebruikernr = ?
AND U_Datum BETWEEN ? AND ?
";

// Parameters voor prepared statement
$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss"; // i = integer, s = string

// Als type gekozen is (lopen/fietsen), voeg extra filter toe
if (!empty($type2)) {
    $query .= " AND U_Oeftype2 = ?";
    $params[] = $type2;
    $types .= "s";
}

// Groepeer per jaar en maand en sorteer
$query .= "
GROUP BY Jaar, Maand
ORDER BY Jaar ASC, Maand ASC
";

// Prepare statement (veilig tegen SQL injectie)
$stmt = $mysqli->prepare($query);

// Koppel parameters aan query
$stmt->bind_param($types, ...$params);

// Voer query uit
$stmt->execute();

// Haal resultaten op
$result = $stmt->get_result();

// Array om resultaten per maand op te slaan
$records = [];
while ($row = $result->fetch_assoc()) {

    // Maak sleutel zoals "2025-03"
    $key = $row['Jaar'] . '-' . str_pad($row['Maand'], 2, '0', STR_PAD_LEFT);

    // Sla totale afstand op (float omdat km decimalen kan hebben)
    $records[$key] = (float)$row['TotAfstand'];
}

// Arrays voor grafiek
$labels = [];
$data   = [];

// Zet begin- en einddatum om naar DateTime objecten
$start = new DateTime($begindatum);
$end   = new DateTime($einddatum);

// Zorg dat laatste maand ook inbegrepen wordt
$end->modify('first day of next month');

// Interval van 1 maand
$interval = new DateInterval('P1M');

// Maak reeks van maanden
$period   = new DatePeriod($start, $interval, $end);

// Loop door elke maand (ook zonder data)
foreach ($period as $dt) {

    // Sleutel zoals "2025-03"
    $key = $dt->format('Y-m');

    // Label voor grafiek (bv "Mar 2025")
    $labels[] = $dt->format('M Y');

    // Als geen data → 0
    $data[] = $records[$key] ?? 0;
}

// Sluit statement en database verbinding
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Sportafstand per maand</title>

<!-- Chart.js library voor grafieken -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<h2>Sportafstand per maand (km)</h2>

<!-- Canvas waar de grafiek in getekend wordt -->
<canvas id="afstandChart" width="800" height="400"></canvas>

<script>
// Labels (maanden) vanuit PHP
const labels = <?php echo json_encode($labels); ?>;

// Data (afstand in km) vanuit PHP
const dataValues = <?php echo json_encode($data); ?>;

// Haal canvas context op
const ctx = document.getElementById('afstandChart').getContext('2d');

// Maak een lijn grafiek
const afstandChart = new Chart(ctx, {
    type: 'line',

    data: {
        labels: labels,
        datasets: [{
            label: 'Totale afstand (km)',
            data: dataValues,

            // Kleur van de lijn
            borderColor: 'rgba(75, 192, 192, 1)',

            // Opvulling onder de lijn
            backgroundColor: 'rgba(75, 192, 192, 0.2)',

            borderWidth: 2,

            // Zorgt voor vloeiende lijn
            tension: 0.2,

            // Vul onder de lijn
            fill: true
        }]
    },

    options: {
        responsive: true,

        scales: {
            x: {
                title: {
                    display: true,
                    text: 'Maand / Jaar'
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Afstand (km)'
                }
            }
        }
    }
});
</script>

</body>
</html>