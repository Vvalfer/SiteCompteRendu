<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Form</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: #fff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: 100%;
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }
        .login-container input[type="submit"] {
            width: 100%;
            padding: 10px;
            background-color: #4CAF50;
            border: none;
            border-radius: 5px;
            color: rgb(252, 255, 255);
            font-size: 16px;
            cursor: pointer;
        }
        .login-container input[type="submit"]:hover {
            background-color: #45a049;
        }
    .login-container .forgot-password {
        text-align: right;
        margin-top: 10px;
    }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login</h2>
        <form action="script/index.php" method="post">
            <label for="login">Username:</label>
            <input type="text" id="login" name="login" required>
            <label for="password">Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Login">
        </form>
        <div class="forgot-password">
            <a href="formMdp.html">Forgot your password?</a>
        </div>
    </div>
</body>
</html>