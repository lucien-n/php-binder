<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/functions.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
session_start();


$user = $_SESSION['user'];
if (isset($_POST['action']) && $_POST['action'] === 'delete_account') {
    $userUuid = $user->getUuid();
    $deleteResult = deleteAccount($userUuid);
    if ($deleteResult === true) {
        session_destroy();
        header("Location: /?message=Account+deleted+successfully");
        exit;
    } else {
        header("Location: /error.php?error=Account+deletion+failed");
        exit;
    }
}

?>