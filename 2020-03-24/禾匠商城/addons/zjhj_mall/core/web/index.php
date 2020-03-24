<?php
error_reporting(E_ERROR | E_PARSE);

require __DIR__ . '/../vendor/autoload.php';

$app = new app\hejiang\Application();
$app->run();
