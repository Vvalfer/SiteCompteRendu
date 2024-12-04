<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Compte Rendu de Stage</title>
</head>
<body style="display: flex; flex-direction: column; align-items: center; justify-content: center; height: 100vh; margin: 0;">
    <style>
        button {
            display: inline-block;
            width: 250px;
            font-size: 20px;
            padding: 20px 40px;
            border-radius: 20px;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #007bff;
        }
    </style>
    <h1 style="position: absolute; top: 0; width: 100%; text-align: center; margin: 0; padding: 20px; background-color: #f8f8f8;">Compte Rendu de Stage</h1>
    <h2 style="margin: 0; position: absolute; top: 140px; font-size: 34px; color: #333; text-shadow: 1px 1px 2px rgba(0, 0, 0, 0.1);">Bienvenue Professeur</h2>
    <div style="display: flex; justify-content: center; gap: 40px; margin-top: 20px;">  
        <button onclick="window.location.href='Prof/listeEleves.php'">Listes élèves</button>
        <button onclick="window.location.href='Prof/CRprof.php'">Compte Rendus</button>
    </div>
    <button style="position: absolute; top: 20px; right: 20px; font-size: 14px; padding: 10px 20px;" onclick="window.location.href='myAccount.php'">My account</button>
</body>
</html>