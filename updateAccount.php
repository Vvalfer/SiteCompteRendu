<?php
session_start();
include '../../conf.php';

// Vérifiez si l'utilisateur est connecté
if (!isset($_SESSION['user_id'])) {
    header("Location: ../form.php");
    exit();
}

// Vérifiez si le formulaire a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Récupérez les données du formulaire
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $surname = isset($_POST['surname']) ? $_POST['surname'] : '';
    $birthdate = isset($_POST['birthdate']) ? $_POST['birthdate'] : '';
    $email = isset($_POST['mail']) ? $_POST['mail'] : '';
    $phone = isset($_POST['tel']) ? $_POST['tel'] : '';

    // Récupérez l'ID de l'utilisateur depuis la session
    $user_id = $_SESSION['user_id'];

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $pswd, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer et exécuter la requête de mise à jour
    $stmt = $conn->prepare("UPDATE users SET nom = ?, prenom = ?, dateN = ?, mail = ?, tel = ? WHERE id = ?");
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }
    $stmt->bind_param("sssssi", $name, $surname, $birthdate, $email, $phone, $user_id);
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    $stmt->close();
    $conn->close();

    // Mettre à jour les informations de session
    $_SESSION['nome'] = $name;
    $_SESSION['prenom'] = $surname;
    $_SESSION['dateN'] = $birthdate;
    $_SESSION['mail'] = $email;
    $_SESSION['tel'] = $phone;

    // Rediriger vers la page de compte avec un message de succès
    header("Location: ../myAccount.php?update=success");
    exit();
} else {
    // Rediriger vers la page de compte si le formulaire n'a pas été soumis
    header("Location: ../myAccount.php");
    exit();
}
?>