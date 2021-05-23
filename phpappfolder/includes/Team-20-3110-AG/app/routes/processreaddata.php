<?php

/**
 * processreaddata.php
 *
 * Slim route to read M2M data
 * and save to Maria DB
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


$app->post('/processreaddata',
    function (Request $request, Response $response) use ($app) {
        $session_data_handler = $app->getContainer()->get('sessionDataHandler');
        $session_variables = $session_data_handler->retrieveFromSessionDb($app);
        if($session_variables['username'] === 'DJG' and $session_variables['mobile'] === 'DJG')
        {
            return $this->view->render($response,'registerorlogin.html.twig', VIEW_UNLOGGED_ARRAY);
        }
        //test to get user_id
        $encrypted_user_id = $session_variables['user_id'];
        $encoded_user_id = encode($app, $encrypted_user_id );
        $session_variables['user_id'] = decrypt($app, $encoded_user_id );


        //Decrypt Session data
        $encrypted_username = $session_variables['username'];
        $encoded_username = encode($app, $encrypted_username);
        $session_variables['username'] = decrypt($app, $encoded_username);

        $encrypted_loginout = $session_variables['loginout'];
        $encoded_loginout= encode($app, $encrypted_loginout);
        $session_variables['loginout'] = decrypt($app, $encoded_loginout);

        $settings = $app->getContainer()->get('settings');

        $database_connection_settings = $settings['database_settings'];

        $connection = $app->getContainer()->get('connection');

        $connection->setPDOConnectionSettings($database_connection_settings);

        $pdo = $connection->newConnection();

        $sql1 = "SELECT * FROM tbl_telemetry where substring(tele_device_id,1,25) = :device_id AND tele_created_id = :created_id ";
        $sql2 = "INSERT INTO tbl_telemetry (tele_device_id, tele_created_id, tele_user_id, tele_data, tele_switch1, tele_switch2, tele_switch3, tele_switch4, tele_motor, tele_keypad, tele_temperature, tele_created, tele_added_m2m, tele_imported ) 
            VALUES (:device_id, :created_id, :user_id, :data, :switch1, :switch2, :switch3, :switch4, :motor, :keypad, :temperature, :created, :added, :imported)";

        $soapClient = $app->getContainer()->get('soapClient');

        $telemetry[] = $soapClient->peekMessages(SOAP_ARRAY['soap_username'], SOAP_ARRAY['soap_password'], 999999999, '', '44');

        libxml_use_internal_errors(true);
        $peek = ($telemetry[0]);

        $poke = array();
        foreach ($peek as $line) {
            $doc = simplexml_load_string($line);
            if ($doc === false) {
                libxml_clear_errors();
            } else {

                //echo SOAP_ARRAY['soap_key'].'<br>';
                if ($doc->message->key == SOAP_ARRAY['soap_key'] && substr($doc->message->id, 0, 2) == 'AG' ) {
                    array_push($poke, $line);
                }
            }

        }
        $posted = array();
        $lines = array();
        foreach ($poke as $xml_line) {
            $xml = new SimpleXMLElement($xml_line);

                    $stmt = $pdo->prepare($sql1);

                    $device_id = substr($xml->message->id, 0, 3);
                    $created_id = substr($xml->message->id, 3);

                    $stmt->bindValue(':device_id', $device_id);
                    $stmt->bindValue(':created_id', $created_id);

                    $stmt->execute();
                    $count = $stmt->fetchColumn();

                    if (!$count ) {

                        $stmt = $pdo->prepare($sql2);

                        $base64_wrapper = $app->getContainer()->get('base64Wrapper');
                        $libSodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
                        $encrypted_data = $xml->message->value;

                        $decrypted_string = $libSodium_wrapper->decrypt($base64_wrapper, $encrypted_data);

                        $data = substr($decrypted_string, 7, 6);
                        $minus = substr($decrypted_string, 6, 1);
                        $temperature = intval($data) / 100;
                        if ($minus == '1') {
                            $temperature = $temperature * -1;

                        }

                        $input = str_replace('/', '-', $xml->receivedtime);
                        $date1 = date('Y-m-d H:i:s', strtotime($input));
                        $date2 = date('Y-m-d H:i:s');
                        $created = date('Y-m-d H:i:s', intval($created_id) );
                        //Bind our variables.

                        $switch1 = 'off';
                        $switch2 = 'off';
                        $switch3 = 'off';
                        $switch4 = 'off';
                        $motor = 'off';
                        if (substr($decrypted_string, 0, 1)==1)
                        {
                            $switch1 = 'on';
                        }
                        if (substr($decrypted_string, 1, 1)==1)
                        {
                            $switch2 = 'on';
                        }
                        if (substr($decrypted_string, 2, 1)==1)
                        {
                            $switch3 = 'on';
                        }
                        if (substr($decrypted_string, 3, 1)==1)
                        {
                            $switch4 = 'on';
                        }
                        if (substr($decrypted_string, 4, 1)==1)
                        {
                            $motor = 'Reverse';
                        }
                        else{
                            if (substr($decrypted_string, 4, 1) == 2) {
                                $motor = 'Reverse';
                            }
                        }
                        $keypad = substr($decrypted_string, 5, 1);
                        if($keypad==='X'){
                            $keypad='0';
                        }
                        $user_id = $session_variables['user_id'];


                        $stmt->bindValue(':device_id', $device_id);
                        $stmt->bindValue(':created_id', $created_id);
                        $stmt->bindValue(':user_id', $user_id);
                        $stmt->bindValue(':data', $decrypted_string);
                        $stmt->bindValue(':switch1', $switch1);
                        $stmt->bindValue(':switch2', $switch2);
                        $stmt->bindValue(':switch3', $switch3);
                        $stmt->bindValue(':switch4', $switch4);
                        $stmt->bindValue(':motor', $motor);
                        $stmt->bindValue(':keypad', $keypad);
                        $stmt->bindValue(':temperature', $temperature);
                        $stmt->bindValue(':created', $created);
                        $stmt->bindValue(':added', $date1);
                        $stmt->bindValue(':imported', $date2);
                        //Execute the statement and insert the new posting
                        $stmt->execute();

                        // collect result to pass to view screen
                        $lines['device_id'] = $device_id;
                        $lines['created_id'] = $created_id;
                        $lines['switch1'] = $switch1;
                        $lines['switch2'] = $switch2;
                        $lines['switch3'] = $switch3;
                        $lines['switch4'] = $switch4;
                        $lines['motor'] = $motor;
                        $lines['keypad'] = $keypad;
                        $lines['temperature'] = $temperature;
                        $lines['user_id'] = "";
                        $lines['recno'] = strval($pdo->lastInsertId());

                        $posted[] = $lines;

                    }
                }

        $view_senddata_arr = VIEW_ARRAY;
        $view_senddata_arr['navbar_username'] = $session_variables['username'];
        $view_senddata_arr['nav_button_login'] = $session_variables['loginout'];
        $view_senddata_arr['page_heading_1'] = 'Device Telemetry Transmission';

        if (empty($posted)) {

            $view_senddata_arr['page_text'] = 'There were no unread Telemetry messages on the server.';
        }
        else{


?>

        <div id="table-wrapper" class="container" >
  <div id="table-scroll" class="overflow-auto" style="height: 400px !important; width: 100%; align-self: center" >
    <table style="align-self: center; align-items: center; align-content: center">
        <thead>
            <tr>
                <th style="width: 10%; text-align: center;"><span class="text">ID</span></th>
                <th style="width: 10%; text-align: center;"><span class="text">Switch1</span></th>
                <th style="width: 10%; text-align: center;"><span class="text">Switch2</span></th>
                <th style="width: 10%; text-align: center;"><span class="text">Switch3</span></th>
                <th style="width: 10%; text-align: center;"><span class="text">Switch4</span></th>
                <th style="width: 10%; text-align: center;"><span class="text">Motor</span></th>
                <th style="width: 10%; text-align: center;"><span class="text">Keypad</span></th>
                <th style="width: 10%; text-align: center;"><span class="text">Temperature</span></th>
            </tr>
        </thead>

        <tbody>
        <?php


		 foreach($posted as $line)

		 {
			 echo " ";

			 echo 	'<tr>
                        <td>'.$line['device_id'].$line['created_id'].'</td>
			 			<td style="width: 10%; text-align: center">'.$line['switch1'].'</td> 
			 			<td style="width: 10%; text-align: center">'.$line['switch2'].'</td> 
			 			<td style="width: 10%; text-align: center">'.$line['switch3'].'</td>
						 <td style="width: 10%; text-align: center">'.$line['switch4'].'</td> 
						 <td style="width: 10%; text-align: center">'.$line['motor'].'</td> 
						 <td style="width: 10%; text-align: center">'.$line['keypad'].'</td> 
						<td style="width: 10%; text-align: center">'.$line['temperature'].'</td></tr>
					</tr>'
					;
		 }


		 echo ' </tbody>
</table>
</div>' ;

            $view_senddata_arr['page_text'] = 'All data read is below';
        }

        return $this->view->render($response, 'importdata.html.twig', $view_senddata_arr);





            })->setName('processreaddata');

