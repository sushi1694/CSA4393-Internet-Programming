<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Page</title>
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
        .registration-container {
            background-color: rgba(255, 255, 255, 0.6);
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            text-align: center;
            width: 100%;
            max-width: 300px;
        }
        .registration-container h2 {
            margin-bottom: 20px;
        }
        .registration-container input[type="text"],
        .registration-container input[type="password"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin: 10px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        .registration-container input[type="submit"] {
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
        .registration-container input[type="submit"]:hover {
            background-color: #0056b3;
        }
        .registration-container a {
            display: block;
            margin-top: 10px;
            color: #007bff;
            text-decoration: none;
        }
        .registration-container a:hover {
            text-decoration: underline;
        }
        .error-message {
            color: red;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="registration-container">
        <h2>Register New Account</h2>
        <form action="" method="post">
            <input type="text" name="name" placeholder="Name" required>
            <input type="text" name="username" placeholder="Username" required>
            <input type="password" name="password" placeholder="Password" required>
            <input type="submit" value="Register">
            <a href="login.php">Login</a>
        </form>
        <?php
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

        // Initialize error message
        $error_message = "";

        // Check if the form is submitted
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            // Process form data
            $name = $_POST["name"];
            $username = $_POST["username"];
            $password = $_POST["password"];

            // Check if username already exists
            $check_username_query = "SELECT * FROM loginpage WHERE username='$username'";
            $result = $conn->query($check_username_query);
            if ($result->num_rows > 0) {
                // Username already exists, display error message
                $error_message = "Username already exists. Please choose a different username.";
            } else {
                // Insert data into the database
                $sql = "INSERT INTO loginpage (name, username, password) VALUES ('$name', '$username', '$password')";

                if ($conn->query($sql) === TRUE) {
                    echo "<p>New record created successfully</p>";
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Error: " . $sql . "<br>" . $conn->error;
                }
            }
        }

        // Close connection
        $conn->close();
        ?>
        <?php if (!empty($error_message)): ?>
            <div class="error-message"><?php echo $error_message; ?></div>
        <?php endif; ?>
    </div>
</body>
</html>
