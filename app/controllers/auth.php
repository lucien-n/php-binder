<?php
require "functions.php";
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
session_start();

if (isset($_POST['login'])) {
    $response = login($_POST['username'], $_POST['password']);
    if ($response === true) {
        $message = "Login successful";
        echo "Login successful";
        header("Location: /index.php");
        exit;
    } else {
        echo "Login failed";
    }
}

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $user_email = $_POST['email'];
    $user_password = $_POST['password'];
    $user_gender = $_POST['gender'];
    $user_likedGender = $_POST['liked_gender'];
    $user_age = $_POST['age'];
    $user_bio = $_POST['bio'];
    $response = registerUser($username, $user_email, $user_password, $user_gender, $user_likedGender, $user_age, $user_bio);

    if ($response === "success") {
        login($username, $user_password);
        exit;
    } else {
        echo "Registration failed";
    }
}

if (isset($_GET['logout'])) {
    logout();
    header("Location: /auth/login.php");
    exit;
}
?>