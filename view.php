<?php
// Ensure the ID parameter is set and is a valid integer
if(isset($_GET['id']) && is_numeric($_GET['id'])) {
    $recipe_id = $_GET['id'];

    // Database connection
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

    // Prepare SQL query
    $sql = "SELECT * FROM receipes WHERE id = $recipe_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // Fetch the recipe details
        $recipe_name = $row['name'];
        $recipe_image = $row['image'];
        $recipe_description = $row['description'];
        $recipe_ingredients = $row['ingredients'];
        $recipe_instructions = $row['instructions'];
    } else {
        echo "Recipe not found";
        exit; // Exit if recipe ID does not exist
    }

    $conn->close();
} else {
    echo "Invalid recipe ID";
    exit; // Exit if no valid recipe ID is provided
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $recipe_name; ?></title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
    <div class="view">
        <a href="recipe.php" style="margin: 30px; color: white;"><i class="bi bi-arrow-left-circle-fill fs-2"></i></a>
        <h2><?php echo $recipe_name; ?></h2>
        <br>
        <img src="<?php echo $recipe_image; ?>" alt="<?php echo $recipe_name; ?>">
        <br>
        <div class="view-text">
            <p><?php echo $recipe_description; ?></p>
            <br>
            <h3>Ingredients:</h3>
            <ol>
                <p><?php echo $recipe_ingredients; ?></p>
            </ol>
            <h4>Instructions:</h4>
            <ol>
                <p><?php echo $recipe_instructions; ?></p>
            </ol>
        </div>
        <!-- <div class="container">
    <div class="row">
        <div class="col">
               Buttons aligned at the end with spacing 
            <div class="d-flex justify-content-end align-items-end gap-2">
                <button type="submit" class="btn btn-primary">Edit</button>
                <a href="delete_recipe.php?id=1" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>
</div> -->

    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
