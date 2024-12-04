<?php
include '../../conf.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50)); // Génération token

    $conn = new mysqli($servername, $username, $pswd, $dbname);

    // Vérifiez la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE users SET reset_token = ?, reset_expires = DATE_ADD(NOW(), INTERVAL 1 HOUR) WHERE login = ?");
    
    // Vérifiez la préparation de la requête
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $token, $email);
    
    // Vérifiez l'exécution de la requête
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    // Envoyer l'e-mail
    $reset_link = $link . "reset-password.php?token=" . $token;
    echo $reset_link;

    $stmt->close();
    $conn->close();
}
?>