<?php
session_start(); // Start the session

// Function to fetch recipes based on search query
function fetch_recipes($search_query = '')
{
    $recipes = [];
    $host = 'localhost';
    $db = 'mydatabase';
    $user = 'root';
    $pass = '';

    $conn = new mysqli($host, $user, $pass, $db);

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare the SQL query
    if ($search_query) {
        $stmt = $conn->prepare("SELECT id, name, description, image FROM receipes WHERE name LIKE ?");
        $search_query = "%" . $search_query . "%";
        $stmt->bind_param('s', $search_query);
    } else {
        $stmt = $conn->prepare("SELECT id, name, description, image FROM receipes");
    }

    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $recipes[] = $row;
    }

    $stmt->close();
    $conn->close();

    return $recipes;
}

// Check if this is an AJAX request
if (isset($_GET['ajax']) && $_GET['ajax'] == '1') {
    $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
    $recipes = fetch_recipes($search_query);
    header('Content-Type: application/json');
    echo json_encode($recipes);
    exit;
}

// Fetch recipes based on search query if exists
$search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
$recipes = fetch_recipes($search_query);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        .search-bar {
            display: flex;
            justify-content: flex-end;
            margin: 20px;
        }

        .search-bar input[type="search"] {
            width: 300px;
            padding: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        .search-bar button {
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        .search-bar button i {
            font-size: 20px;
            color: #007bff;
        }

        .box {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            justify-content: center;
        }

        .card {
            position: relative;
            width: 300px;
            height: 300px;
            margin: 10px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }

        .card img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .card .content {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            padding: 15px;
            background-color: #edffef;
            color: #182f32;
            text-align: center;
            opacity: 0;
            transition: opacity 0.3s;
        }

        .card:hover .content {
            opacity: 1;
        }

        .card h3 {
            font-size: 20px;
            margin-top: 0;
            margin-bottom: 10px;
            color: #213032;
        }

        .card p {
            font-size: 14px;
            margin-bottom: 10px;
        }

        .card button {
            position: relative;
            background-color: #213032;
            color: #fff;
            text-decoration: none;
            border: 2px solid black;
            font-weight: bold;
            transition: 0.4s;
            padding: 13px 30px;
        }

        .card button:hover {
            background-color: #97a2a3;
            color: #213032;
            border: 2px solid black;
            cursor: pointer;
        }
    </style>
</head>

<body>
    <header>
        <div class="logo">
            <img src="logo.png" class="me-3" width="50px"> Flavoursome Recipes
        </div>
        <div class="nav-bar">
            <ul>
                <li> <a href="index.php">Home</a> </li>
                <li> <a href="about.html">About</a> </li>
                <li> <a href="#">Recipes</a> </li>
                <li> <a href="contact.html">Contact</a> </li>
                <?php if (isset($_SESSION['user_id'])) : ?>
                    <li> <a href="logout.php">Logout</a> </li>
                <?php else : ?>
                    <li> <a href="login.php">Login</a> </li>
                <?php endif; ?>
            </ul>
        </div>
    </header>

    <div class="recipe">
        <div class="search-bar">
            <form method="get" action="recipe.php">
                <input type="search" name="search" id="search" placeholder="Search recipe">
                <button type="submit"><i class="bi bi-search"></i></button>
            </form>
        </div>
        <h2>Featured Recipes</h2>
        <div class="box" id="recipes-box">
            <?php
            foreach ($recipes as $recipe) {
                echo '<div class="card">';
                echo '<img src="' . $recipe['image'] . '" alt="Recipe Image">';
                echo '<div class="content">';
                echo '<h3>' . $recipe['name'] . '</h3>';
                echo '<p>' . $recipe['description'] . '</p>';
                echo '<div class="container">';
                echo '<a href="view.php?id=' . $recipe['id'] . '"><i class="bi bi-eye-fill fs-3 me-3 text-dark"></i></a>';
                if (isset($_SESSION['user_id'])) {
                    echo '<a href="edit.php?id=' . $recipe['id'] . '"><i class="bi bi-pencil-square fs-3 me-3 text-dark"></i></a>';
                    echo '<a href="delete.php?id=' . $recipe['id'] . '"><i class="bi bi-trash3 fs-3 me-3 text-dark"></i></a>';
                }
                echo '</div>';
                echo '</div>';
                echo '</div>';
            }
            ?>
        </div>
        <?php if (isset($_SESSION['user_id'])) : ?>
            <div class="fixed">
                <i class="bi bi-plus-circle fs-3"><a href="create.html" style="color: white;"><b>Add</b></a></i>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    <script>
        // JavaScript to handle the live search
        document.getElementById('search').addEventListener('keyup', function() {
            let searchQuery = this.value;

            fetch(`recipe.php?search=${searchQuery}&ajax=1`)
                .then(response => response.json())
                .then(data => {
                    let recipesBox = document.getElementById('recipes-box');
                    recipesBox.innerHTML = '';

                    data.forEach(recipe => {
                        let card = document.createElement('div');
                        card.className = 'card';

                        let img = document.createElement('img');
                        img.src = recipe.image;
                        img.alt = 'Recipe Image';
                        card.appendChild(img);

                        let content = document.createElement('div');
                        content.className = 'content';

                        let h3 = document.createElement('h3');
                        h3.innerText = recipe.name;
                        content.appendChild(h3);

                        let p = document.createElement('p');
                        p.innerText = recipe.description;
                        content.appendChild(p);

                        let container = document.createElement('div');
                        container.className = 'container';

                        let viewLink = document.createElement('a');
                        viewLink.href = `view.php?id=${recipe.id}`;
                        viewLink.innerHTML = '<i class="bi bi-eye-fill fs-3 me-3 text-dark"></i>';
                        container.appendChild(viewLink);

                        <?php if (isset($_SESSION['user_id'])) : ?>
                        let editLink = document.createElement('a');
                        editLink.href = `edit.php?id=${recipe.id}`;
                        editLink.innerHTML = '<i class="bi bi-pencil-square fs-3 me-3 text-dark"></i>';
                        container.appendChild(editLink);

                        let deleteLink = document.createElement('a');
                        deleteLink.href = `delete.php?id=${recipe.id}`;
                        deleteLink.innerHTML = '<i class="bi bi-trash3 fs-3 me-3 text-dark"></i>';
                        container.appendChild(deleteLink);
                        <?php endif; ?>

                        content.appendChild(container);
                        card.appendChild(content);

                        recipesBox.appendChild(card);
                    });
                });
        });
    </script>
</body>

</html>
