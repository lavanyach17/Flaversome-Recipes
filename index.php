<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recipe Management</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <header>
        <div class="logo">
            <img src="logo.png" class="me-3" width="50px"> Flavoursome Recipes
        </div>
        <div class="nav-bar">
            <ul>
                <li> <a href="#">Home</a> </li>
                <li> <a href="about.html">About</a> </li>
                <li> <a href="recipe.php">Recipes</a> </li>
                <li> <a href="contact.html">Contact</a> </li>
                <?php
                session_start();
                if (isset($_SESSION['user_id'])) {
                    // User is logged in, show Logout button
                    echo '<li> <a href="logout.php">Logout</a> </li>';
                } else {
                    // User is not logged in, show Login button
                    echo '<li> <a href="login.php">Login</a> </li>';
                }
                ?>
            </ul>
        </div>
    </header>
    <div class="hero">
        <div class="content">
            <h4>Unleash Your Culinary passion</h4>
            <h1>Discover Taste Sensation</h1>
            <h3>Elevate Your Cooking Game</h3>
            <div class="button"><a href="recipe.php" style="color: white";>Explore Recipes</a></div>
        </div>
        
    </div>
    <footer>
        <div class="social-icons">
            <a href="#" class="social-icon"> <i class="fab fa-facebook"></i> </a>
            <a href="#" class="social-icon"> <i class="fab fa-twitter"></i> </a>
            <a href="#" class="social-icon"> <i class="fab fa-instagram"></i> </a>
        </div>
        <h5>CopyRight © 2023 By Flavoursome Recipes </h5>
    </footer>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
