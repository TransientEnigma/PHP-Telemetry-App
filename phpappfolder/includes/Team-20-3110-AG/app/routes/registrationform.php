<?php
/**
 * registrationform.php
 * Route to Registeration form
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 30/11/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/registrationform', function (Request $request, Response $response) use ($app) {

    return $this->view->render($response,'registrationform.html.twig',
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
            'page_heading_1' => 'Please enter registration details',
        ]
    );

})->setName('registrationform');
