<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $message = $_POST['message'];
    $servername = "localhost";
    $username = "root";
    $password = ""; 

    $conn = new mysqli($servername, $username, $password, "mydatabase");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "INSERT INTO contact (name, email, message)
            VALUES ('$name', '$email', '$message')";

    if ($conn->query($sql) === TRUE) {
        echo "<script type='text/javascript'>alert('Form submitted successfully!');</script>";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $conn->close();
}
?>






