<?php
/**
 * userlogin.php
 * Route to User Login Page
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 18/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/userlogin', function (Request $request, Response $response) use ($app){
    session_regenerate_id();
    return $this->view->render($response,
        'userlogin.html.twig',
        [
            'bootstrap_css_path' => BOOTSTRAP_CSS,
            'bootstrap_jq_path'=> BOOTSTRAP_JQ,
            'bootstrap_js_path' => BOOTSTRAP_JS,
            'page_name' => PAGE_PATH,
            'navbar_username' => 'DJG',
            'page_heading_1' => 'User Login Page',
            'page_footing' => 'Copyright(c) 2020 DJG ',
            'page_text' => 'This is the User Login Page',
        ]
    );
})->setName('userlogin');