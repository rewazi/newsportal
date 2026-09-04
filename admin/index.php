<?php

session_start();

require_once __DIR__ . '/../inc/Database.php';
require_once __DIR__ . '/modelAdmin/modelAdmin.php';
require_once __DIR__ . '/controllerAdmin/controllerAdmin.php';
require_once __DIR__ . '/routeAdmin/routingAdmin.php';

$router = new RoutingAdmin();
$router->run();
