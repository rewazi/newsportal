<?php

session_start();
require_once __DIR__. '/route/routing.php';

$router = new Routing();
$router->run();