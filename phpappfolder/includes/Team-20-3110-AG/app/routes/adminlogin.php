<?php

/**
 * userlogin.php
 * Administrator Login Route
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 18/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/adminlogin', function (Request $request, Response $response) {
//    return $this->view->render($response,
//        'adminlogin.html.twig',
//        [
//            'bootstrap_css_path' => BOOTSTRAP_CSS,
//            'bootstrap_jq_path'=> BOOTSTRAP_JQ,
//            'bootstrap_js_path' => BOOTSTRAP_JS,
//            'page_name' => PAGE_PATH,
//            'nav_button_homepage' => 'Homepage',
//            'nav_button_register' => 'Register',
//            'nav_button_send' => 'Send Data',
//            'nav_button_view' => 'View Data',
//            'nav_button_login' => 'Login',
//            'page_heading_2' => PROJECT_NAME,
//            'page_heading_1' => 'Administrator Login Page',
//            'page_footing' => 'Copyright(c) 2020 DJG ',
//            'page_text' => 'This is the Administrator Login Page',
//        ]);
    // Status 307 Temporary Redirect
    $response = $response->withStatus(307);
    return $this->view->render($response,
        'error.html.twig',
        [
            'bootstrap_css_path' => BOOTSTRAP_CSS,
            'bootstrap_jq_path' => BOOTSTRAP_JQ,
            'bootstrap_js_path' => BOOTSTRAP_JS,
            'page_name' => PAGE_PATH,
            'navbar_username' => PROJECT_LOGO,
            'page_heading_1' => 'Status 307 Temporary Redirect',
            'page_footing' => 'Copyright(c) 2020 ',
            'page_text' => 'This Area is under Maintenance',
        ]
    );
})->setName('adminlogin');