<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/connection.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/models/user_binder.php');

$binder_user = new UserBinder("123uuid", "Katya", 0, 1, "https://placehold.co/600x400?text=Katya", 23, "I'm someone", 18289442, 18289442);

$index_html = $twig->load('index.html');
echo $index_html->render(['binder' => $binder_user]);
?>