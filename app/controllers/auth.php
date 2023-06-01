<?php
require "functions.php";

if (isset($_POST['login'])) {

}

if (isset($_POST['register'])) {
    $response = registerUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['gender'], $_POST['liked_gender'], $_POST['age'], $_POST['bio'], );
}

if (isset($_GET['logout'])) {

}
?>