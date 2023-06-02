<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');

$login_template = $twig->load('/auth/register.html');
echo $login_template->render();
?>