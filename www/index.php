<?php
session_start();
$ingelogd = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>

<html lang="en">

<head>   
    <!-- voegt speciale tekens toe aan de woordenschat -->
    <meta charset="UTF-8">
    <!-- zorgt dat alles deftig werkt op gsm -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-dark text-white">
    <!--navigatie balk in het zwart bovenaan -->
    <nav class="navbar navbar-dark bg-black shadow-sm p-3">
        <div class="container d-flex justify-content-between align-items-center">
        <a class="navbar-brand fw-semibold" href="../../index.php">Tracktive</a> 
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

    <header class="container text-center py-5">
        <h1 class="fw-bold">Welkom bij Tracktive</h1>
        <p class="lead">Kies een onderdeel om te starten.</p>
    </header>

    <section class="container py-4"> <!--hoofd sectie met 6 opties -->

        <div class="row g-4">

            <div class="col-12 col-md-6  col-lg-4   ">

                <div class="card bg-secondary text-white h-100">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">Start fitnesstraining</h5>

                        <p class="card-text flex-grow-1">Begin met kracht- en spiertraining.</p>

                        <a href="/code/fitness/start_fitness.php" class="btn btn-outline-light mt-auto">Start</a>


                    </div>

                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card bg-secondary text-white h-100">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">Start cardiotraining</h5>

                        <p class="card-text flex-grow-1">Start met lopen of fietsen.</p>

                        <a href="/code/cardio/start_cardio.php" class="btn btn-outline-light mt-auto">Start</a>

                    </div>

                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card bg-secondary text-white h-100">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">Wijzig trainingsschema</h5>

                        <p class="card-text flex-grow-1">Pas je persoonlijke schema aan.</p>

                        <a href="/code/wijzig_schema/overzicht_schemas.php" class="btn btn-outline-light mt-auto">Start</a>

                    </div>

                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card bg-secondary text-white h-100">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">Lijst oefeningen</h5>

                        <p class="card-text flex-grow-1">Bekijk alle beschikbare oefeningen.</p>

                        <a href="/code/lijst_oefeningen/overzicht_oefeningen.php" class="btn btn-outline-light mt-auto">Start</a>

                    </div>

                </div>

            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card bg-secondary text-white h-100">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">Lichaamsmeting</h5>

                        <p class="card-text flex-grow-1">Meet gewicht, lichaamsmetingen en meer.</p>

                        <a href="/code/lichaamsmetingen/lichaamsmetingen.php" class="btn btn-outline-light mt-auto">Start</a>

                    </div>

                </div>
            </div>

            <div class="col-12 col-md-6 col-lg-4">

                <div class="card bg-secondary text-white h-100">

                    <div class="card-body d-flex flex-column">

                        <h5 class="card-title">Analyse</h5>

                        <p class="card-text flex-grow-1">Bekijk je vooruitgang via grafieken.</p>

                        <a href="/code/analyse/keuze_grafiek.php" class="btn btn-outline-light mt-auto">Start</a> 

                    </div>

                </div>

            </div>

 

        </div>

    </section>

    <footer class="text-center py-3 mt-4">

        <p class="m-0">© Tracktive</p>

    </footer>


</body>

</html>