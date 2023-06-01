<?php
require "functions.php";
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');


if (isset($_POST['login'])) {
    $response = login($_POST['username'], $_POST['password']);
    if ($response === true) {
        $message = "Login successful";
        echo "Login successful";
        exit;
    } else {
        echo "Login failed";
    }
}

if (isset($_POST['register'])) {
    $response = registerUser($_POST['username'], $_POST['email'], $_POST['password'], $_POST['gender'], $_POST['liked_gender'], $_POST['age'], $_POST['bio'], );
    if ($response === "success") {
        $message = "Registration successful";
        echo $login_template->render(['message' => $message]);
        exit;
    } else {
        echo "Registration failed";
    }
}

if (isset($_GET['logout'])) {

}
?>