<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Define your database connection details
    $servername = "localhost";
    $dbusername = "root";
    $dbpassword = "";
    $dbname = "blog_db";

    // Create connection
    $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Process form data
    $post_id = intval($_POST["id"]);
    $title = $_POST["title"];
    $author = $_POST["author"];
    $content = $_POST["content"];
    $tags = $_POST["tags"]; // Include tags

    // Handle file upload
    $imagePath = '';
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $uploadDir = 'uploads/';
        $uploadFile = $uploadDir . basename($_FILES['image']['name']);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
            $imagePath = $conn->real_escape_string($uploadFile);
        } else {
            echo "File upload failed.";
            $imagePath = ''; // Ensure imagePath is empty if upload fails
        }
    }

    // Update post in the database
    if (!empty($imagePath)) {
        // Update with new image
        $sql = "UPDATE posts SET title = ?, author = ?, content = ?, tags = ?, image = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $title, $author, $content, $tags, $imagePath, $post_id);
    } else {
        // Update without new image
        $sql = "UPDATE posts SET title = ?, author = ?, content = ?, tags = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssi", $title, $author, $content, $tags, $post_id);
    }

    if ($stmt->execute()) {
        header('Location: home.php'); // Redirect to home page after update
        exit;
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();
}
?>
