<?php
/**
 * viewuserdata.php
 * Route to View User Data page for Administrators
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 18/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/viewuserata', function (Request $request, Response $response)  use ($app)
{
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
    $users_array = $user_model->getAllUsersFromDatabase();

    return $this->view->render($response,
        'viewuserdata.html.twig',
        [
            'bootstrap_css_path' => BOOTSTRAP_CSS,
            'bootstrap_jq_path'=> BOOTSTRAP_JQ,
            'bootstrap_js_path' => BOOTSTRAP_JS,
            'page_name' => PAGE_PATH,
            'navbar_username' => 'DJG',
            'nav_button_homepage' => 'Homepage',
            'nav_button_register' => 'Register',
            'nav_button_send' => 'Send Data',
            'nav_button_view' => 'View Data',
            'nav_button_login' => 'Login',
            'page_heading_2' => PROJECT_NAME,
            'page_heading_1' => 'Welcome',
            'page_footing' => 'Copyright(c) 2020 DJG',
            'page_text' => 'This is the View Users Data Page for Administrators',
            'array_of_users' => $users_array,
        ]);
})->setName('viewuserdata');