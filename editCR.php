<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion

// Vérifier si l'ID du compte-rendu est passé en paramètre dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID du compte-rendu non spécifié.");
}

$id_cr = intval($_GET['id']); // Récupérer l'ID du compte-rendu

// Connexion à la base de données
$conn = new mysqli($servername, $username, $pswd, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Requête pour récupérer les informations du compte-rendu
$sql = "SELECT cr.sujet, cr.contenu, stage.titre AS titre_stage 
        FROM cr 
        JOIN stage ON cr.id_stage = stage.id 
        WHERE cr.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_cr);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Compte-rendu introuvable.");
}

$cr = $result->fetch_assoc();
$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Compte-Rendu</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 800px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .form-group {
            margin-bottom: 25px;
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
            margin-bottom: 20px;
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
        .info-section {
            padding: 15px;
            background-color: #f1f1f1;
            border-radius: 5px;
            margin-bottom: 20px;
        }
        .info-section h5 {
            margin-bottom: 10px;
            font-weight: bold;
        }
        .info-section p {
            margin: 0;
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="modifCR.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        <h1 class="text-center mb-4">Modifier un Compte-Rendu</h1>

        <div class="info-section">
            <h5>Sujet du Compte-Rendu :</h5>
            <p><?php echo htmlspecialchars($cr['sujet']); ?></p>
        </div>
        <div class="info-section">
            <h5>Stage Lié :</h5>
            <p><?php echo htmlspecialchars($cr['titre_stage']); ?></p>
        </div>

        <form action="../script/updateCR.php" method="POST">
            <input type="hidden" name="id_cr" value="<?php echo $id_cr; ?>"> <!-- Champ caché pour l'ID du compte-rendu -->
            <div class="form-group">
                <label for="contenu"><strong>Contenu du Compte-Rendu :</strong></label>
                <textarea class="form-control" id="contenu" name="contenu" rows="10" required><?php echo htmlspecialchars($cr['contenu']); ?></textarea>
            </div>
            <div class="text-center">
                <button type="submit" class="btn-submit">Enregistrer les Modifications</button>
            </div>
        </form>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>