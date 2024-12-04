<?php
session_start();
include '../../conf.php';
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['login']) && isset($_POST['password'])) {
        $login = $_POST['login'];
        $password = $_POST['password'];
        $hashed_password = hash('sha256', $password);

        //connection
        $password_db = "";
        $conn = new mysqli($servername, $username, $pswd, $dbname);

        // verification
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Preparation request
        $stmt = $conn->prepare("SELECT id, login, mdp, id_statut, nom, prenom, dateN, mail, tel FROM users WHERE login = ? AND mdp = ?");
        $stmt->bind_param("ss", $login, $hashed_password);

        // Execute
        $stmt->execute();

        // Récupération du résultat
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $id_statut = $row['id_statut'];

            $_SESSION['user_id'] = $row['id'];
            $_SESSION['nom'] = $row['nom'];
            $_SESSION['prenom'] = $row['prenom'];
            $_SESSION['dateN'] = $row['dateN'];
            $_SESSION['mail'] = $row['mail'];
            $_SESSION['tel'] = $row['tel'];

            if ($id_statut == 2) {
                header("Location: ../prof.php");
            } elseif ($id_statut == 1) {
                header("Location: ../eleve.php");
            } else {
                echo "Invalid status";
            }
        } else {
            echo "Invalid login or password";
        }

        $stmt->close();
        $conn->close();
    } else {
        echo "Login and password are required.";
    }
} else {
    echo "Invalid request method.";
}
?>