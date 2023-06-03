<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/connection.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/log.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_binder.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_auth.php');

session_start();

// $user = $_SESSION["user"];
$user = isset($_SESSION["user"]) ? $_SESSION["user"] : null;

$binder_user = null;

if (isset($user) && $user instanceof User) {
    $user_has_pending_likes = 0;
    $statement = $conn->prepare("SELECT * FROM pending WHERE liked_uuid = ? LIMIT 1");
    $statement->execute([$user->getUuid()]);
    $binder_who_liked_user = $statement->get_result()->fetch_assoc();
    if (isset($binders_who_liked_user[0])) {
        $user_has_pending_likes = 1;
    }


    if ($user_has_pending_likes == 1 && rand(1, 2) == 1) {
        $binder_user = reset($binder_who_liked_user);
    } else {
        $statement = $conn->prepare("SELECT u.*
FROM users u
LEFT JOIN dislikes d ON u.uuid = d.disliked_uuid
LEFT JOIN matchs m ON (u.uuid = m.uuid_one OR u.uuid = m.uuid_two)
LEFT JOIN pending p ON u.uuid = p.liked_uuid
WHERE d.disliked_uuid IS NULL
  AND m.id IS NULL
  AND p.liked_uuid IS NULL
  AND NOT u.uuid = ?");
        $statement->execute([$user->getUuid()]);
        $data = $statement->get_result()->fetch_row();
        if (isset($data[0]))
            $binder_user = new UserBinder($data[1], $data[4], $data[5], $data[6], $data[7], $data[8], $data[9], $data[10], $data[12]);
        else
            $binder_user = null;
    }
}

$index_html = $twig->load('index.html');
echo $index_html->render(['binder' => $binder_user, 'user' => $user]);
?>