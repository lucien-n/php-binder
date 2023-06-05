<?php
require "functions.php";
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
session_start();

if (isset($_GET['login'])) {
    $response = login($_POST['email'], $_POST['password']);
    if ($response === true) {
        header("location: /index.php");
        exit;
    }

    header('location: /error.php?error=Login+failed');
    exit;
}

if (isset($_GET['register'])) {
    $username = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $user_gender = $_POST['gender'];
    $user_likedGender = $_POST['liked_gender'];
    $user_age = $_POST['age'];
    $user_bio = $_POST['bio'];
    $response = registerUser($username, $user_email, $user_password, $user_gender, $user_likedGender, $user_age, $user_bio, $_FILES['image']);

    if ($response === "success") {
        login($username, $user_password);
        header("location: /index.php");
        exit;
    } else {
        header('location: /error.php?error=Registration+failed');
        exit;
    }
}

if (isset($_GET['logout'])) {
    logout();
    header("Location: /auth/login.php");
    exit;
}
?>
