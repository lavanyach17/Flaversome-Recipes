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

// Fetch the recipe data if id is set
$id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$recipe = null;

if ($id > 0) {
    $sql = "SELECT * FROM receipes WHERE id=?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $recipe = $result->fetch_assoc();
    $stmt->close();
}

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $id = isset($_POST['id']) ? intval($_POST['id']) : 0;
    $name = $_POST['name'];
    $image = $_POST['image'];
    $description = $_POST['description'];
    $ingredients = $_POST['ingredients'];
    $instructions = $_POST['instructions'];

    if ($id > 0) {
        // Update existing recipe
        $sql = "UPDATE receipes SET name=?, image=?, description=?, ingredients=?, instructions=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssi", $name, $image, $description, $ingredients, $instructions, $id);
    } else {
        // Add new recipe
        $sql = "INSERT INTO receipes (name, image, description, ingredients, instructions) VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssss", $name, $image, $description, $ingredients, $instructions);
    }

    if ($stmt->execute()) {
        echo "Recipe " . ($id > 0 ? "updated" : "added") . " successfully!";
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update Recipe</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
<div class="views">
    <form method="post" action="receipes(add).php">
        <input type="hidden" name="id" value="<?php echo isset($recipe['id']) ? $recipe['id'] : 0; ?>">
        <div class="form-group">
            <label for="name">Recipe Name</label>
            <input type="text" class="form-control" id="name" name="name" value="<?php echo isset($recipe['name']) ? htmlspecialchars($recipe['name']) : ''; ?>" required>
        </div>
        <div class="form-group">
            <label for="image">Recipe Image URL</label>
            <input type="url" class="form-control" id="image" name="image" value="<?php echo isset($recipe['image']) ? htmlspecialchars($recipe['image']) : ''; ?>" placeholder="Enter Image URL">
        </div>
        <div class="form-group">
            <label for="description">Description</label>
            <textarea class="form-control" id="description" name="description" rows="3" required><?php echo isset($recipe['description']) ? htmlspecialchars($recipe['description']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="ingredients">Ingredients</label>
            <textarea class="form-control" id="ingredients" name="ingredients" rows="5" required><?php echo isset($recipe['ingredients']) ? htmlspecialchars($recipe['ingredients']) : ''; ?></textarea>
        </div>
        <div class="form-group">
            <label for="instructions">Instructions</label>
            <textarea class="form-control" id="instructions" name="instructions" rows="5" required><?php echo isset($recipe['instructions']) ? htmlspecialchars($recipe['instructions']) : ''; ?></textarea>
        </div>
        <button type="submit">Update Recipe</button>
            <a href="index.php" style="color: white;">Back to home</a>
    </form>
</div>
</body>
</html>