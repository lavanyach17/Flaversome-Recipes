<?php
session_start(); // Start the session

$errors = [];

// Check for logout success message
$logout_message = '';
if (isset($_GET['logout']) && $_GET['logout'] === 'success') {
    $logout_message = "<div class='alert alert-success'>You have successfully logged out.</div>";
}

// Check if the form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Perform validation
    $username = trim($_POST['name']);
    $password = trim($_POST['password']);

    if (empty($username)) {
        $errors[] = "Username is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    // If no errors, proceed with authentication
    if (empty($errors)) {
        $host = 'localhost';
        $db = 'mydatabase';
        $user = 'root';
        $pass = '';

        $conn = new mysqli($host, $user, $pass, $db);

        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // Prepare and execute query
        $stmt = $conn->prepare('SELECT id, password FROM users WHERE name = ?');
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $stmt->bind_result($user_id, $hashed_password);
        $stmt->fetch();
        $stmt->close();

        // Verify password and set session variables
        if ($user_id && password_verify($password, $hashed_password)) {
            $_SESSION['user_id'] = $user_id;
            $_SESSION['name'] = $username;

            // Redirect to index.php or any other page after successful login
            header('Location: index.php');
            exit();
        } else {
            $errors[] = "<div class='alert alert-danger'>Invalid username or password</div>";
        }

        $conn->close();
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <section class="login">
        <h2>Login</h2>
        <div class="main">
            <img src="logo.png" alt="">
            <div class="contact-form">
                <form method="post">
                    <?php
                    // Display logout success message
                    if ($logout_message) {
                        echo $logout_message;
                    }
                    
                    // Display errors, if any
                    if (!empty($errors)) {
                        foreach ($errors as $error) {
                            echo "<p>$error</p>";
                        }
                    }
                    ?>
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>
                    <div class="container">
                      <div class="row">
                          <div class="col-6"><button type="submit" class="btn btn-primary">Login</button></div>
                          <div class="col-6"><div class="button"><a href="register.php" class="btn btn-secondary">Register</a></div></div>
                      </div>
                      <a href="index.php" style="color: white;">Back to home</a>
                  </div>
                </form>
            </div>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" 
    integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" 
    integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</body>
</html>
