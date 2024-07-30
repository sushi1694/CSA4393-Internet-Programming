<?php
session_start();

// Debugging session info
echo "<pre>";
print_r($_SESSION);
echo "</pre>";

// Redirect to login page if not logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.html");
    exit();
}

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
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $tags = $conn->real_escape_string($_POST['tags']);
    $author = $_SESSION['username']; // Get the logged-in username

    // Handle file upload
    $image_path = null;
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $upload_dir = 'uploads/'; // Directory where you want to save uploaded images

        // Check if upload directory exists and is writable
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        // Move the uploaded file to the target directory
        $image_path = $upload_dir . $image_name;
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
        // Redirect to myposts.php after successful post creation
        header("Location: myposts.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    // Close statement
    $stmt->close();
}

// Close connection
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Post</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 0;
            background-image: url('img111.png'); /* Background image */
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            background-repeat: no-repeat;
            color: #333;
        }

        .home-button {
            background-color: #007bff;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            margin: 20px;
            transition: background-color 0.3s ease;
        }

        .home-button:hover {
            background-color: #0056b3;
        }

        main {
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 80px); /* Full height minus header height */
        }

        section {
            padding: 40px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            width: 100%;
        }

        section h2 {
            margin-top: 0;
            color: #333;
            font-size: 2rem;
            border-bottom: 2px solid #8B4513; /* Reddish brown underline */
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            font-weight: bold;
            margin-bottom: 8px;
            color: #333;
            font-size: 1rem;
        }

        input[type="text"],
        textarea,
        input[type="file"] {
            width: 100%;
            padding: 12px;
            margin-bottom: 20px; /* Increased space between elements */
            border: 1px solid #ccc;
            border-radius: 8px;
            background-color: #f9f9f9;
            color: #333;
            box-sizing: border-box;
        }

        textarea {
            resize: vertical; /* Allow vertical resizing */
        }

        button {
            background-color: #8B4513; /* Reddish brown color */
            color: #ffffff;
            padding: 14px;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-top: 10px;
        }

        button:hover {
            background-color: #6f3f1c; /* Darker reddish-brown color for hover effect */
            transform: translateY(-2px); /* Subtle lift effect */
        }
    </style>
</head>
<body>
    <main>
        <section id="create-post">
            <a href="home.php" class="home-button">Home</a> <!-- Home button to return to home.php -->
            <h2>Create a New Post</h2>
            <form id="post-form" action="create.php" method="post" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" required>
                
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="6" required></textarea>
                
                <label for="tags">Tags (comma-separated):</label>
                <input type="text" id="tags" name="tags" required>
                
                <label for="image">Image:</label>
                <input type="file" id="image" name="image">
                
                <!-- Display the author's username -->
                <label for="author">Author:</label>
                <input type="text" id="author" name="author" value="<?php echo htmlspecialchars($_SESSION['username']); ?>" readonly>
                
                <button type="submit">Post</button>
            </form>
        </section>
    </main>
</body>
</html>
