<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

// Database connection
$host = 'localhost';
$db = 'mydatabase';
$user = 'root';
$pass = '';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];

    $stmt = $conn->prepare('INSERT INTO recipes (name, image, description, ingredients, instructions) VALUES (?, ?, ?, ?, ?)');
    $stmt->bind_param('sssss', $name, $image, $description, $ingredients, $instructions);

    if ($stmt->execute()) {
        header('Location: recipe.php');
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>
