<?php
// Logincontrole
require '../../code/login/auth.php';
?>
<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>

    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
</head>

<body class="bg-dark text-white">

    <!-- Navigatiebalk -->
    <nav class="navbar navbar-dark bg-black p-3 shadow-sm">
        <div class="container d-flex justify-content-between align-items-center">
            <a class="navbar-brand fw-semibold" href="../../index.php">Tracktive</a>

            <!-- Profielmenu -->
            <div class="dropdown">
                <button class="btn btn-outline-light dropdown-toggle" type="button" id="profielDropdown" data-bs-toggle="dropdown">
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

    <!-- Hoofdsectie -->
    <section class="container py-5">
        <h2 class="text-center fw-light mb-4">Analyse</h2>

        <div class="row g-4 justify-content-center">

            <!-- Lichaamsmetingen -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3">
                            <i class="bi bi-clipboard2-pulse"></i>
                        </div>
                        <h5 class="fw-semibold">Lichaamsmetingen</h5>
                        <p class="flex-grow-1">Bekijk uw progressie.</p>
                        <a href="analyse_lichaamsmetingen.php" class="btn btn-light fw-semibold">
                            Start <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Fitness -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3">
                            <i class="bi bi-bar-chart-line"></i>
                        </div>
                        <h5 class="fw-semibold">Fitness</h5>
                        <p class="flex-grow-1">Bekijk uw progressie.</p>
                        <a href="analyse_fitness.php" class="btn btn-light fw-semibold">
                            Start <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Cardio -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3">
                            <i class="bi bi-heart-pulse"></i>
                        </div>
                        <h5 class="fw-semibold">Cardio</h5>
                        <p class="flex-grow-1">Bekijk uw progressie.</p>
                        <a href="analyse_cardio.php" class="btn btn-light fw-semibold">
                            Start <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>


            <!-- geschiedenis -->
            <div class="col-12 col-md-6 col-lg-4">
                <div class="card bg-secondary text-white border-0 shadow-sm h-100">
                    <div class="card-body text-center d-flex flex-column">
                        <div class="display-4 mb-3">
                            <i class="bi bi-clock-history"></i>
                        </div>
                        <h5 class="fw-semibold">Geschiedenis</h5>
                        <p class="flex-grow-1">Bekijk uw geschiedenis.</p>
                        <a href="geschiedenis.php" class="btn btn-light fw-semibold">
                            Start <i class="bi bi-arrow-right-circle ms-1"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center py-3 mt-4 opacity-75">
        <p class="m-0">© Tracktive</p>
    </footer>

    <!-- Bootstrap scripts -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
