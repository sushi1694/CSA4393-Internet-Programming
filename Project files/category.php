<?php
// category.php

$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "blog_db";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the tag from the URL
$tag = isset($_GET['tag']) ? $_GET['tag'] : '';

// Fetch posts related to the tag
$sql = "SELECT p.id, p.title, p.author, p.created_at
        FROM posts p
        JOIN post_tags pt ON p.id = pt.post_id
        JOIN tags t ON pt.tag_id = t.id
        WHERE t.name = ?
        ORDER BY p.created_at DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $tag);
$stmt->execute();
$result = $stmt->get_result();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts with Tag: <?php echo htmlspecialchars($tag); ?></title>
    <style>
        /* Include your CSS styles here */
    </style>
</head>
<body>
    <header>
        <h1>Blogster - Posts with Tag: <?php echo htmlspecialchars($tag); ?></h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="create.php">Create Post</a></li>
                <li><a href="login.html">Logout</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <section id="posts">
            <h2>Posts Tagged: <?php echo htmlspecialchars($tag); ?></h2>
            <div id="post-list">
                <?php
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<div class='post'>";
                        echo "<h3><a href='post.php?id=" . $row["id"] . "'>" . htmlspecialchars($row["title"]) . "</a><span class='author'>Author: " . htmlspecialchars($row["author"]) . "</span></h3>";
                        echo "<p><em>Posted on " . htmlspecialchars($row["created_at"]) . "</em></p>";
                        echo "</div>";
                    }
                } else {
                    echo "<p>No posts found for this tag.</p>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </div>
        </section>
    </main>
</body>
</html>
