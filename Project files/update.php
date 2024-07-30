<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Password</title>
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
        .update-password-container {
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 300px;
        }
        .update-password-container h2 {
            margin-bottom: 20px;
        }
        .update-password-container label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .update-password-container input[type="text"],
        .update-password-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .update-password-container input[type="submit"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-top: 10px;
            border: none;
            border-radius: 4px;
            background-color: #007bff;
            color: #fff;
            cursor: pointer;
            box-sizing: border-box;
        }
        .update-password-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .update-password-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .update-password-container a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="update-password-container">
        <h2>Update Password</h2>
        <form action="" method="post">
            <label for="username">Username:</label>
            <input type="text" id="username" name="username" required>
            <label for="password">New Password:</label>
            <input type="password" id="password" name="password" required>
            <input type="submit" value="Update Password">
        </form>
        <a href="login.php">Back to Login</a>
    </div>

    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Define your database connection details
        $servername = "localhost";
        $dbusername = "root";
        $dbpassword = "";
        $dbname = "login_db";

        // Create connection
        $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Process form data
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Check if username already exists
        $check_username_query = "SELECT * FROM loginpage WHERE username='$username'";
        $result = $conn->query($check_username_query);
        if ($result->num_rows > 0) {
            // Username already exists, update password
            $update_query = "UPDATE loginpage SET password='$password' WHERE username='$username'";
            if ($conn->query($update_query) === TRUE) {
                echo "Password updated successfully";
                header("Location: login.php");
                exit();
            } else {
                echo "Error updating password: " . $conn->error;
            }
        } else {
            // Username doesn't exist, display error message
            echo "Username does not exist. Please register first.";
        }

        // Close connection
        $conn->close();
    }
    ?>
</body>
</html>
