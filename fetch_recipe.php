<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch recipes
$sql = "SELECT id, name, image, description FROM receipes";
$result = $conn->query($sql);

$recipes = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }
} else {
    echo "No recipes found";
}

$conn->close();
?>
