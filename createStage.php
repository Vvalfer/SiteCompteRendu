<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion

// Vérifier l'ID de l'utilisateur (vous pouvez adapter selon votre logique d'authentification)
session_start();
$id_user = $_SESSION['user_id'];

// Vérifier s'il y a des messages d'erreur ou de succès dans l'URL
$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Créer un Stage</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 30px;
            max-width: 800px;
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
        <h1 class="text-center">Créer un Stage</h1>
        <!-- Afficher les messages de succès ou d'erreur -->
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                Stage ajouté avec succès !
            </div>
        <?php elseif ($error == 1): ?>
            <div class="alert alert-danger" role="alert">
                Une erreur est survenue lors de l'ajout du stage. Veuillez réessayer.
            </div>
        <?php elseif ($error == 2): ?>
            <div class="alert alert-warning" role="alert">
                Tous les champs sont obligatoires. Veuillez remplir le formulaire.
            </div>
        <?php endif; ?>
        <form action="script/submitStage.php" method="POST">
            <input type="hidden" name="id_user" value="<?php echo $id_user; ?>"> <!-- Champ caché pour l'ID de l'utilisateur -->
            <div class="form-group">
                <label for="titre">Titre du Stage :</label>
                <input type="text" class="form-control" id="titre" name="titre" required>
            </div>
            <div class="form-group">
                <label for="date_debut">Date de Début :</label>
                <input type="date" class="form-control" id="date_debut" name="date_debut" required>
            </div>
            <div class="form-group">
                <label for="date_fin">Date de Fin :</label>
                <input type="date" class="form-control" id="date_fin" name="date_fin" required>
            </div>
            <div class="form-group">
                <label for="entreprise">Nom de l'Entreprise :</label>
                <input type="text" class="form-control" id="entreprise" name="entreprise" required>
            </div>
            <div class="form-group">
                <label for="tuteur">Nom du Tuteur :</label>
                <input type="text" class="form-control" id="tuteur" name="tuteur" required>
            </div>
            <div class="form-group">
                <label for="tel_tuteur">Téléphone du Tuteur :</label>
                <input type="tel" class="form-control" id="tel_tuteur" name="tel_tuteur" required>
            </div>
            <div class="form-group">
                <label for="adresse">Adresse :</label>
                <input type="text" class="form-control" id="adresse" name="adresse" required>
            </div>
            <div class="form-group">
                <label for="ville">Ville :</label>
                <input type="text" class="form-control" id="ville" name="ville" required>
            </div>
            <button type="submit" class="btn-submit">Créer le Stage</button>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>