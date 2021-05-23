<?php
/**
 * processadminlogin.php
 * Route to process Aministrator Login.
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 30/11/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/processadminlogin',
    function (Request $request, Response $response) use ($app)
    {
//        $tainted_login_details = $request->getParsedBody();
//        $tainted_username = $tainted_login_details['username'];
//        $tainted_password = $tainted_login_details['password'];
//        if(($tainted_username === "") or ($tainted_password === ""))
//        {
//            return $this->view->render($response,
//                'adminlogin.html.twig',
//                [
//                    'bootstrap_css_path' => BOOTSTRAP_CSS,
//                    'bootstrap_jq_path'=> BOOTSTRAP_JQ,
//                    'bootstrap_js_path' => BOOTSTRAP_JS,
//                    'page_name' => PAGE_PATH,
//                    'navbar_username' => 'DJG',
//                    'nav_button_homepage' => 'Homepage',
//                    'nav_button_register' => 'Register',
//                    'nav_button_send' => 'Send Data',
//                    'nav_button_view' => 'View Data',
//                    'nav_button_login' => 'Login',
//                    'page_heading_2' => PROJECT_NAME,
//                    'page_heading_1' => 'Please enter correct login details',
//                    'page_footing' => 'Copywrite(c) 2020 DJG ',
//                    'page_text' => 'This is the Select Login Page',
//                ]
//            );
//
//        }
//
//        return $this->view->render($response,
//            'adminhomepage.html.twig',
//            [
//                'bootstrap_css_path' => BOOTSTRAP_CSS,
//                'images_css_path' => IMAGES_CSS,
//                'bootstrap_jq_path'=> BOOTSTRAP_JQ,
//                'bootstrap_js_path' => BOOTSTRAP_JS,
//                'image_path' => ADMIN_HOMEPAGE_JPG,
//                'page_name' => PAGE_PATH,
//                'navbar_username' => $tainted_username,
//                'nav_button_homepage' => 'Homepage',
//                'nav_button_register' => 'Register',
//                'nav_button_send' => 'Send Data',
//                'nav_button_view' => 'View Data',
//                'nav_button_login' => 'Login',
//                'page_heading_2' => PROJECT_NAME,
//                'page_heading_1' => 'Welcome',
//                'page_footing' => 'Copywrite(c) 2020 ',
//                'page_text' => 'The Administrators Area',
//            ]
//        );
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
                'nav_button_homepage' => 'Homepage',
                'nav_button_register' => 'Register',
                'nav_button_send' => 'Send Data',
                'nav_button_view' => 'View Data',
                'nav_button_login' => 'Login',
                'page_heading_1' => 'Status 307 Temporary Redirect',
                'page_footing' => 'Copyright(c) 2020 ',
                'page_text' => 'This Area is under Maintenance',
            ]
        );
    })->setName('processadminlogin');