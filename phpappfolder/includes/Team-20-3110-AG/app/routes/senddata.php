<?php
/**
 * senddata.php
 * Route to send data page
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 17/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/senddata', function (Request $request, Response $response) use ($app) {
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
    $universal_logger->logEvent('User '. $session_variables['user_id'] . ':  Navigated to Device Manual Entry Page');


    //Decrypt Session data
    $encrypted_username = $session_variables['username'];
    $encoded_username = encode($app, $encrypted_username);
    $session_variables['username'] = decrypt($app, $encoded_username);
    $encrypted_loginout = $session_variables['loginout'];
    $encoded_loginout= encode($app, $encrypted_loginout);
    $session_variables['loginout'] = decrypt($app, $encoded_loginout);

    $view_senddata_arr = VIEW_ARRAY;
    $view_senddata_arr['navbar_username'] = $session_variables['username'];
    $view_senddata_arr['nav_button_login'] = $session_variables['loginout'];
    $view_senddata_arr['page_heading_1'] = 'Device Telemetry Transmission';
    $view_senddata_arr['page_text' ] = 'Enter telemetry and click Transmit to send';
    return $this->view->render($response, 'senddata.html.twig', $view_senddata_arr);
})->setName('senddata');