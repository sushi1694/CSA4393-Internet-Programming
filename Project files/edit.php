<?php
session_start();

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

// Retrieve post data
if (isset($_GET['id'])) {
    $post_id = intval($_GET['id']);
    $sql = "SELECT * FROM posts WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $post_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $post = $result->fetch_assoc();
} else {
    die("No post ID specified.");
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $title = $conn->real_escape_string($_POST['title']);
    $content = $conn->real_escape_string($_POST['content']);
    $tags = $conn->real_escape_string($_POST['tags']);
    $image_path = $post['image']; // Keep existing image path if not updated

    // Handle file upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == UPLOAD_ERR_OK) {
        $tmp_name = $_FILES['image']['tmp_name'];
        $image_name = basename($_FILES['image']['name']);
        $upload_dir = 'uploads/';

        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }

        $image_path = $upload_dir . $image_name;
        if (move_uploaded_file($tmp_name, $image_path)) {
            $image_path = $conn->real_escape_string($image_path);
        } else {
            echo "Failed to upload image.";
        }
    }

    $sql = "UPDATE posts SET title = ?, content = ?, tags = ?, image = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssssi", $title, $content, $tags, $image_path, $post_id);

    if ($stmt->execute()) {
        header("Location: myposts.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Post</title>
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

        header {
            background: rgba(255, 255, 255, 0.5); /* White with 50% opacity */
            color: #333; /* Darker text color for contrast */
            padding: 20px 0;
            text-align: center;
            border-bottom: 2px solid #0056b3;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            font-weight: 600;
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
    <header>
        <h1>My Blog</h1>
    </header>
    <main>
        <section id="edit-post">
            <h2>Edit Post</h2>
            <form action="edit.php?id=<?php echo $post_id; ?>" method="post" enctype="multipart/form-data">
                <label for="title">Title:</label>
                <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($post['title']); ?>" required>
                
                <label for="content">Content:</label>
                <textarea id="content" name="content" rows="6" required><?php echo htmlspecialchars($post['content']); ?></textarea>
                
                <label for="tags">Tags (comma-separated):</label>
                <input type="text" id="tags" name="tags" value="<?php echo htmlspecialchars($post['tags']); ?>" required>
                
                <label for="image">Image:</label>
                <input type="file" id="image" name="image">
                <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Current Image" style="max-width: 100%; height: auto; margin-top: 10px;">
                
                <button type="submit">Update Post</button>
            </form>
        </section>
    </main>
</body>
</html>
