<?php
require_once($_SERVER["DOCUMENT_ROOT"]. '/utils/connection.php');
require_once('utils/twig.php');

$template = $twig->load('index.html');

echo $template->render(['name' => 'djeneba']);
?>