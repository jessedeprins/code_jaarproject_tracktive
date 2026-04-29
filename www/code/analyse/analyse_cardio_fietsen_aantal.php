<?php
// zet foutmeldingen aan zodat je de errors kan zien als je iets fout hebt -->
error_reporting(E_ALL);
ini_set('display_errors', 1);
require '../../code/login/auth.php'; //checkt of ie is ingelogd
//haalt de data op uit de post
$gebruikernummer = isset($_POST['gebruikernummer']) ? (int)$_POST['gebruikernummer'] : 0;
$type2          = $_POST['type2'] ?? '';
$begindatum     = $_POST['begindatum'] ?? '';
$einddatum      = $_POST['einddatum'] ?? '';
//checkt of alle verplichte velden ingevuld zijn
if (!$gebruikernummer || !$begindatum || !$einddatum) {
    die("Gebruikernummer, begin- en einddatum zijn verplicht.");
}

$host   = 'ID479738_Tracktive.db.webhosting.be';
$dbname = 'ID479738_Tracktive';
$user   = 'ID479738_Tracktive';
$pass   = 'Spike2021?';

$mysqli = new mysqli($host, $user, $pass, $dbname);
if ($mysqli->connect_error) {
    die("Database verbinding mislukt: " . $mysqli->connect_error);
}
$mysqli->set_charset("utf8");

// aantal oefeningen per maand tellen
$query = "
SELECT YEAR(U_Datum) AS Jaar,
       MONTH(U_Datum) AS Maand,
       COUNT(U_Oeftype2) AS TotAantal
FROM Uitgevoerdeoef
WHERE Gebruikernr = ?
AND U_Datum BETWEEN ? AND ?
";

$params = [$gebruikernummer, $begindatum, $einddatum];
$types = "iss"; // i=integer en s=string

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
    $records[$key] = (int)$row['TotAantal'];
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
<html>
<head>
<title>Sportaantal per maand</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>

<h2>Aantal oefeningen per maand</h2>
<canvas id="aantalChart" width="800" height="400"></canvas>

<script>
const labels = <?php echo json_encode($labels); ?>;
const dataValues = <?php echo json_encode($data); ?>;

const ctx = document.getElementById('aantalChart').getContext('2d');
const aantalChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: labels,
        datasets: [{
            label: 'Totaal aantal oefeningen',
            data: dataValues,
            borderColor: 'rgba(153, 102, 255, 1)',
            backgroundColor: 'rgba(153, 102, 255, 0.2)',
            borderWidth: 2,
            tension: 0.2,
            fill: true
        }]
    },
    options: {
        responsive: true,
        scales: {
            x: { title: { display: true, text: 'Maand / Jaar' } },
            y: { beginAtZero: true, title: { display: true, text: 'Aantal' } }
        }
    }
});
</script>

</body>
</html>