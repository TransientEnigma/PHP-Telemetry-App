<?php
/**
 * viewtelemdata.php
 *
 * Slim route to read data
 * from the Maria DB
 * and show on screen
 *
 * Author: G Conway
 * Email: p2613423@my365.dmu.ac.uk
 * Date: 29/12/2020
 *
 * @author G E Conway <p2613423@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
/**
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

$app->get('/viewtelemdata', function (Request $request, Response $response) use($app){
    $session_data_handler = $app->getContainer()->get('sessionDataHandler');
    $session_variables = $session_data_handler->retrieveFromSessionDb($app);
    if($session_variables['username'] === 'DJG' and $session_variables['mobile'] === 'DJG')
    {
        return $this->view->render($response,'registerorlogin.html.twig',VIEW_UNLOGGED_ARRAY);
    }
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
    $autosend_data_arr['page_heading_1'] = 'Please enter parameters for viewing telemetry data';
    $autosend_data_arr['page_text'] = 'Make automatic selections from the options provided';
    return $this->view->render($response, 'selecttelemdata.html.twig',$autosend_data_arr);
})->setName('viewtelemdata');