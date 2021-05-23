<?php
/**
 * autogensenddata.php
 *
 * Route to create auto
 * simulation telemetry entries
 *
 * Author: G Conway
 * Email: p2613423@my365.dmu.ac.uk
 * Date: 29/12/2020
 *
 * @author G E Conway <p2613423@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/processsenddata', function (Request $request, Response $response) use($app) {
    $session_data_handler = $app->getContainer()->get('sessionDataHandler');
    $session_variables = $session_data_handler->retrieveFromSessionDb($app);
    if($session_variables['username'] === 'DJG' and $session_variables['mobile'] === 'DJG')
    {
        return $this->view->render($response,'registerorlogin.html.twig', VIEW_UNLOGGED_ARRAY);
    }

    //Decrypt Session data (for views and telemetry)
    $encrypted_username = $session_variables['username'];
    $encoded_username = encode($app, $encrypted_username);
    $session_variables['username'] = decrypt($app, $encoded_username);
    $encrypted_loginout = $session_variables['loginout'];
    $encoded_loginout= encode($app, $encrypted_loginout);
    $session_variables['loginout'] = decrypt($app, $encoded_loginout);
    $encrypted_user_id = $session_variables['user_id'];
    $encoded_user_id = encode($app, $encrypted_user_id );
    $session_variables['user_id'] = decrypt($app, $encoded_user_id );
    $tainted_parameters = $request->getParsedBody();
 

     if (array_key_exists('keypadControl', $tainted_parameters )) {
         if (($tainted_parameters['keypadControl'] === "null") or ($tainted_parameters['motorControl'] === "null")) {
             $view_processsenddata_arr = VIEW_ARRAY;;
             $view_processsenddata_arr['navbar_username'] = $session_variables['username'];
             $view_processsenddata_arr['nav_button_login'] = $session_variables['loginout'];
             $view_processsenddata_arr['page_heading_1'] = 'Device Telemetry Transmission';
             $view_processsenddata_arr['page_text'] = 'Please input ALL sections';
             return $this->view->render($response, 'senddata.html.twig', $view_processsenddata_arr);
         }
     }
    $validator = $app->getContainer()->get('validator');
    $cleaned_parameters = $validator->cleanupParameters($app, $tainted_parameters);
    $simulator = $app->getContainer()->get('simWrapper');
    
    if ($cleaned_parameters['device_id']==='AG1'){
        $validator = $app->getContainer()->get('validator');
        $cleaned_parameters = $validator->cleanupParameters($app, $tainted_parameters);
        $temperature = $simulator -> getTemperature($cleaned_parameters['min_temp'], $cleaned_parameters['max_temp']);
        $input_string = $simulator->getInputString($temperature, strval(rand(0, 1)), strval(rand(0, 1)), strval(rand(0, 1)), strval(rand(0, 1)),strval(rand(0, 2)),strval(rand(0, 9)));
    }
    else {

        sleep(2);
        $date = null;
        $switch1 = '0';
        $switch2 = '0';
        $switch3 = '0';
        $switch4 = '0';
        $motor = '0';
        $keypad = null;
        if($cleaned_parameters['switchControl1']=='on') {
            $switch1 = '1';
        }
        if($cleaned_parameters['switchControl2']=='on') {
            $switch2 = '1';
        }
        if($cleaned_parameters['switchControl3']=='on') {
            $switch3 = '1';
        }
        if($cleaned_parameters['switchControl4']=='on') {
            $switch4 = '1';
        }
        if($cleaned_parameters['motorControl']=='motorFwd') {
            $motor = '1';
        }
        else {
            if($cleaned_parameters['motorControl']=='motorRvs') {
                $motor = '2';
            }
        }
        if($cleaned_parameters['keypadControl']!==null) {
            $keypad = $cleaned_parameters['keypadControl'];
        }
        else {
            $keypad = 'X';
        }
        $input_string = $simulator->getInputString($cleaned_parameters['tempValue'],$switch1,$switch2,$switch3,$switch4,$motor,$keypad);

    }

    $device_id = $cleaned_parameters['device_id'];
    $libSodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
    $encrypted_data = $libSodium_wrapper->encrypt($input_string);
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');
    $message_string = $base64_wrapper->encode_base64($encrypted_data);
    $soapClient = $app->getContainer()->get('soapClient');
    $date = new DateTime();
    $message_id = $device_id.strval($date->getTimestamp());
    $message =  '<id>'.$message_id.'</id><key>'.SOAP_ARRAY['soap_key'].'</key><usr>'.$session_variables['user_id'].'</usr><value>'.$message_string.'</value>';
    $soapClient->sendMessage(SOAP_ARRAY['soap_username'], SOAP_ARRAY['soap_password'], SOAP_ARRAY['soap_telephony_id'], $message, true, 'SMS');


    if ($device_id === 'AG1'){
        echo  'Device ID = '.$device_id.' Data = '.$input_string.'<br>';
        echo  'Sent as: '.$message.'<br><br>';
    }
    else
        {

          $view_senddata_arr = VIEW_ARRAY;
        $view_senddata_arr['navbar_username'] = $session_variables['username'];
        $view_senddata_arr['nav_button_login'] = $session_variables['loginout'];
        $view_senddata_arr['page_heading_1'] = 'Device Telemetry Transmission';
        $view_senddata_arr['page_text' ] = 'Thank you. Your data has been sent';
        return $this->view->render($response, 'senddata.html.twig', $view_senddata_arr);
    }

})->setName('processsenddata');


