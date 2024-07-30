<?php
session_start();

// Database connection
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "blog_db";

$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Retrieve the latest posts
$sql_latest = "SELECT id, title, author FROM posts ORDER BY created_at DESC LIMIT 5";
$result_latest = $conn->query($sql_latest);

// Retrieve posts created by the logged-in user
$user = isset($_SESSION['username']) ? $_SESSION['username'] : '';
$sql_user_posts = "SELECT id, title, author FROM posts WHERE author = ?";
$stmt_user_posts = $conn->prepare($sql_user_posts);
$stmt_user_posts->bind_param("s", $user);
$stmt_user_posts->execute();
$result_user_posts = $stmt_user_posts->get_result();

// Merge results
$posts = [];
while ($row = $result_latest->fetch_assoc()) {
    $posts[$row['id']] = $row;
}
while ($row = $result_user_posts->fetch_assoc()) {
    if (!isset($posts[$row['id']])) {
        $posts[$row['id']] = $row;
    }
}
$posts = array_values($posts); // Re-index the array

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home</title>
    <style>
        /* Base Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        /* Header Styles */
        header {
            background: #007bff; /* Blue color */
            color: white;
            padding: 20px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            position: relative; /* Ensure the header is positioned relative for absolute positioning inside it */
            display: flex;
            flex-direction: column;
            align-items: center;
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
            font-size: 1.1rem;
        }

        nav ul li a:hover {
            text-decoration: none; /* Removed underline from links on hover */
        }

        /* Search Bar Styles */
        .search-bar-container {
            width: 100%;
            display: flex;
            justify-content: center; /* Center horizontally */
            margin-top: 10px; /* Space between navigation and search bar */
            position: relative;
        }

        .search-bar {
            padding: 10px;
            border-radius: 5px;
            border: none;
            font-size: 1rem;
            width: 300px;
        }

        /* Dropdown Menu Styles */
        .dropdown {
            position: absolute;
            top: calc(100% + 10px); /* Positioned just below the search bar with a gap */
            left: 50%; /* Center horizontally */
            transform: translateX(-50%); /* Center horizontally */
            width: 300px; /* Matches the width of the search bar */
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1000;
        }

        .dropdown-item {
            padding: 10px;
            text-decoration: none;
            display: block;
            color: #333;
        }

        .dropdown-item:hover {
            background-color: #f4f4f4;
        }

        .dropdown.active {
            display: block;
        }

        /* Logout Button Styles */
        .logout-btn {
            background-color: #ff4500; /* OrangeRed color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
            position: absolute; /* Position logout button absolutely */
            bottom: 20px; /* Position from the bottom */
            right: 20px; /* Position from the right */
        }

        .logout-btn:hover {
            background-color: #ff6347; /* Tomato color */
        }

        /* My Posts Button Styles */
        .my-posts-btn {
            position: absolute;
            top: 20px;
            left: 20px;
            background-color: #4CAF50; /* Green color */
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .my-posts-btn:hover {
            background-color: #45a049; /* Darker green on hover */
        }

        /* Hero Section Styles */
        .hero {
            width: 100%;
            height: 400px; /* Adjust height as needed */
            background-image: url('imghome.jpg'); /* Update with your image path */
            background-size: cover;
            background-position: center;
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            border-radius: 20px; /* Curved border */
            overflow: hidden; /* Ensures content is clipped within the rounded corners */
        }

        .hero .btn {
            background-color: rgba(0, 0, 0, 0.7); /* Black with 70% opacity */
            color: white;
            padding: 15px 30px;
            border: none;
            border-radius: 5px;
            font-size: 1.2rem;
            cursor: pointer;
            text-decoration: none;
            transition: background-color 0.3s ease;
        }

        .hero .btn:hover {
            background-color: rgba(0, 0, 0, 0.9); /* Black with 90% opacity on hover */
        }

        /* Main Content Styles */
        main {
            padding: 20px;
            background-color: #000000; /* Background color */
        }

        section#latest-posts {
            padding: 40px 20px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
            margin-top: 20px;
        }

        section#latest-posts h2 {
            margin-top: 0;
            font-size: 1.8rem;
            color: #a52a2a; /* Reddish-brown color */
        }

        /* Floating Boxes for Posts */
        #post-list {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            padding: 20px;
        }

        #post-list .post {
            flex: 1 1 calc(25% - 20px);
            box-sizing: border-box;
            padding: 20px;
            background: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            transition: box-shadow 0.3s ease, transform 0.3s ease;
            text-align: center;
        }

        #post-list .post:hover {
            box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
            transform: translateY(-5px);
        }

        #post-list .post h3 {
            margin: 0;
            font-size: 1.2rem;
            color: #a52a2a; /* Reddish-brown color */
        }

        #post-list .post h3 a {
            text-decoration: none; /* Remove underline */
            color: #a52a2a; /* Reddish-brown color for links */
        }

        #post-list .post h3 a:hover {
            text-decoration: underline; /* Add underline on hover, if desired */
        }

        /* User Button and Dropdown Styles */
        .user-btn {
            position: absolute;
            top: 20px;
            right: 20px;
            width: 40px;
            height: 40px;
            background-color: #ff0000; /* Red color */
            border-radius: 50%;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 1.2rem;
            text-decoration: none;
            text-align: center; /* Center text horizontally */
            line-height: 1; /* Adjust line height to vertically center */
}


        .user-btn:hover {
            background-color: #cc0000; /* Darker red on hover */
        }

        .user-dropdown {
            position: absolute;
            top: 70px; /* Adjust according to the button's position */
            right: 20px;
            background-color: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            display: none;
            z-index: 1000;
            width: 150px; /* Adjust width as needed */
        }

        .user-dropdown.active {
            display: block;
        }

        .user-dropdown a {
            display: block;
            padding: 10px;
            color: #333;
            text-decoration: none;
        }

        .user-dropdown a:hover {
            background-color: #f4f4f4;
        }
        .username {
            padding: 10px;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
}
    </style>
