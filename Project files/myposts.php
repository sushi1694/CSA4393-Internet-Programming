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

// Retrieve posts by the logged-in user
$username = $_SESSION['username'];
$sql = "SELECT * FROM posts WHERE author = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Posts</title>
    <style>
        /* Base Styles */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
            color: #333;
        }

        header {
            background: #0056b3; /* Blue background */
            color: white;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            position: relative;
            text-align: center;
        }

        .header-content {
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
        }

        .header-content h1 {
            margin: 0;
            font-size: 2rem;
            font-weight: bold;
        }

        .home-button {
            background-color: #ffffff;
            color: #0056b3;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
            text-decoration: none;
            position: absolute;
            left: 20px;
            top: 50%;
            transform: translateY(-50%);
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        .home-button:hover {
            background-color: #e1e1e1;
            transform: translateY(-50%) scale(1.05);
        }

        .home-button:active {
            transform: translateY(-50%) scale(0.95);
        }

        main {
            padding: 40px 20px;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .container {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            max-width: 1200px;
            margin: auto;
        }

        .post-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            overflow: hidden;
            width: calc(33.333% - 20px); /* Adjust for gap */
            min-width: 300px; /* Responsive */
            box-sizing: border-box;
            padding: 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .post-card h2 {
            margin-top: 0;
            font-size: 1.5rem;
            color: #333;
        }

        .post-card p {
            margin: 10px 0;
            color: #666;
        }

        .post-card .post-content {
            flex: 1;
            margin-bottom: 20px;
        }

        .post-card .actions {
            text-align: right;
        }

        .post-card button {
            background-color: #0056b3; /* Blue color */
            color: #ffffff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 0.9rem;
            transition: background-color 0.3s ease, transform 0.2s ease;
            margin-left: 10px;
        }

        .post-card button:hover {
            background-color: #003d7a; /* Darker blue color */
            transform: scale(1.05);
        }

        .post-card button:active {
            transform: scale(0.98);
        }
    </style>
</head>
<body>
    <header>
        
        <div class="header-content">
        <a href="home.php" class="home-button">Home</a>
            <h1>My Posts</h1>
        </div>
    </header>
    <main>
        <div class="container">
            <?php while ($row = $result->fetch_assoc()): ?>
            <div class="post-card">
                <h2><?php echo htmlspecialchars($row['title']); ?></h2>
                <div class="post-content">
                    <p><?php echo htmlspecialchars(substr($row['content'], 0, 150)) . '...'; ?></p>
                </div>
                <div class="actions">
                    <a href="edit.php?id=<?php echo $row['id']; ?>"><button>Edit</button></a>
                    <a href="deletepost.php?id=<?php echo $row['id']; ?>" onclick="return confirm('Are you sure you want to delete this post?');"><button>Delete</button></a>
                </div>
            </div>
            <?php endwhile; ?>
        </div>
    </main>
</body>
</html>

<?php
// Close connection
$conn->close();
?>
