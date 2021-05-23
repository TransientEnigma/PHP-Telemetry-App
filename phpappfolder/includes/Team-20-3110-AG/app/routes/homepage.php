<?php
/**
 * homepage.php
 * Homepage Route
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 18/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/homepage', function (Request $request, Response $response) use ($app) {


    $session_data_handler = $app->getContainer()->get('sessionDataHandler');
    $session_variables = $session_data_handler->retrieveFromSessionDb($app);
    if($session_variables['username'] === 'DJG' and $session_variables['mobile'] === 'DJG')
    {
        return $this->view->render($response,'registerorlogin.html.twig', VIEW_UNLOGGED_ARRAY);
    }

    //get user_id
    $encrypted_user_id = $session_variables['user_id'];
    $encoded_user_id = encode($app, $encrypted_user_id );
    $session_variables['user_id'] = decrypt($app, $encoded_user_id );

    // Logging Event
    $universal_logger = $app->getContainer()->get('universalLogger');
    $universal_logger->logEvent('User '. $session_variables['user_id'] . ':  Navigated to Homepage');

    //Decrypt Session data
    $encrypted_mobile = $session_variables['mobile'];
    $encoded_mobile = encode($app, $encrypted_mobile);
    $session_variables['mobile'] = decrypt($app, $encoded_mobile);

    $encrypted_username = $session_variables['username'];
    $encoded_username = encode($app, $encrypted_username);
    $session_variables['username'] = decrypt($app, $encoded_username);

    $encrypted_loginout = $session_variables['loginout'];
    $encoded_loginout= encode($app, $encrypted_loginout);
    $session_variables['loginout'] = decrypt($app, $encoded_loginout);



    $view_homepage_arr = VIEW_ARRAY;
    $view_homepage_arr['image_path'] = USER_HOMEPAGE_JPG;
    $view_homepage_arr['navbar_username'] = $session_variables['username'];
    $view_homepage_arr['page_mobile'] = $session_variables['mobile'];
    $view_homepage_arr['nav_button_login'] = $session_variables['loginout'];
    $view_homepage_arr['page_heading_1'] = 'Welcome';
    $view_homepage_arr['page_text'] = 'The Registered User Area';
    return $this->view->render($response, 'userhomepage.html.twig',$view_homepage_arr);
})->setName('homepage');


