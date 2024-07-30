<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Categories</title>
    <style>
        /* Base Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background: #007bff; /* Blue color */
            color: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
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
            background-color: #ff5722; /* Deep orange */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            margin-top: 10px;
        }

        .logout-btn:hover {
            background-color: #e64a19; /* Darker orange */
        }

        .category-list {
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin: 20px auto;
            max-width: 800px;
        }

        .category-list h2 {
            margin-top: 0;
            font-size: 2rem;
            color: #007bff; /* Blue color */
            text-align: center;
        }

        .category-item {
            padding: 15px;
            margin: 10px 0;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            text-decoration: none;
            color: #333;
            display: block;
            transition: background-color 0.3s ease, transform 0.3s ease;
        }

        .category-item:hover {
            background: #e0e0e0;
            transform: scale(1.02);
        }
    </style>
</head>
<body>
    <header>
        <h1>Category</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="create.php">Create Blog</a></li>
                <li><a href="categories.php">Categories</a></li>
            </ul>
        </nav>
        
    </header>
    <main>
        <div class="container">
            <section class="category-list">
                <h2>Categories</h2>
                <?php
                // Include database connection
                $servername = "localhost";
                $dbusername = "root";
                $dbpassword = "";
                $dbname = "blog_db";

                $conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                // Retrieve unique tags from the posts table
                $sql = "SELECT tags FROM posts";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $tags = [];
                    while ($row = $result->fetch_assoc()) {
                        $postTags = explode(',', $row["tags"]);
                        foreach ($postTags as $tag) {
                            $tag = trim($tag);
                            if (!in_array($tag, $tags)) {
                                $tags[] = $tag;
                            }
                        }
                    }

                    foreach ($tags as $tag) {
                        echo "<a href='posts_by_tag.php?tag=" . urlencode($tag) . "' class='category-item'>" . htmlspecialchars($tag) . "</a>";
                    }
                } else {
                    echo "<p>No categories found.</p>";
                }

                $conn->close();
                ?>
            </section>
        </div>
    </main>
</body>
</html>
