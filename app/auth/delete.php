<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/functions.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
session_start();

if (isset($_POST['action']) && $_POST['action'] === 'delete_account') {
    if (isset($_SESSION['user']) && $_SESSION['user'] instanceof User) {
        $userUuid = $_SESSION['user']->getUuid();
        deleteAccount($userUuid);
        header("Location: /index.php?message=Account+deleted+successfully");
        exit;
    } else {
        header("Location: /error.php?error=Account+deletion+failed");
        exit;
    }
}
?>
