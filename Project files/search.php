<?php
header('Content-Type: application/json');

// Database connection details
$servername = "localhost";
$dbusername = "root";
$dbpassword = "";
$dbname = "blog_db";

// Create a new database connection
$conn = new mysqli($servername, $dbusername, $dbpassword, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the search query and escape special characters
$query = isset($_GET['q']) ? $conn->real_escape_string($_GET['q']) : '';

// SQL query to search for posts with titles matching the query
$sql = "SELECT id, title FROM posts WHERE title LIKE ? LIMIT 10";
$stmt = $conn->prepare($sql);
$searchTerm = "%$query%";
$stmt->bind_param("s", $searchTerm);
$stmt->execute();
$result = $stmt->get_result();

// Fetch results and encode as JSON
$posts = array();
while ($row = $result->fetch_assoc()) {
    $posts[] = $row;
}

// Close connection
$stmt->close();
$conn->close();

// Output results as JSON
echo json_encode($posts);
?>
