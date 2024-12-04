<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion
session_start();
$id_user = $_SESSION['user_id'];

// Connexion à la base de données
$conn = new mysqli($servername, $username, $pswd, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Récupérer les stages de l'utilisateur
$sql = "SELECT id, titre FROM stage WHERE id_user = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$stages = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $stages[] = $row;
    }
}
$stmt->close();
$conn->close();

// Vérifier s'il y a des messages d'erreur ou de succès dans l'URL
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajout d'un Compte-Rendu</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 30px;
            max-width: 800px;
        }
        .alert {
            margin-top: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .btn-submit {
            color: white;
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
        }
        .btn-submit:hover {
            color: #28a745;
            background-color: white;
            border-color: #28a745;
        }
        .btn-back {
            margin-top: 20px;
            color: white;
            background-color: #28a745;
            border-color: #28a745;
            border-radius: 5px;
            padding: 10px 20px;
            text-decoration: none;
        }
        .btn-back:hover {
            color: #28a745;
            background-color: white;
            border-color: #28a745;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../eleve.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        <h1 class="text-center">Ajout d'un Compte-Rendu</h1>

        <!-- Afficher les messages de succès ou d'erreur -->
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                Compte-rendu ajouté avec succès !
            </div>
        <?php elseif ($error == 1): ?>
            <div class="alert alert-danger" role="alert">
                Une erreur est survenue lors de l'ajout du compte-rendu. Veuillez réessayer.
            </div>
        <?php elseif ($error == 2): ?>
            <div class="alert alert-warning" role="alert">
                Tous les champs sont obligatoires. Veuillez remplir le formulaire.
            </div>
        <?php endif; ?>

        <form action="../script/submitCR.php" method="POST">
            <div class="form-group">
                <label for="stage">Sélectionnez un Stage :</label>
                <select class="form-control" id="stage" name="stage" required>
                    <option value="">-- Sélectionnez un Stage --</option>
                    <?php foreach ($stages as $stage): ?>
                        <option value="<?php echo $stage['id']; ?>">
                            <?php echo htmlspecialchars($stage['titre']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label for="sujet">Sujet :</label>
                <input type="text" class="form-control" id="sujet" name="sujet" required>
            </div>
            <div class="form-group">
                <label for="dateCR">Date du Compte-Rendu :</label>
                <input type="date" class="form-control" id="dateCR" name="dateCR" required>
            </div>
            <div class="form-group">
                <label for="contenu">Contenu du Compte-Rendu :</label>
                <textarea class="form-control" id="contenu" name="contenu" rows="5" required></textarea>
            </div>
            <button type="submit" class="btn-submit">Soumettre</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>