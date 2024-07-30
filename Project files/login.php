<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page - Blogster</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background: url('bcg.jpg') no-repeat center center fixed;
            background-size: cover;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .login-container {
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 300px;
        }
        .login-container h1 {
            margin-bottom: 20px;
            color: #333;
            font-size: 2em;
            font-weight: bold;
        }
        .login-container h2 {
            margin-bottom: 20px;
        }
        .login-container input[type="text"],
        .login-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .login-container button {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            background-color: #007bff;
            color: #fff;
            font-size: 16px;
            box-sizing: border-box;
        }
        .login-container button:hover {
            background-color: #0056b3;
        }
        .login-container button.register-button {
            background-color: #6c757d;
        }
        .login-container button.register-button:hover {
            background-color: #5a6268;
        }
        .login-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .login-container a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Blogster</h1> <!-- Added Blogster as the app name -->
        <h2>Login</h2>
        <form action="authentication.php" method="POST">
            <input type="text" placeholder="Username" name="username" required>
            <input type="password" placeholder="Password" name="password" required>
            <button type="submit">Login</button>
            <button type="button" class="register-button" onclick="window.location.href='register.php'">Register</button>
            <a href="update.php">Forgot Password</a>
        </form>
        <?php if (!empty($_GET['error'])): ?>
            <div class="error-message"><?php echo htmlspecialchars($_GET['error']); ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
