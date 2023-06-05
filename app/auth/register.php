<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/controllers/functions.php');

$register_template = $twig->load('/auth/register.html');

echo $register_template->render();
?>
