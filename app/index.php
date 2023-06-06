<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/connection.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/log.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/db_functions.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_binder.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_auth.php');

session_start();

// $user = $_SESSION["user"];
$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;

$binder_user = null;

if (isset($user) && $user instanceof User) {
    $user_uuid = $user->getUuid();
    $searching = true;
    $binder_user = null;
    $potential_binder = null;
    while ($searching) {
        if (rand(1, 2) == 1 && user_has_pending_likes($conn, $user_uuid)) {
            $potential_binder = get_user_pending_like($conn, $user_uuid);
            $searching = false;
        } else {
            $uninteracted_binder_uuid = get_binder($conn, $user_uuid, $user->getGender(), $user->getLikedGender());
            $potential_binder = get_binder_by_uuid($conn, $uninteracted_binder_uuid);
            $searching = false;
        }
    }

    if (isset($potential_binder)) {
        $binder_user = new UserBinder($potential_binder[1], $potential_binder[4], $potential_binder[5], $potential_binder[6], $potential_binder[7], $potential_binder[8], $potential_binder[9], $potential_binder[10], $potential_binder[12]);
    }
}
if (isset($_GET['message'])) {
    $message = $_GET['message'];
    echo "<p class='success-message'>$message</p>";
}
$index_html = $twig->load('index.html');
echo $index_html->render(['binder' => $binder_user, 'user' => $user]);
?>