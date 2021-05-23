<?php

/**
 * View Data Route
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->get('/importdata', function (Request $request, Response $response) {
    //simply some test data to pass into the view to generate the table
    $dummy_device_data_1 = [
        'device_temp' => '16.1',
        'switch_1_state' => 'on',
        'switch_2_state' => 'off',
        'switch_3_state' => 'off',
        'switch_4_state' => 'off',
        'motor_forward_reverse' => 'forward',
        'keypad_input' => '1',
    ];
    $dummy_device_data_2 = [
        'device_temp' => '21.6',
        'switch_1_state' => 'on',
        'switch_2_state' => 'on',
        'switch_3_state' => 'off',
        'switch_4_state' => 'off',
        'motor_forward_reverse' => 'reverse',
        'keypad_input' => '2',
    ];
    $dummy_device_data_3 = [
        'device_temp' => '30.8',
        'switch_1_state' => 'on',
        'switch_2_state' => 'on',
        'switch_3_state' => 'on',
        'switch_4_state' => 'off',
        'motor_forward_reverse' => 'forward',
        'keypad_input' => '3',
    ];
    $dummy_device_data_4 = [
        'device_temp' => '44.7',
        'switch_1_state' => 'on',
        'switch_2_state' => 'on',
        'switch_3_state' => 'on',
        'switch_4_state' => 'off',
        'motor_forward_reverse' => 'forward',
        'keypad_input' => '4',
    ];
    $dummy_device_objects[] =  $dummy_device_data_1;
    $dummy_device_objects[] =  $dummy_device_data_2;
    $dummy_device_objects[] =  $dummy_device_data_3;
    $dummy_device_objects[] =  $dummy_device_data_4;

//foreach( $dummy_device_objects as $array){
//    foreach($array as $key => $value)
//    echo "$key => $value<br>";
//}
//    die();
    return $this->view->render($response,
        'importdata.html.twig',
        [
            'bootstrap_css_path' => BOOTSTRAP_CSS,
            'bootstrap_jq_path'=> BOOTSTRAP_JQ,
            'bootstrap_js_path' => BOOTSTRAP_JS,
            'page_name' => PAGE_PATH,
            'navbar_username' => 'DJG',
            'nav_button_homepage' => 'Homepage',
            'nav_button_register' => 'Register',
            'nav_button_send' => 'Send Data',
            'nav_button_view' => 'View Data',
            'nav_button_login' => 'Login',
            'page_heading_2' => PROJECT_NAME,
            'page_heading_1' => 'Welcome',
            'page_footing' => 'Copyright(c) 2020 ',
            'page_text' => 'This is the View Data Page',
            'array_of_devices' => $dummy_device_objects,
        ]);
})->setName('importdata');