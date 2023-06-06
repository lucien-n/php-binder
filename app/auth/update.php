<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/functions.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
session_start();

$user = $_SESSION['user'];
if (isset($_POST['action']) && $_POST['action'] === 'update_username') {
    $newUsername = $_POST['new_username'];
    $userUuid = $user->getUuid(); 
    
    $updateResult = updateUsername($userUuid, $newUsername);
    if ($updateResult === true) {
        $user->setUsername($newUsername);
        header("Location: /profile.php");
        exit;
    } else {
        
        header("Location: /error.php?error=Username+update+failed");
        exit;
    }
}

if (isset($_POST['action']) && $_POST['action'] === 'update_bio') {
    $newBio = $_POST['new_bio'];
    $userUuid = $user->getUuid(); 
    
    $updateResult = updateBio($userUuid, $newBio);
    if ($updateResult === true) {
        $user->setBio($newBio);
        header("Location: /profile.php");
        exit;
    } else {
        
        header("Location: /error.php?error=Bio+update+failed");
        exit;
    }
}

?>