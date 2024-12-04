<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion
session_start();

$id_user = $_SESSION['user_id'];

// Connexion à la base de données
$conn = new mysqli($servername, $username, $pswd, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Requête SQL pour récupérer les comptes-rendus de l'utilisateur
$sql= "SELECT cr.id, cr.sujet, cr.dateCR, stage.titre AS titre_stage 
FROM cr 
JOIN stage ON cr.id_stage = stage.id 
WHERE cr.id_user = ?;";

$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();

$comptesRendus = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comptesRendus[] = $row;
    }
}

$stmt->close();
$conn->close();

$success = isset($_GET['success']) ? $_GET['success'] : null;
$error = isset($_GET['error']) ? $_GET['error'] : null;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Compte-Rendu</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 30px;
            max-width: 95%; /* Largeur pour afficher plus de contenu */
        }
        .table-container {
            margin-top: 20px;
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
        tr {
            cursor: pointer; /* Curseur pointer pour indiquer que la ligne est cliquable */
        }
    </style>
</head>
<body>
    <div class="container">
        <a href="../eleve.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        <h1 class="text-center">Modifier un Compte-Rendu</h1>
        <div class="table-container">
        <?php if ($success): ?>
            <div class="alert alert-success" role="alert">
                Compte-rendu modifié avec succès !
            </div>
        <?php elseif ($error == 1): ?>
            <div class="alert alert-danger" role="alert">
                Une erreur est survenue lors de la modification du compte-rendu. Veuillez réessayer.
            </div>
        <?php elseif ($error == 2): ?>
            <div class="alert alert-warning" role="alert">
                Tous les champs sont obligatoires. Veuillez remplir le formulaire.
            </div>
        <?php endif; ?>
            <table id="crTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Date du Compte-Rendu</th>
                        <th>Sujet</th>
                        <th>Stage</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptesRendus as $cr): ?>
                        <tr onclick="window.location.href='editCR.php?id=<?php echo $cr['id']; ?>'">
                            <td><?php echo htmlspecialchars($cr['dateCR']); ?></td>
                            <td><?php echo htmlspecialchars($cr['sujet']); ?></td>
                            <td><?php echo htmlspecialchars($cr['titre_stage']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#crTable').DataTable({
                "pageLength": 10, // Nombre de lignes par page par défaut
                "lengthMenu": [10, 20, 50, 100], // Options pour le nombre de lignes par page
                "language": {
                    "lengthMenu": "Afficher _MENU_ comptes-rendus par page",
                    "zeroRecords": "Aucun compte-rendu trouvé",
                    "info": "Affichage de la page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun compte-rendu disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ comptes-rendus au total)",
                    "search": "Rechercher :",
                    "paginate": {
                        "next": "Suivant",
                        "previous": "Précédent"
                    }
                },
                "dom": '<"top"f>rt<"bottom"lp><"clear">', // Placement des éléments de DataTables
                "pagingType": "simple"
            });
        });
    </script>
</body>
</html>