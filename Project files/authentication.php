<?php
session_start(); // Start the session

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

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Process form data
    $username = $_POST["username"];
    $password = $_POST["password"];

    // Check if username and password match a record in the database
    $sql = "SELECT * FROM loginpage WHERE username='$username' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        // User found, store username in session
        $_SESSION['username'] = $username;
        header("Location: home.php");
        exit();
    } else {
        // User not found, redirect to login with error message
        $error_message = "Invalid credentials";
        header("Location: login.php?error=" . urlencode($error_message));
        exit();
    }
}

// Close connection
$conn->close();
?>
