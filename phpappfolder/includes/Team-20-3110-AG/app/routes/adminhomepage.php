<?php

/**
 * adminhomepage.php
 * Administrator Homepage Route
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 30/11/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/adminhomepage', function (Request $request, Response $response) {
    return $this->view->render($response,
        'adminhomepage.html.twig',
        [
            'bootstrap_css_path' => BOOTSTRAP_CSS,
            'images_css_path' => IMAGES_CSS,
            'bootstrap_jq_path'=> BOOTSTRAP_JQ,
            'bootstrap_js_path' => BOOTSTRAP_JS,
            'image_path' => ADMIN_HOMEPAGE_JPG,
            'page_name' => PAGE_PATH,
            'navbar_username' => 'DJG',
            'nav_button_homepage' => 'Homepage',
            'nav_button_register' => 'Register',
            'nav_button_send' => 'Send Data',
            'nav_button_view' => 'View Data',
            'nav_button_login' => 'Login',
            'page_heading_2' => PROJECT_NAME,
            'page_heading_1' => 'Welcome',
            'page_footing' => 'Copyright(c) 2020 DJG ',
            'page_text' => 'Hello and welcome to the Telemetary App Designed by Team-20-3110-AG',
        ]);
})->setName('adminhomepage');