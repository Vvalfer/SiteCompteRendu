<?php
// Inclure le fichier de configuration pour obtenir les informations de connexion à la base de données
include '../../conf.php';

// Vérifier si la méthode de la requête est GET
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Récupérer le token de la requête GET
    $token = $_GET['token'];

    // Connexion à la base de données
    $conn = new mysqli($servername, $username, $pswd, $dbname);

    // Vérifier si la connexion a échoué
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Préparer une requête SQL pour vérifier le token et la date d'expiration
    $stmt = $conn->prepare("SELECT login FROM users WHERE reset_token = ? AND reset_expires > NOW()");
    
    // Vérifier si la préparation de la requête a échoué
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Lier le paramètre token à la requête préparée
    $stmt->bind_param("s", $token);
    // Exécuter la requête
    $stmt->execute();
    // Obtenir le résultat de la requête
    $result = $stmt->get_result();

    // Vérifier si le token est valide et non expiré
    if ($result->num_rows > 0) {
        // Afficher le formulaire de réinitialisation du mot de passe
        echo '<style>
                body {
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    margin: 0;
                    font-family: Arial, sans-serif;
                    background-color: #f0f0f0;
                }
                form {
                    display: flex;
                    flex-direction: column;
                    align-items: center;
                    padding: 30px;
                    border: 1px solid #ccc;
                    border-radius: 10px;
                    box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                    background-color: #fff;
                }
                input[type="password"] {
                    width: 100%;
                    padding: 10px;
                    margin: 10px 0;
                    border: 1px solid #ccc;
                    border-radius: 5px;
                    box-sizing: border-box;
                }
                button {
                    padding: 10px 20px;
                    margin-top: 10px;
                    border: none;
                    border-radius: 5px;
                    background-color: #007BFF;
                    color: white;
                    cursor: pointer;
                    font-size: 16px;
                }
                button:hover {
                    background-color: #0056b3;
                }
            </style>';
        echo '<form action="reset-password.php" method="post">
                <input type="hidden" name="token" value="' . $token . '">
                <input type="password" name="new_password" placeholder="Enter new password" required>
                <button type="submit">Reset Password</button>
            </form>';
    } else {
        // Si le token est invalide ou expiré, afficher un message d'erreur
        echo "Invalid or expired token.";
    }

    // Fermer la connexion à la base de données
    $stmt->close();
    $conn->close();
} elseif ($_SERVER["REQUEST_METHOD"] == "POST") {
    $token = $_POST['token'];
    $new_password = hash('sha256', $_POST['new_password']);// Hash

    // Mis a jour mdp
    $conn = new mysqli($servername, $username, $pswd, $dbname);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("UPDATE users SET mdp = ?, reset_token = NULL, reset_expires = NULL WHERE reset_token = ?");
    
    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    $stmt->bind_param("ss", $new_password, $token);
    
    if (!$stmt->execute()) {
        die("Execute failed: " . $stmt->error);
    }

    echo '<style>
            body {
                display: flex;
                justify-content: center;
                align-items: center;
                height: 100vh;
                margin: 0;
                font-family: Arial, sans-serif;
                background-color: #f0f0f0;
            }
            .message-container {
                display: flex;
                flex-direction: column;
                align-items: center;
                padding: 30px;
                border: 1px solid #ccc;
                border-radius: 10px;
                box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
                background-color: #fff;
            }
            .message-container p {
                font-size: 18px;
                margin: 0 0 20px 0;
            }
            .message-container button {
                padding: 10px 20px;
                border: none;
                border-radius: 5px;
                background-color: #007BFF;
                color: white;
                cursor: pointer;
                font-size: 16px;
            }
            .message-container button:hover {
                background-color: #0056b3;
            }
        </style>';
    echo '<div class="message-container">
            <p>Your password has been reset successfully.</p>
            <button onclick="window.location.href=\'../form.php\'">Go Back</button>
        </div>';

    $stmt->close();
    $conn->close();
}
?>