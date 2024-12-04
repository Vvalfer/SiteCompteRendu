<!DOCTYPE html>
<html lang="fr">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Élèves</title>
    <style>
        .btn-outline-success {
            border-radius: 10px;
            color: white;
            background-color: #28a745;
            border-color: #28a745;
        }
        .btn-outline-success:hover {
            color: #28a745;
            background-color: white;
            border-color: #28a745;
        }
        .search-bar {
            border-radius: 10px;
            border-width: 2px;
            border-color: #808080;
        }
        .table-container {
            margin-top: 50px;
            width: 100%;
            max-width: 1200px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .dataTables_filter {
            text-align: center;
        }
        .dataTables_filter input {
            display: inline-block;
            width: auto;
        }
    </style>
    <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>
<body>
    <header>
        <h1 style="text-align: center; margin-top: 20px;">Liste des Élèves</h1>
        <div style="text-align: center; margin-top: 20px;">
            <button class="btn btn-outline-success" style="margin-left: 50px;" onclick="window.history.back();">
                <i class="fas fa-arrow-left"></i> Retour
            </button>
        </div>
    </header>
    <main>  
        <div class="container table-container">
            <div class="d-flex justify-content-center mb-3">
                <div id="studentsTable_filter" class="dataTables_filter"></div>
            </div>
            <table id="studentsTable" class="table table-striped">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Date de naissance</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    include '../../conf.php';
                    $conn = new mysqli($servername, $username, $pswd, $dbname);
                    if ($conn->connect_error) {
                        die("Connection failed: " . $conn->connect_error);
                    }

                    $sql = "SELECT id, nom, prenom, dateN FROM users WHERE id_statut = 1";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        while($row = $result->fetch_assoc()) {
                            echo "<tr data-id='" . htmlspecialchars($row['id']) . "'>";
                            echo "<td>" . htmlspecialchars($row['nom']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['prenom']) . "</td>";
                            echo "<td>" . htmlspecialchars($row['dateN']) . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='3'>Aucun élève trouvé</td></tr>";
                    }

                    $conn->close();
                    ?>
                </tbody>
            </table>
        </div>
    </main>
    <script>
        $(document).ready(function() {
            $('#studentsTable').DataTable({
                "language": {
                    "lengthMenu": "Afficher _MENU_ élèves par page",
                    "zeroRecords": "Aucun élève trouvé",
                    "info": "Affichage de la page _PAGE_ sur _PAGES_",
                    "infoEmpty": "Aucun élève disponible",
                    "infoFiltered": "(filtré à partir de _MAX_ élèves au total)",
                    "search": "Rechercher:",
                    "paginate": {
                        "next": "Suivant",
                        "previous": "Précédent"
                    }
                },
                "dom": '<"top"f>rt<"bottom"lp><"clear">',
                "pagingType": "simple"
            });

            // lignes cliquables
            $('#studentsTable tbody').on('click', 'tr', function() {
                var id = $(this).data('id');
                if (id) {
                    window.location.href = 'SpecEleve.php?id=' + id;
                }
            });
        });
    </script>
</body>
</html>