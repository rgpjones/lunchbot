<?php

use RgpJones\Rotaman\Application;

require_once __DIR__ . '/vendor/autoload.php';

$config = simplexml_load_file(__DIR__ . '/config.xml');

if ($_POST['token'] != $config->token) {
    throw new \RunTimeException('Invalid Request');
}

$config->user = $_POST['user_name'];
$config->channel = $_POST['channel_name'];

$app = new Application(
    [
        'config' => $config,
    ]
);
$app->run();

