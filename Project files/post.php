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

// Get the post ID from the URL
$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

// Retrieve the post details
$sql = "SELECT * FROM posts WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $post_id);
$stmt->execute();
$result = $stmt->get_result();
$post = $result->fetch_assoc();

if (!$post) {
    echo "Post not found.";
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($post['title']); ?></title>
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

        .post-content {
            padding: 40px;
            background: rgba(255, 255, 255, 0.9); /* Semi-transparent white background */
            border-radius: 12px;
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            max-width: 700px;
            margin: 20px auto;
        }

        .post-content h1 {
            font-size: 2rem;
            border-bottom: 2px solid #8B4513; /* Reddish brown underline */
            padding-bottom: 10px;
        }

        .post-content p {
            line-height: 1.6;
        }

        .post-content img {
            max-width: 100%;
            height: auto;
            border-radius: 8px;
        }
    </style>
</head>
<body>
    <header>
        <h1>My Blog</h1>
    </header>
    <div class="post-content">
        <h1><?php echo htmlspecialchars($post['title']); ?></h1>
        <p><em>By <?php echo htmlspecialchars($post['author']); ?> on <?php echo date('F j, Y', strtotime($post['created_at'])); ?></em></p>
        <?php if ($post['image']): ?>
            <img src="<?php echo htmlspecialchars($post['image']); ?>" alt="Post Image">
        <?php endif; ?>
        <p><?php echo nl2br(htmlspecialchars(str_replace("\r", "", $post['content']))); ?></p>
    </div>
</body>
</html>

<?php
// Close connection
$stmt->close();
$conn->close();
?>
