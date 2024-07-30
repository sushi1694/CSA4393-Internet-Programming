<?php
session_start(); // Start the session

// Check if user is logged in
if (!isset($_SESSION['username'])) {
    echo "User not logged in.";
    header("Location: login.html");
    exit();
} else {
    echo "User logged in as: " . htmlspecialchars($_SESSION['username']);
}

// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "blog_db";

// Create a new connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve and sanitize form data
    $title = htmlspecialchars($conn->real_escape_string($_POST['title']));
    $content = htmlspecialchars($conn->real_escape_string($_POST['content']));
    $tags = htmlspecialchars($conn->real_escape_string($_POST['tags'])); // This should be a comma-separated string
    $author = htmlspecialchars($_SESSION['username']); // Get the logged-in username

    // Handle file upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $upload_dir = 'uploads/'; // Directory where you want to save uploaded images
        $image_path = $upload_dir . $image_name;

        // Check if upload directory exists and is writable
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move the uploaded file to the target directory
        if (move_uploaded_file($tmp_name, $image_path)) {
            $image_path = $conn->real_escape_string($image_path); // Escape the file path
        } else {
            echo "Failed to upload image.";
            $image_path = null;
        }
    }

    // Prepare SQL query
    $sql = "INSERT INTO posts (title, author, content, tags, image, created_at) VALUES (?, ?, ?, ?, ?, NOW())";
    $stmt = $conn->prepare($sql);

    if ($stmt === false) {
        die("Prepare failed: " . $conn->error);
    }

    // Bind parameters
    $stmt->bind_param("sssss", $title, $author, $content, $tags, $image_path);

    // Execute the query
    if ($stmt->execute()) {
        echo "Post created successfully. <a href='home.php'>Back to home</a>";
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>