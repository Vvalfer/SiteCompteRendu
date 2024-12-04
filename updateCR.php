<?php
include '../../conf.php'; // Inclure le fichier de configuration pour la connexion

// Vérifier si les données sont envoyées par la méthode POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérer les données du formulaire
    $id_cr = isset($_POST['id_cr']) ? intval($_POST['id_cr']) : 0;
    $contenu = isset($_POST['contenu']) ? trim($_POST['contenu']) : '';

    // Vérifier que l'ID du compte-rendu et le contenu sont présents
    if ($id_cr > 0 && !empty($contenu)) {
        // Connexion à la base de données
        $conn = new mysqli($servername, $username, $pswd, $dbname);
        if ($conn->connect_error) {
            die("Erreur de connexion : " . $conn->connect_error);
        }

        // Préparer la date actuelle
        $date_modif = date('Y-m-d H:i:s'); // Format de date et heure actuel

        // Préparer et exécuter la requête de mise à jour
        $sql = "UPDATE cr SET contenu = ?, date_modif = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssi", $contenu, $date_modif, $id_cr);

        if ($stmt->execute()) {
            // Rediriger vers la page modifCR.php avec un message de succès
            header("Location: ../Eleve/modifCR.php?success=1");
        } else {
            // Rediriger avec un message d'erreur
            header("Location: ../Eleve/editCR.php?id=$id_cr&error=1");
        }

        $stmt->close();
        $conn->close();
    } else {
        // Rediriger si des champs sont manquants
        header("Location: ../Eleve/editCR.php?id=$id_cr&error=2");
    }
} else {
    // Rediriger si la requête n'est pas POST
    header("Location: ../Eleve/modifCR.php");
}
exit();