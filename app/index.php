<?php
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/connection.php');
require_once($_SERVER["DOCUMENT_ROOT"] . '/utils/twig.php');

$index_html = $twig->load('index.html');
echo $index_html->render(['name' => 'djeneba']);
?>