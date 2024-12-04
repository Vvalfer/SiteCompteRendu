<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion
session_start();

$id_user = $_SESSION['user_id'];

// Vérifier si les données sont envoyées par la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_stage = isset($_POST['stage']) ? intval($_POST['stage']) : 0;
    $dateCR = isset($_POST['dateCR']) ? $_POST['dateCR'] : '';
    $contenu = isset($_POST['contenu']) ? trim($_POST['contenu']) : '';
    $sujet = isset($_POST['sujet']) ? trim($_POST['sujet']) : '';
    $dateM = isset($_POST['dateCR']) ? $_POST['dateCR'] : '';

    // Vérifier que toutes les données nécessaires sont présentes
    if ($id_stage > 0 && !empty($dateCR) && !empty($contenu)) {
        // Connexion à la base de données
        $conn = new mysqli($servername, $username, $pswd, $dbname);
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        // Préparer et exécuter la requête d'insertion
        $sql = "INSERT INTO cr (sujet, dateCR, contenu, date_modif, id_user, id_stage) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);

        $stmt->bind_param("ssssii", $sujet, $dateCR, $contenu, $dateM, $id_user, $id_stage);
        
        if ($stmt->execute()) {
            // Rediriger vers la page eleve.php avec un message de succès
            header("Location: ../Eleve/ajoutCR.php?success=1");
        } else {
            // Rediriger avec un message d'erreur
            header("Location: ../Eleve/ajoutCR.php?error=1");
        }

        $stmt->close();
        $conn->close();
    } else {
        // Rediriger si des champs sont manquants
        header("Location: ../Eleve/ajoutCR.php?error=2");
    }
} else {
    // Rediriger si la requête n'est pas POST
    header("Location: ../Eleve/ajoutCR.php");
}
exit();