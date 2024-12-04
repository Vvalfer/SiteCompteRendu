<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion

// Vérifier si l'ID est passé en paramètre dans l'URL
if (!isset($_GET['id']) || empty($_GET['id'])) {
    die("ID d'élève non spécifié.");
}

// Récupérer l'ID de l'élève depuis l'URL
$id = intval($_GET['id']);

// Connexion à la base de données
$conn = new mysqli($servername, $username, $pswd, $dbname);
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Requête SQL pour récupérer les informations de l'élève
$sql_eleve = "SELECT 
                users.nom, 
                users.prenom, 
                users.dateN, 
                users.mail, 
                users.tel, 
                statut.label AS statut_label
            FROM users
            JOIN statut ON users.id_statut = statut.id
            WHERE users.id = ?";
$stmt_eleve = $conn->prepare($sql_eleve);
$stmt_eleve->bind_param("i", $id);
$stmt_eleve->execute();
$result_eleve = $stmt_eleve->get_result();

if ($result_eleve->num_rows === 0) {
    die("Aucun élève trouvé avec cet ID.");
}

$eleve = $result_eleve->fetch_assoc();
$stmt_eleve->close();

// Requête SQL pour récupérer les stages de l'élève
$sql_stages = "SELECT id, titre, dateD, dateF, nom_entreprise, nom_tuteur, tel_tuteur, addresse, ville 
               FROM stage
               WHERE id_user = ?";
$stmt_stages = $conn->prepare($sql_stages);
$stmt_stages->bind_param("i", $id);
$stmt_stages->execute();
$result_stages = $stmt_stages->get_result();

$stages = [];
while ($stage = $result_stages->fetch_assoc()) {
    $stages[] = $stage;
}
$stmt_stages->close();

// Vérifier si l'ID de stage est passé, sinon prendre le premier stage de la liste
if (isset($_GET['id_stage']) && !empty($_GET['id_stage'])) {
    $id_stage_selected = intval($_GET['id_stage']);
} elseif (count($stages) > 0) {
    // Si aucun id_stage n'est passé mais qu'il y a des stages disponibles
    $id_stage_selected = $stages[0]['id'];
} else {
    // Si aucun stage n'est disponible, on met une valeur par défaut qui sera utilisée pour indiquer l'absence de stages
    $id_stage_selected = null;
}

