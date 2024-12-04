<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion

// Connexion à la base de données
$conn = new mysqli($servername, $username, $pswd, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Requête SQL pour récupérer les comptes-rendus avec le nom et prénom de chaque élève
$sql = "SELECT 
            users.nom, 
            users.prenom, 
            cr.dateCR, 
            cr.sujet 
        FROM cr 
        JOIN users ON cr.id_user = users.id";

$result = $conn->query($sql);

$comptesRendus = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $comptesRendus[] = $row;
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Comptes-Rendus des Élèves</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <style>
        .container {
            margin-top: 30px;
            max-width: 95%;
        }
        .table-container {
            margin-top: 20px;
            max-height: none;
            overflow: visible;
        }

        .btn-back {
            margin-bottom: 20px;
            border-radius: 5px;
            color: white;
            background-color: #28a745;
            border-color: #28a745;
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
        <a href="../prof.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        <h1 class="text-center">Comptes-Rendus des Élèves</h1>
        <div class="table-container">
            <table id="crTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date du Compte-Rendu</th>
                        <th>Sujet</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($comptesRendus as $cr): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($cr['nom']); ?></td>
                            <td><?php echo htmlspecialchars($cr['prenom']); ?></td>
                            <td><?php echo htmlspecialchars($cr['dateCR']); ?></td>
                            <td><?php echo htmlspecialchars($cr['sujet']); ?></td>
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