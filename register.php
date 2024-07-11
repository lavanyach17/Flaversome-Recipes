<?php
session_start();
$errors = [];

// Database connection
$host = 'localhost';
$db = 'mydatabase';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Form validation and processing
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['name']);
    $password = trim($_POST['password']);
    $email = trim($_POST['email']);

    if (empty($username)) {
        $errors[] = "Username is required";
    }

    if (empty($password)) {
        $errors[] = "Password is required";
    }

    if (empty($email)) {
        $errors[] = "Email is required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format";
    }

    if (empty($errors)) {
        // Check if username or email already exists
        $stmt = $conn->prepare('SELECT COUNT(*) FROM users WHERE name = ? OR email = ?');
        $stmt->bind_param('ss', $username, $email);
        $stmt->execute();
        $stmt->bind_result($userExists);
        $stmt->fetch();
        $stmt->close();

        if ($userExists) {
            $errors[] = "Username or Email already exists";
        
        } else {
            // Hash the password
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Insert user into the database
            $stmt = $conn->prepare('INSERT INTO users (name, password, email) VALUES (?, ?, ?)');
            $stmt->bind_param('sss', $username, $hashed_password, $email);
            $stmt->execute();
            $stmt->close();

            // Set session variable for successful registration
            $_SESSION['register_success'] = true;
            $_SESSION['success_message'] = "You have successfully registered. Please login.";

            header('Location: register.php');
            exit();
        }
    }

    // Store errors in session for display in HTML
    $_SESSION['errors'] = $errors;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" 
    integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>
<body>
    <section class="about">
        <h2>Register</h2>
        <div class="main">
            <img src="logo.png" alt="">
            <div class="contact-form">
                
                <form id="registerForm" method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
                    <label for="name">Name:</label>
                    <input type="text" name="name" id="name" required>
                    <label for="email">Email:</label>
                    <input type="email" name="email" id="email" required>
                    <label for="password">Password:</label>
                    <input type="password" id="password" name="password" required>

                    <!-- Display backend validation errors here -->
                    <?php
                    if (isset($_SESSION['errors'])) {
                        foreach ($_SESSION['errors'] as $error) {
                            echo "<div class='alert alert-danger'>$error</div>";
                        }
                        unset($_SESSION['errors']);
                    }

                    // Display success message if registration was successful
                    if (isset($_SESSION['register_success'])) {
                        echo "<div class='alert alert-success'>" . $_SESSION['success_message'] . "</div>";
                        unset($_SESSION['register_success']);
                        unset($_SESSION['success_message']);
                    }
                    ?>

                    <div class="container">
                      <div class="row">
                          <div class="col-6"><button type="submit" class="btn btn-primary">Register</button></div>
                          <div class="col-6"><div class="button"><a href="login.php" class="btn btn-secondary">Login</a></div></div>
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