// Requête pour récupérer les informations du compte-rendu, en ajoutant la condition sur l'élève et le stage liés
$comptes_rendus = [];
if ($id_stage_selected !== null) {
    $sql_cr = "SELECT cr.id, cr.sujet, cr.contenu, cr.dateCR, cr.commentaire, cr.id_stage, stage.titre AS titre_stage 
               FROM cr 
               JOIN stage ON cr.id_stage = stage.id 
               WHERE cr.id_stage = ?";
    $stmt_cr = $conn->prepare($sql_cr);
    if ($stmt_cr === false) {
        die("Erreur de préparation de la requête : " . $conn->error);
    }
    $stmt_cr->bind_param("i", $id_stage_selected);
    $stmt_cr->execute();
    $result_cr = $stmt_cr->get_result();

    if ($result_cr->num_rows > 0) {
        while ($row = $result_cr->fetch_assoc()) {
            $comptes_rendus[] = $row;
        }
    }
    $stmt_cr->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Détails de l'élève</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
            max-width: 1000px;
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0px 0px 15px rgba(0, 0, 0, 0.1);
        }
        .header-section {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .header-section h1 {
            margin: 0;
        }
        .btn-back {
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
        .card {
            margin-bottom: 20px;
            border-radius: 10px;
            border: 1px solid #28a745;
        }
        .card-header {
            background-color: #28a745;
            color: white;
            font-weight: bold;
            border-radius: 10px 10px 0 0;
        }
        .pagination {
            justify-content: center;
        }
        .stages-section,
        .cr-section {
            margin-bottom: 40px;
        }
        .stage-card {
            margin-bottom: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header-section">
            <h1>Détails de l'élève</h1>
            <a href="../prof.php" class="btn-back"><i class="fas fa-arrow-left"></i> Retour</a>
        </div>

        <!-- Informations de l'élève -->
        <div class="card mt-4">
            <div class="card-header text-center">
                Informations de l'élève
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <p><strong>Nom :</strong> <?php echo htmlspecialchars($eleve['nom']); ?></p>
                        <p><strong>Prénom :</strong> <?php echo htmlspecialchars($eleve['prenom']); ?></p>
                        <p><strong>Date de Naissance :</strong> <?php echo htmlspecialchars($eleve['dateN']); ?></p>
                    </div>
                    <div class="col-md-6">
                        <p><strong>Email :</strong> <?php echo htmlspecialchars($eleve['mail']); ?></p>
                        <p><strong>Téléphone :</strong> <?php echo htmlspecialchars($eleve['tel']); ?></p>
                        <p><strong>Statut :</strong> <?php echo htmlspecialchars($eleve['statut_label']); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Stages de l'élève avec pagination -->
            <div class="col-md-6 stages-section">
                <h3 class="text-center">Stages</h3>
                <div id="stages-container">
                    <!-- Les stages seront insérés ici via JavaScript -->
                </div>
                <nav>
                    <ul class="pagination" id="stages-pagination-container">
                        <!-- Pagination sera insérée ici via JavaScript -->
                    </ul>
                </nav>
            </div>

            <!-- Comptes-rendus de stage avec pagination -->
            <div class="col-md-6 cr-section">
                <h3 class="text-center">Comptes-Rendus</h3>
                <div id="cr-container" class="mt-4">
                    <!-- Les comptes-rendus seront insérés ici via JavaScript -->
                </div>
                <nav>
                    <ul class="pagination" id="cr-pagination-container">
                        <!-- Pagination sera insérée ici via JavaScript -->
                    </ul>
                </nav>
            </div>
        </div>
    </div>

    <script>
        // Transmettre les stages et comptes-rendus au script JS
        var stages = <?php echo json_encode($stages); ?>;
        var comptesRendus = <?php echo json_encode($comptes_rendus); ?>;
        
        // Variables de pagination
        var itemsPerPage = 2;
        var currentStagePage = 1;
        var currentCRPage = 1;

        // Afficher les stages avec pagination
        function displayStages() {
            var container = document.getElementById('stages-container');
            container.innerHTML = '';

            if (stages.length === 0) {
                container.innerHTML = '<p>Aucun stage trouvé pour cet élève.</p>';
                return;
            }

            var startIndex = (currentStagePage - 1) * itemsPerPage;
            var endIndex = startIndex + itemsPerPage;
            var totalPages = Math.ceil(stages.length / itemsPerPage);

            stages.slice(startIndex, endIndex).forEach(function(stage) {
                container.innerHTML += `
                    <div class="card stage-card">
                        <div class="card-header">
                            Stage : ${stage.titre}
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-12">
                                    <p><strong>Entreprise :</strong> ${stage.nom_entreprise}</p>
                                    <p><strong>Dates :</strong> ${stage.dateD} - ${stage.dateF}</p>
                                    <p><strong>Tuteur :</strong> ${stage.nom_tuteur} (${stage.tel_tuteur})</p>
                                    <p><strong>Adresse :</strong> ${stage.addresse}, ${stage.ville}</p>
                                </div>
                            </div>
                            <a href="?id=<?php echo $id; ?>&tab=cr&id_stage=${stage.id}" class="btn btn-outline-success mt-3">Voir les Comptes-Rendus</a>
                        </div>
                    </div>
                `;
            });

            updateStagePagination(totalPages);
        }

        // Mettre à jour la pagination des stages
        function updateStagePagination(totalPages) {
            var paginationContainer = document.getElementById('stages-pagination-container');
            paginationContainer.innerHTML = '';
            for (var i = 1; i <= totalPages; i++) {
                var li = document.createElement('li');
                li.className = 'page-item ' + (i === currentStagePage ? 'active' : '');
                var a = document.createElement('a');
                a.className = 'page-link';
                a.href = '#';
                a.textContent = i;
                a.onclick = (function(page) {
                    return function(event) {
                        event.preventDefault();
                        currentStagePage = page;
                        displayStages();
                    };
                })(i);
                li.appendChild(a);
                paginationContainer.appendChild(li);
            }
        }

        // Afficher les comptes-rendus selon l'ID du stage sélectionné
        function displayComptesRendus(stageId) {
            var container = document.getElementById('cr-container');
            container.innerHTML = '';

            if (!comptesRendus || comptesRendus.length === 0) {
                container.innerHTML = '<p>Aucun compte-rendu trouvé pour ce stage.</p>';
                return;
            }

            var filteredComptesRendus = comptesRendus.filter(function(cr) {
                return cr.id_stage == stageId;
            });
            var totalPages = Math.ceil(filteredComptesRendus.length / itemsPerPage);

            function updateCRContainer() {
                container.innerHTML = '';
                var startIndex = (currentCRPage - 1) * itemsPerPage;
                var endIndex = startIndex + itemsPerPage;
                filteredComptesRendus.slice(startIndex, endIndex).forEach(function(cr) {
                    container.innerHTML += `
                        <div class="card mt-3">
                            <div class="card-body">
                                <p><strong>Sujet :</strong> ${cr.sujet}</p>
                                <p><strong>Date :</strong> ${cr.dateCR}</p>
                                <p><strong>Contenu :</strong> ${cr.contenu}</p>
                                <p><strong>Commentaire :</strong> ${cr.commentaire}</p>
                            </div>
                        </div>
                    `;
                });
            }

            function updateCRPagination() {
                var paginationContainer = document.getElementById('cr-pagination-container');
                paginationContainer.innerHTML = '';
                for (var i = 1; i <= totalPages; i++) {
                    var li = document.createElement('li');
                    li.className = 'page-item ' + (i === currentCRPage ? 'active' : '');
                    var a = document.createElement('a');
                    a.className = 'page-link';
                    a.href = '#';
                    a.textContent = i;
                    a.onclick = (function(page) {
                        return function(event) {
                            event.preventDefault();
                            currentCRPage = page;
                            updateCRContainer();
                            updateCRPagination();
                        };
                    })(i);
                    li.appendChild(a);
                    paginationContainer.appendChild(li);
                }
            }

            updateCRContainer();
            updateCRPagination();
        }

        // Appeler la fonction d'affichage avec l'ID du stage sélectionné
        document.addEventListener("DOMContentLoaded", function () {
            // Obtenir l'id_stage depuis l'URL
            const urlParams = new URLSearchParams(window.location.search);
            const id_stage_selected = urlParams.get('id_stage');

            if (id_stage_selected) {
                displayComptesRendus(id_stage_selected);
            }
            displayStages();
        });
    </script>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>