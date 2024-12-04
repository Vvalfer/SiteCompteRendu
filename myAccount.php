<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: form.php");
    exit();
}

$name = isset($_SESSION['nom']) ? $_SESSION['nom'] : '';
$surname = isset($_SESSION['prenom']) ? $_SESSION['prenom'] : '';
$birthdate = isset($_SESSION['dateN']) ? $_SESSION['dateN'] : '';
$email = isset($_SESSION['mail']) ? $_SESSION['mail'] : '';
$phone = isset($_SESSION['tel']) ? $_SESSION['tel'] : '';

if (isset($_GET['update']) === 'success') {
    echo '<script>alert("Informations updated successfully.")</script>';
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Account</title>
    <link rel="stylesheet" href="styles.css">
<style>
    body {
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        min-height: 100vh;
        margin: 0;
    }
    header, main, footer {
        width: 100%;
        max-width: 800px;
        text-align: center;
    }
    nav ul {
        margin-top: 20px;
        margin-bottom: 20px;
        list-style-type: none;
        padding: 0;
    }
    nav ul li {
        display: inline;
        margin: 0 10px;
    }
    nav ul li a.btn-primary:hover {
        background-color: white;
        color: black;
    }
    nav ul li a.btn-danger:hover {
        background-color: white;
        color: black;
    }
    .btn-primary:hover {
        background-color: white;
        color: black;
    }
</style>
</head>
<body>
    <header style="display: flex; flex-direction: column; align-items: center; position: fixed; top: 0; width: 100%; background-color: #f8f9fa; z-index: 1000; padding: 10px 0; border: 1px solid #ccc; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 20px;">
        <div style="margin-bottom: 20px;">
            <h1>My Account</h1>
        </div>
        <nav>
            <ul style="display: flex; gap: 10px; padding: 0;">
                <li><a href="javascript:history.back()" class="btn btn-primary" style="border-radius: 10px; padding: 10px 20px; background-color: #45a049; border: 1px solid grey;">Home</a></li>
                <li><a href="script/logout.php" class="btn btn-danger" style="border-radius: 10px; padding: 10px 20px;">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section style="display: flex; flex-direction: column; align-items: center; justify-content: center; margin-top: 150px;">
            <h2>Account Information</h2>
            <form action="script/updateAccount.php" method="post" style="width: 100%; max-width: 400px;">
                <div class="form-group" style="margin-bottom: 10px; text-align: left;">
                    <label for="name"><strong>Name:</strong></label>
                    <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($name); ?>" style="border: 1px solid #000; border-radius: 10px;">
                </div>
                <div class="form-group" style="margin-bottom: 10px; text-align: left;">
                    <label for="surname"><strong>Surname:</strong></label>
                    <input type="text" id="surname" name="surname" class="form-control" value="<?php echo htmlspecialchars($surname); ?>" style="border: 1px solid #000; border-radius: 10px;">
                </div>
                <div class="form-group" style="margin-bottom: 10px; text-align: left;">
                    <label for="birthdate"><strong>Birthdate:</strong></label>
                    <input type="date" id="birthdate" name="birthdate" class="form-control" value="<?php echo htmlspecialchars($birthdate); ?>" style="border: 1px solid #000; border-radius: 10px;">
                </div>
                <div class="form-group" style="margin-bottom: 10px; text-align: left;">
                    <label for="mail"><strong>Email:</strong></label>
                    <input type="email" id="mail" name="mail" class="form-control" value="<?php echo htmlspecialchars($email); ?>" style="border: 1px solid #000; border-radius: 10px;">
                </div>
                <div class="form-group" style="margin-bottom: 10px; text-align: left;">
                    <label for="tel"><strong>Phone:</strong></label>
                    <input type="tel" id="tel" name="tel" class="form-control" value="<?php echo htmlspecialchars($phone); ?>" style="border: 1px solid #000; border-radius: 10px;">
                </div>
                <button type="submit" class="btn btn-primary" style="border-radius: 10px; background-color: #45a049; border: 1px solid grey;">Update Informations</button>
            </form>
        </section>
    </main>
    <footer style="margin-top: auto; padding: 10px 0; background-color: #f8f9fa; width: 100%; text-align: center; border-top: 1px solid #ccc; box-shadow: 0 -4px 8px rgba(0, 0, 0, 0.3); border-radius: 20px;">
        <p style="text-align: center;">&copy; 2024 Louis</p>
    </footer>
</body>
</html>