<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion

// Vérifier si les données sont envoyées par la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_user = isset($_POST['id_user']) ? intval($_POST['id_user']) : 0;
    $titre = isset($_POST['titre']) ? trim($_POST['titre']) : '';
    $date_debut = isset($_POST['date_debut']) ? $_POST['date_debut'] : '';
    $date_fin = isset($_POST['date_fin']) ? $_POST['date_fin'] : '';
    $entreprise = isset($_POST['entreprise']) ? trim($_POST['entreprise']) : '';
    $tuteur = isset($_POST['tuteur']) ? trim($_POST['tuteur']) : '';
    $tel_tuteur = isset($_POST['tel_tuteur']) ? trim($_POST['tel_tuteur']) : '';
    $adresse = isset($_POST['adresse']) ? trim($_POST['adresse']) : '';
    $ville = isset($_POST['ville']) ? trim($_POST['ville']) : '';

    // Vérifier que toutes les données nécessaires sont présentes
    if ($id_user > 0 && !empty($titre) && !empty($date_debut) && !empty($date_fin) && !empty($entreprise) && !empty($tuteur) && !empty($tel_tuteur) && !empty($adresse) && !empty($ville)) {
        // Connexion à la base de données
        $conn = new mysqli($servername, $username, $pswd, $dbname);
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        // Préparer et exécuter la requête d'insertion
        $sql = "INSERT INTO stage (titre, dateD, dateF, nom_entreprise, nom_tuteur, tel_tuteur, addresse, ville, id_user) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssi", $titre, $date_debut, $date_fin, $entreprise, $tuteur, $tel_tuteur, $adresse, $ville, $id_user);

        if ($stmt->execute()) {
            // Rediriger vers la page eleve.php avec un message de succès
            header("Location: ../Eleve/createStage.php?success=1");
        } else {
            // Rediriger avec un message d'erreur
            header("Location: ../Eleve/createStage.php?error=1");
        }

        $stmt->close();
        $conn->close();
    } else {
        // Rediriger si des champs sont manquants
        header("Location: ../Eleve/createStage.php?error=2");
    }
} else {
    // Rediriger si la requête n'est pas POST
    header("Location: ../Eleve/createStage.php");
}
exit();