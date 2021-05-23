<?php
/**
 * bootstrap.php
 * loads libraries, setting, sets up container and app
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 29/11/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
session_start();
require 'vendor/autoload.php';
$settings = require __DIR__ . '/app/settings.php';
$container = new \Slim\Container($settings);
require __DIR__ . '/app/dependencies.php';
$app = new \Slim\App($container);
require __DIR__ . '/app/srcs.php';
require __DIR__ . '/app/routes.php';
$app->run();
