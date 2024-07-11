<?php
// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mydatabase";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the recipe ID is set in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    // Prepare and execute the delete statement
    $sql = "DELETE FROM receipes WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    
    if ($stmt->execute()) {
        echo "Recipe deleted successfully!";
    } else {
        echo "Error deleting recipe: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();

// Redirect back to the homepage or recipes page
header("Location: recipe.php");
exit();
?>