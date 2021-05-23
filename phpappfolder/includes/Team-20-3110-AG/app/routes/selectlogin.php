<?php
/**
 * selectlogin.php
 * Route to Login Selection Page
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 18/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->get('/selectlogin', function (Request $request, Response $response) use ($app){
    $universal_logger = $app->getContainer()->get('universalLogger');
    $session_data_handler = $app->getContainer()->get('sessionDataHandler');
    $session_variables = $session_data_handler->retrieveFromSessionDb($app);
    if($session_variables['username'] === 'DJG' and $session_variables['mobile'] === 'DJG')
    {
        //Default variable values so do nothing, stay on the current page
    }
    else
    {
        //user is logged in - get user_id
        $encrypted_user_id = $session_variables['user_id'];
        $encoded_user_id = encode($app, $encrypted_user_id );
        $session_variables['user_id'] = decrypt($app, $encoded_user_id );

        // Logging Event
        $universal_logger->logEvent('User '. $session_variables['user_id'].':   Navigated to Select Login Page');

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
        // Logging Event
        $universal_logger->logEvent('User '. $session_variables['user_id'].':   Logged Out');

    }

    return $this->view->render($response,
        'selectlogin.html.twig',
        [
            'keypad_css_path' => KEYPAD_CSS,
            'bootstrap_css_path' => BOOTSTRAP_CSS,
            'images_css_path' => IMAGES_CSS,
            'bootstrap_jq_path'=> BOOTSTRAP_JQ,
            'bootstrap_js_path' => BOOTSTRAP_JS,
            'homepage_image_path' => HOMEPAGE_JPG,
            'custom_jq_path'=> CUSTOM_JQ,
            'page_name' => PAGE_PATH,
            'navbar_username' => 'DJG',
            'page_heading_1' => 'Welcome',
            'page_text' => 'Please Select a Login',
        ]
    );
})->setName('selectlogin');