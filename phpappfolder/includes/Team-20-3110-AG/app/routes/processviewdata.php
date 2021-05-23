<?php

/**
 * processviewdata.php
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

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;


$app->post('/processviewdata',
    function (Request $request, Response $response) use ($app) {
        $session_data_handler = $app->getContainer()->get('sessionDataHandler');
        $session_variables = $session_data_handler->retrieveFromSessionDb($app);
        if($session_variables['username'] === 'DJG' and $session_variables['mobile'] === 'DJG')
        {
            return $this->view->render($response,'registerorlogin.html.twig', VIEW_UNLOGGED_ARRAY);
        }

//    //test to get user_id
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

        $parameters = $request->getParsedBody();

        $view = $parameters['choice'];

        $settings = $app->getContainer()->get('settings');

        $database_connection_settings = $settings['database_settings'];

        $connection = $app->getContainer()->get('connection');

        $connection->setPDOConnectionSettings($database_connection_settings);

        $pdo = $connection->newConnection();
        
        $params = 'tele_device_id, tele_created_id, tele_switch1, tele_switch2, tele_switch3,
            tele_switch4,  tele_motor,  tele_keypad,  tele_temperature';


        $sql1 = "SELECT $params FROM tbl_telemetry WHERE DATE(tele_created) = CURRENT_DATE";
        $sql2 = "SELECT $params FROM tbl_telemetry WHERE DATE(tele_created) = CURRENT_DATE-1";
        $sql3 = "SELECT $params FROM tbl_telemetry WHERE DATE(tele_created) <= CURRENT_DATE AND DATE(tele_created) >= CURRENT_DATE-6";

//        echo $view;
//        die;

        $stmt = null;
        switch ($view) {
            case 'today';
                $stmt = $pdo->prepare($sql1);
                break;
            case 'yesterday';
                $stmt = $pdo->prepare($sql2);
                break;
            case 'week';
                $stmt = $pdo->prepare($sql3);
                break;
        }

        $stmt->execute();

        $result = $stmt->fetchall();

//        var_dump($result);
//        die;

        $view_senddata_arr = VIEW_ARRAY;
        $view_senddata_arr['navbar_username'] = $session_variables['username'];
        $view_senddata_arr['nav_button_login'] = $session_variables['loginout'];
        $view_senddata_arr['page_heading_1'] = 'Device Telemetry Transmission';

        if (empty($result)) {

            $view_senddata_arr['page_text'] = 'There were no relevant telemetry messages for this period.';
        }
        else
            {


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


            foreach($result as $line)

            {
            //    echo " ";

                echo 	'<tr>
                        <td>'.$line['tele_device_id'].$line['tele_created_id'].'</td>
			 			<td style="width: 10%; text-align: center">'.$line['tele_switch1'].'</td> 
			 			<td style="width: 10%; text-align: center">'.$line['tele_switch2'].'</td> 
			 			<td style="width: 10%; text-align: center">'.$line['tele_switch3'].'</td>
						 <td style="width: 10%; text-align: center">'.$line['tele_switch4'].'</td> 
						 <td style="width: 10%; text-align: center">'.$line['tele_motor'].'</td> 
						 <td style="width: 10%; text-align: center">'.$line['tele_keypad'].'</td> 
						<td style="width: 10%; text-align: center">'.$line['tele_temperature'].'</td>
					</tr>'
                ;
            }


            echo ' </tbody>
</table>
</div>' ;

            $view_senddata_arr['page_text'] = 'All data read is below';
        }

        return $this->view->render($response, 'importdata.html.twig', $view_senddata_arr);





















    })->setName('processviewdata');