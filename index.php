<?php

require_once __DIR__.'/vendor/autoload.php';

$app = new Silex\Application();

$app->get('/', function() use($app) {
    $response = <<<HTML
<h1>Hello, there! </h1>
HTML;

    return $response;
});

$app->run();