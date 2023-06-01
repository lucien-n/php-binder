<?php
require_once('connection.php');
require_once('twig.php');

$template = $twig->load('index.html');

echo $template->render(['name' => 'djeneba']);
?>