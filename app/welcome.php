<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');

$login_template = $twig->load('welcome.html');
echo $login_template->render();
?>