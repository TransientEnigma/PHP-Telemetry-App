<?php
/**
 * registerorlogin.php
 * Register or Login Page Route
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 32/01/2021
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/', function (Request $request, Response $response) use ($app){
    $universal_logger = $app->getContainer()->get('universalLogger');
    $user_model = $app->getContainer()->get('userModelObject');
    $user_database = $app->getContainer()->get('userDatabaseObject');
    $user_sql_queries = $app->getContainer()->get('userSqlQueriesObject');
    $settings_array = $app->getContainer()->get('settings');
    $user_model->setSqlQueries($user_sql_queries);
    $user_model->setUserDatabase($user_database);
    $database_connection_settings = $settings_array['database_settings'];
    $user_model->setDatabaseConnectionSettings($database_connection_settings);
    $user_model->setUniversalLogger($universal_logger);
    $user_model->deleteSession();
    session_regenerate_id();

    return $this->view->render($response,'registerorlogin.html.twig',VIEW_UNLOGGED_ARRAY);
})->setName('registerorlogin');