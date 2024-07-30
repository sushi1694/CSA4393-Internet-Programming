<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Posts by Tag</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header Styles */
        header {
            background: #007bff; /* Blue color */
            color: white;
            padding: 20px 0;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        header h1 {
            margin: 0;
            font-size: 2.5rem;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            margin: 10px 0;
            text-align: center;
        }

        nav ul li {
            display: inline;
            margin: 0 15px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            font-size: 1.2rem;
        }

        nav ul li a:hover {
            text-decoration: underline;
            color: #ffeb3b; /* Highlight color */
        }

        .logout-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            background-color: #ff4500; /* Deep orange */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .logout-btn:hover {
            background-color: #ff6347; /* Hover color */
        }

        /* Main Content Styles */
        main {
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .post-list {
            padding: 20px;
        }

        .post-list h2 {
            font-size: 2rem;
            color: #007bff; /* Blue color */
            text-align: center;
            margin-bottom: 20px;
        }

        .post-item {
            padding: 15px;
            margin: 10px 0;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 8px;
            text-decoration: none;
            color: #333;
            display: block;
            transition: background-color 0.3s ease, transform 0.3s ease;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .post-item:hover {
            background: #e0e0e0;
            transform: scale(1.02);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        .post-item h3 {
            margin: 0;
            font-size: 1.5rem;
        }

        .post-item p {
            margin: 10px 0 0;
            color: #666;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Posts Category</h1>
            <nav>
                <ul>
                    <li><a href="home.php">Home</a></li>
                    <li><a href="create.php">Create Post</a></li>
                    <li><a href="categories.php">Category</a></li>
                </ul>
            </nav>
            <a href="logout.php" class="logout-btn">Logout</a>
        </div>
    </header>
    <main>
        <div class="container">
            <section class="post-list">
                <h2>Category : "<?php echo htmlspecialchars($_GET['tag']); ?>"</h2>
                <?php
                $tag = isset($_GET['tag']) ? $_GET['tag'] : '';

                if (!$tag) {
                    echo "<p>No tag specified.</p>";
                    exit;
                }

                // Include database connection
                $servername = "localhost";
                $dbusername = "root";
                $dbpassword = "";
                $dbname = "blog_db";

                $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve posts with the specified tag
                $sql = "SELECT id, title, author, created_at FROM posts WHERE FIND_IN_SET(?, tags)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("s", $tag);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        echo "<a href='post.php?id=" . $row["id"] . "' class='post-item'>";
                        echo "<h3>" . htmlspecialchars($row["title"]) . "</h3>";
                        echo "<p><em>Posted by " . htmlspecialchars($row["author"]) . " on " . htmlspecialchars($row["created_at"]) . "</em></p>";
                        echo "</a>";
                    }
                } else {
                    echo "<p>No posts found for this tag.</p>";
                }

                $stmt->close();
                $conn->close();
                ?>
            </section>
        </div>
    </main>
</body>
</html>
