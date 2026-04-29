<?php
// Zet foutmeldingen aan (handig voor debugging)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Haal input uit POST
$gebruikernummer = isset($_POST['gebruikernummer']) ? (int)$_POST['gebruikernummer'] : 0;
$type2          = $_POST['type2'] ?? '';
$begindatum     = $_POST['begindatum'] ?? '';
$einddatum      = $_POST['einddatum'] ?? '';

// Controle op verplichte velden
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

// Controle op verbinding
if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}

// Zet karakterset op UTF-8
$mysqli->set_charset("utf8");


// Query: telt aantal oefeningen per maand
$query = "
SELECT YEAR(U_Datum) AS Jaar,
       MONTH(U_Datum) AS Maand,
       COUNT(Uitgevoerdeoefnr) AS TotAantal
FROM Uitgevoerdeoef
WHERE Gebruikernr = ?
AND U_Datum BETWEEN ? AND ?
";

// Parameters voor prepared statement
$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss";

// Extra filter op type (indien ingevuld)
if (!empty($type2)) {
    $query .= " AND U_Oeftype2 = ?";
    $params[] = $type2;
    $types .= "s";
}

// Groeperen per maand en sorteren
$query .= "
GROUP BY Jaar, Maand
ORDER BY Jaar ASC, Maand ASC
";

// Prepare statement (veilig tegen SQL injectie)
$stmt = $mysqli->prepare($query);

// Bind parameters aan query
$stmt->bind_param($types, ...$params);

// Voer query uit
$stmt->execute();

// Haal resultaten op
$result = $stmt->get_result();

// Array voor resultaten per maand
$records = [];
while ($row = $result->fetch_assoc()) {

    // Maak sleutel zoals "2025-03"
    $key = $row['Jaar'] . '-' . str_pad($row['Maand'], 2, '0', STR_PAD_LEFT);

    // Opslaan als integer (aantal oefeningen)
    $records[$key] = (int)$row['TotAantal'];
}

// Arrays voor grafiek
$labels = [];
$data   = [];

// Zet datums om naar DateTime objecten
$start = new DateTime($begindatum);
$end   = new DateTime($einddatum);

// Zorg dat laatste maand ook wordt meegenomen
$end->modify('first day of next month');

// Interval van 1 maand
$interval = new DateInterval('P1M');

// Maak een reeks van maanden
$period   = new DatePeriod($start, $interval, $end);

// Loop door alle maanden (ook zonder data)
foreach ($period as $dt) {

    // Key zoals "2025-03"
    $key = $dt->format('Y-m');

    // Label voor grafiek (bv "Mar 2025")
    $labels[] = $dt->format('M Y');

    // Als geen data → 0
    $data[] = $records[$key] ?? 0;
}

// Sluit database connectie
$stmt->close();
$mysqli->close();
?>

<!DOCTYPE html>
<html>
<head>
<title>Sportaantal per maand</title>

<!-- Chart.js voor grafieken -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>

<body>

<h2>Aantal oefeningen per maand</h2>

<!-- Canvas waar de grafiek wordt getekend -->
<canvas id="aantalChart" width="800" height="400"></canvas>

<script>

// Data vanuit PHP naar JavaScript
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

// Canvas context ophalen
const ctx = document.getElementById('aantalChart').getContext('2d');

// Grafiek maken
const aantalChart = new Chart(ctx, {
    type: 'line',

    data: {
        labels: labels,
        datasets: [{
            label: 'Totaal aantal oefeningen',
            data: dataValues,

            // Lijnkleur
            borderColor: 'rgba(153, 102, 255, 1)',

            // Achtergrondkleur
            backgroundColor: 'rgba(153, 102, 255, 0.2)',

            borderWidth: 2,

            // Vloeiende lijn
            tension: 0.2,

            // Opvullen onder lijn
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
                    text: 'Aantal'
                }
            }
        }
    }
});
</script>

</body>
</html>