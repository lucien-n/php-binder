<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');

$register_template = $twig->load('/auth/register.html');
echo $register_template->render();
?>