<?php
/**
 * autosenddata.php
 *
 * Slim route to collect parameters
 * to create auto telemetry entries
 *
 * Author: G Conway
 * Email: p2613423@my365.dmu.ac.uk
 * Date: 29/12/2020
 *
 * @author G E Conway <p2613423@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
/**
 * autosenddata.php
 * Updated with session persistence
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 15/01/2021
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/autosenddata', function (Request $request, Response $response) use($app){
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
    $universal_logger->logEvent('User '. $session_variables['user_id'] . ':  Navigated to Simulation Page');

    //Decrypt Session data
    $encrypted_username = $session_variables['username'];
    $encoded_username = encode($app, $encrypted_username);
    $session_variables['username'] = decrypt($app, $encoded_username);
    $encrypted_loginout = $session_variables['loginout'];
    $encoded_loginout= encode($app, $encrypted_loginout);
    $session_variables['loginout'] = decrypt($app, $encoded_loginout);


    $autosend_data_arr = VIEW_ARRAY;
    $autosend_data_arr['navbar_username'] = $session_variables['username'];
    $autosend_data_arr['nav_button_login'] = $session_variables['loginout'];
    $autosend_data_arr['page_heading_1'] = 'Please enter parameters for automatic telemetry simulation';
    $autosend_data_arr['page_text'] = 'Enter details required and use TRANSMIT key to start automated transmissions';
    return $this->view->render($response, 'autosenddata.html.twig',$autosend_data_arr);
})->setName('autosenddata');