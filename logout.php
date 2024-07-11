<?php
session_start(); // Start the session

// Unset all of the session variables
$_SESSION = [];

// Destroy the session
session_destroy();

// Redirect to the login page with a success message
header("Location: login.php?logout=success");
exit();
?>
