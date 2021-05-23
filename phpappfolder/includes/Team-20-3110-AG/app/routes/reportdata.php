<?php

/**
 * Send Data Route
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/reportdata', function (Request $request, Response $response) use($app) {
    $session_data_handler = $app->getContainer()->get('sessionDataHandler');
    $session_variables = $session_data_handler->retrieveFromSessionDb($app);
    if($session_variables['username'] === 'DJG' and $session_variables['mobile'] === 'DJG')
    {

        return $this->view->render($response,'registerorlogin.html.twig',VIEW_UNLOGGED_ARRAY);
    }
    //get user_id
    $encrypted_user_id = $session_variables['user_id'];
    $encoded_user_id = encode($app, $encrypted_user_id );
    $session_variables['user_id'] = decrypt($app, $encoded_user_id );

    // Logging Event
    $universal_logger = $app->getContainer()->get('universalLogger');
    $universal_logger->logEvent('User '. $session_variables['user_id'] . ':  Navigated to Report Page');

    //Decrypt Session data
    $encrypted_username = $session_variables['username'];
    $encoded_username = encode($app, $encrypted_username);
    $session_variables['username'] = decrypt($app, $encoded_username);
    $encrypted_loginout = $session_variables['loginout'];
    $encoded_loginout= encode($app, $encrypted_loginout);
    $session_variables['loginout'] = decrypt($app, $encoded_loginout);

    // Status 307 Temporary Redirect
    $response = $response->withStatus(307);
    return $this->view->render($response,
        'error.html.twig',
        [
            'bootstrap_css_path' => BOOTSTRAP_CSS,
            'bootstrap_jq_path' => BOOTSTRAP_JQ,
            'bootstrap_js_path' => BOOTSTRAP_JS,
            'page_name' => PAGE_PATH,
            'navbar_username' => $session_variables['username'],
            'page_heading_1' => 'Status 307 Temporary Redirect',
            'page_footing' => 'Copyright(c) 2020 ',
            'page_text' => 'This Area is under Maintenance',
        ]
    );
//    return $this->view->render($response,
//        'reportdata.html.twig',
//        [
//            'keypad_css_path' => KEYPAD_CSS,
//            'bootstrap_css_path' => BOOTSTRAP_CSS,
//            'bootstrap_jq_path'=> BOOTSTRAP_JQ,
//            'bootstrap_js_path' => BOOTSTRAP_JS,
//            'custom_jq_path'=> CUSTOM_JQ,
//            'navbar_username' => 'DJG',
//            'nav_button_homepage' => 'Homepage',
//            'nav_button_register' => 'Register',
//            'nav_button_send' => 'Send Data',
//            'nav_button_view' => 'View Data',
//            'nav_button_login' => 'Login',
//            'page_heading_2' => PROJECT_NAME,
//            'page_heading_1' => 'Device Telemetry Transmission',
//            'page_footing' => 'Copyright(c) 2020 ',
//            'page_text' => 'Enter telemetry and click Transmit to send',
//        ]);
})->setName('reportdata');