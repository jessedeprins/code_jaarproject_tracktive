<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tracktive</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <!-- Registratiekaart -->
            <div class="card p-4 shadow-sm">
                <h3 class="text-center mb-4">Registratie</h3>

                <!-- Registratieformulier -->
                <form action="loginaanmakenphp.php" method="post">

                    <!-- E-mailadres -->
                    <p>E-mailadres</p>
                    <div class="mb-3">
                        <input type="email" name="email_adress" class="form-control"
                               placeholder="E-mailadres" required>
                    </div>

                    <!-- Voornaam -->
                    <p>Voornaam</p>
                    <div class="mb-3">
                        <input type="text" name="Voornaam" class="form-control"
                               placeholder="Voornaam" required>
                    </div>

                    <!-- Familienaam -->
                    <p>Familienaam</p>
                    <div class="mb-3">
                        <input type="text" name="Familienaam" class="form-control"
                               placeholder="Familienaam" required>
                    </div>

                    <!-- Geboortedatum -->
                    <p>Geboortedatum</p>
                    <div class="mb-3">
                        <input type="date" name="Geboortedatum" class="form-control" required>
                    </div>

                    <!-- Geslacht -->
                    <p>Geslacht</p>
                    <div class="mb-3">
                        <select name="Geslacht" class="form-select" required>
                            <option value="Man">Man</option>
                            <option value="Vrouw">Vrouw</option>
                            <option value="Anders">X</option>
                        </select>
                    </div>

                    <!-- Wachtwoord -->
                    <p>Wachtwoord</p>
                    <div class="mb-3">
                        <input type="password" name="Wachtwoord" class="form-control"
                               placeholder="Paswoord" required>
                    </div>

                    <!-- Registreren -->
                    <button type="submit" class="btn btn-primary w-100">
                        Registreren
                    </button>

                </form>
            </div>

        </div>
    </div>
</div>

</body>
</html>
