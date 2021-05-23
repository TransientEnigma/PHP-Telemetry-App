<?php
/**
 * dependencies.php
 * Dependancies
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 20/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use Telemetry\Base64Wrapper;
use Telemetry\LibSodiumWrapper;
use Telemetry\SimWrapper;
use Telemetry\TelemetryConnection;
use Users\DataValidator;
use Users\UserModel;
use Users\UserSqlQueries;
use Users\UserDatabase;
use Users\UserSessionModel;
use Users\HasherManager;
use Users\SessionDataHandler;
use Users\UserEncrypt;
use Telemetry\TelemetryValidator;
use Logging\UniversalLogger;

// Register component on container
$container['view'] = function ($container) {
  $view = new \Slim\Views\Twig(
    $container['settings']['view']['template_path'],
    $container['settings']['view']['twig'],
    [
      'debug' => true // This line should enable debug mode
    ]
  );

  // Instantiate and add Slim specific extension
  $basePath = rtrim(str_ireplace('index.php', '', $container['request']->getUri()->getBasePath()), '/');
  $view->addExtension(new Slim\Views\TwigExtension($container['router'], $basePath));

  return $view;
};

$container['dataValidationObject'] = function ($container) {

    $dataValidationObject = new DataValidator();
    return $dataValidationObject;
};

$container['userModelObject'] = function ($container) {

    $userModelObject = new UserModel();
    return $userModelObject;
};

$container['userDatabaseObject'] = function ($container) {

    $userDatabaseObject = new UserDatabase();
    return $userDatabaseObject;
};

$container['userSqlQueriesObject'] = function ($container) {

    $userSqlQueriesObject = new UserSqlQueries();
    return $userSqlQueriesObject;
};


$container['sessionModelObject'] = function ($container) {
    $session_model_object = new UserSessionModel();
    return $session_model_object;
};

$container['hashManager'] = function ($container) {
    $hashManager = new HasherManager();
    return $hashManager;
};

$container['sessionDataHandler'] = function ($container) {
    $sessionDataHandler = new SessionDataHandler();
    return $sessionDataHandler;
};


$container['simWrapper'] = function ($container) {
    $simWrapper = new SimWrapper();
    return $simWrapper;

};
$container['validator'] = function ($container) {
    $validator = new TelemetryValidator();
    return $validator;

};

$container['soapClient'] = function ($container) {
    $soapClient = new SoapClient('https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl');
    return $soapClient;

};

$container['connection'] = function ($container) {
    $connection = new TelemetryConnection();
    return $connection;

};

$container['libSodiumWrapper'] = function ($container) {
    $libSodiumWrapper = new LibSodiumWrapper();
    return $libSodiumWrapper;

};

$container['base64Wrapper'] = function ($container) {
    $base64Wrapper = new Base64Wrapper();
    return $base64Wrapper;

};
$container['universalLogger'] = function ($container) {
    $universalLogger= new UniversalLogger();
    return $universalLogger;

};
$container['userEncrypt'] = function ($container) {
    $userEncrypt= new UserEncrypt();
    return $userEncrypt;

};


