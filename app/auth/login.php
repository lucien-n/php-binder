<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . '/twig.php');

$login_template = $twig->load('login.html');

echo $login_template->render();
?>