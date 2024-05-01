<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrazione</title>
    <!-- Inclusione di Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1 class="mb-4">Registrazione</h1>
        <div class="card">
            <div class="card-body">
                <!-- Inserisci qui il codice PHP per il messaggio di errore -->
                <?php
                if (isset($_GET['error_message'])) {
                    echo '<div class="alert alert-danger">' . $_GET['error_message'] . '</div>';
                }
                ?>
                <!-- Fine del codice PHP -->
                <form action="php/registra.php" method="POST">
                    <div class="mb-3">
                        <label for="nome" class="form-label">Nome:</label>
                        <input type="text" class="form-control" id="nome" name="nome" required>
                    </div>
                    <div class="mb-3">
                        <label for="cognome" class="form-label">Cognome:</label>
                        <input type="text" class="form-control" id="cognome" name="cognome" required>
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" required>
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password:</label>
                        <input type="password" class="form-control" id="password" name="password" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Registra</button>
                </form>
            </div>
        </div>
        <!-- Aggiunta del pulsante per il reindirizzamento alla pagina di login -->
        <div class="mt-3">
            <a href="login.html" class="btn btn-secondary">Torna al login</a>
        </div>
    </div>

    <!-- Bootstrap JS Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