</head>
<body>
    <header>
        <h1>My Blog</h1>
        <nav>
            <ul>
                <li><a href="home.php">Home</a></li>
                <li><a href="create.php">Create Blog</a></li>
                <li><a href="categories.php">Categories</a></li>
                <li><a href="aboutus.html">About Us</a></li>
                <li><a href="contact.html">Contact</a></li>
                 
            </ul>
        </nav>
        <div class="search-bar-container">
            <input type="text" id="search-bar" class="search-bar" placeholder="Search posts...">
            <div id="search-results" class="dropdown"></div>
        </div>
        <a href="myposts.php" class="my-posts-btn">My Posts</a>
        <a href="#" class="user-btn"><?php echo strtoupper(substr($user, 0, 1)); ?></a> <!-- User round button -->
        <div class="user-dropdown">
        <div class="username"><?php echo htmlspecialchars($user); ?></div>
            <a href="logout.php">Logout</a>
        </div>
    </header>
    
    <main>
        <section class="hero">
            <a href="create.php" class="btn">Create Blog</a>
        </section>

        <section id="latest-posts">
            <h2>Latest Posts</h2>
            <div id="post-list">
                <?php
                foreach ($posts as $post) {
                    echo '<div class="post">';
                    echo '<h3><a href="post.php?id=' . $post['id'] . '">' . htmlspecialchars($post['title']) . '</a></h3>';
                    echo '<p class="author">By ' . htmlspecialchars($post['author']) . '</p>';
                    echo '</div>';
                }
                ?>
            </div>
        </section>
    </main>

    <script>
        // JavaScript for handling dropdown visibility and search functionality
        document.getElementById('search-bar').addEventListener('focus', function() {
            document.getElementById('search-results').classList.add('active');
        });

        document.getElementById('search-bar').addEventListener('blur', function() {
            setTimeout(function() {
                document.getElementById('search-results').classList.remove('active');
            }, 200);
        });

        document.getElementById('search-bar').addEventListener('input', function() {
            let query = this.value;
            if (query.length > 2) {
                // Fetch search results via AJAX
                fetch('search.php?q=' + encodeURIComponent(query))
                    .then(response => response.json())
                    .then(data => {
                        let resultsDiv = document.getElementById('search-results');
                        resultsDiv.innerHTML = '';
                        data.forEach(item => {
                            let link = document.createElement('a');
                            link.href = 'post.php?id=' + item.id;
                            link.textContent = item.title;
                            link.className = 'dropdown-item';
                            resultsDiv.appendChild(link);
                        });
                    });
            } else {
                document.getElementById('search-results').innerHTML = '';
            }
        });

        // JavaScript for user dropdown menu
        document.querySelector('.user-btn').addEventListener('click', function() {
            document.querySelector('.user-dropdown').classList.toggle('active');
        });
    </script>
</body>
</html>

<?php
$stmt_user_posts->close();
$conn->close();
?>
