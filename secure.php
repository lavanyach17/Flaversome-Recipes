<?php
session_start();

if (!isset($_SESSION['users_id'])) {
    header('Location: login.php');
    exit;
}
?>