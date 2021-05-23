<?php
/**
 * processuserlogin.php
 * Route to process User Login
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 30/11/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/processuserlogin',
    function (Request $request, Response $response) use ($app)
    {
        // get the logger
        $universal_logger = $app->getContainer()->get('universalLogger');

        $tainted_login_details = $request->getParsedBody();

        $tainted_username= $tainted_login_details['username'];
        $tainted_password = $tainted_login_details['password'];
        $sanitised_username = filter_var($tainted_username, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $data_validator = $app->getContainer()->get('dataValidationObject');
        $return_to_page = false;
        $page_error ='';
        if(($sanitised_username === "") or ($tainted_password === "")){
            $page_error = 'Please ensure all fields are filled';
            $return_to_page =true;
        }
        $username_error ='';

        $cleaned_username = $data_validator->validateUsername($tainted_username);
        if($cleaned_username === false){
            $username_error = 'Username must be between 6 and 15 characters';
            $return_to_page =true;
        }

//        echo("<pre>");
//        var_dump($cleaned_username);
//        $encrypted_username=encrypt($app, $cleaned_username);
//        var_dump($encrypted_username);
//        die();
//        echo("<pre>");
        $password_error ='';
        $cleaned_password = $data_validator->validatePassword($tainted_password);
        if($cleaned_password === false){
            $password_error = 'Password must be between 8 and 15 characters';
            $return_to_page =true;
        }

        if($return_to_page)
        {
            return $this->view->render($response,
                'userlogin.html.twig',
                [
                    'bootstrap_css_path' => BOOTSTRAP_CSS,
                    'bootstrap_jq_path'=> BOOTSTRAP_JQ,
                    'bootstrap_js_path' => BOOTSTRAP_JS,
                    'page_name' => PAGE_PATH,
                    'navbar_username' => 'DJG',
                    'page_heading_1' => 'Please enter correct login details',
                    'page_footing' => 'Copyright(c) 2020 ',
                    'page_text' => 'This is the Select Login Page',
                    'username_error'=> $username_error,
                    'password_error'=> $password_error,
                ]
            );
        }
        $user_model = $app->getContainer()->get('userModelObject');
        $user_database = $app->getContainer()->get('userDatabaseObject');
        $user_sql_queries = $app->getContainer()->get('userSqlQueriesObject');
        $settings_array = $app->getContainer()->get('settings');
        $user_model->setSqlQueries($user_sql_queries);
        $user_model->setUserDatabase($user_database);
        $database_connection_settings = $settings_array['database_settings'];
        $user_model->setDatabaseConnectionSettings($database_connection_settings);
        $user_model->setUniversalLogger($universal_logger);
        $user_model->setUserUsername($cleaned_username);
        $user_model->setUserPassword($cleaned_password);

        // check user in database (return encryted data)
        $encrypted_username = usernameEncrypt($app, $cleaned_username);
        $user_model->setUserUsername($encrypted_username);
        $user_in_database = $user_model->authenticateUserInDatabase();

//        echo("<pre>");
//        var_dump($cleaned_username);
//        var_dump($encrypted_username);
//        var_dump($user_in_database);
//        die();

        $hash_manager = $app->getContainer()->get('hashManager');
        $authenticated_user = false;
        if(!empty($user_in_database['user_password']))
        {
            $authenticated_user =
                $hash_manager->checkHash($cleaned_username, $cleaned_password,$user_in_database['user_password']);
        }
        if(!$authenticated_user)
        {
            if(!empty($user_in_database['user_id']))
            {
                // Logging Event - incase some one tried logging in with anothers username
                $universal_logger->logEvent('User ' . $user_in_database['user_id'] .':   Failed Authentication and Login' );
            }
            else
                {
                    // Logging Event - incase some one tried logging in with random details
                    $universal_logger->logEvent('Anonymous User :   Failed Authentication and Login' );
            }

            // Status 401 Unauthorized
            $response = $response->withStatus(401);
            return $this->view->render($response,
                'error.html.twig',
                [
                    'bootstrap_css_path' => BOOTSTRAP_CSS,
                    'bootstrap_jq_path' => BOOTSTRAP_JQ,
                    'bootstrap_js_path' => BOOTSTRAP_JS,
                    'page_name' => PAGE_PATH,
                    'navbar_username' => PROJECT_LOGO,
                    'page_heading_1' => 'Status 401 Unauthorized',
                    'page_text' => 'Sorry you have been denied access. Please check your username and password',
                ]
            );
        }
        if($authenticated_user)
        {
            // Status 200 OK
            $response = $response->withStatus(200);

            // Logging Event
            $universal_logger->logEvent('User ' . $user_in_database['user_id'] . ':  Successfully Authenticated and Logged in' );

            // Get and decrypt the encrypted data from database
            $encrypted_username = $user_in_database['user_username'];
            $user_in_database['user_username'] = usernameDecrypt($app, $encrypted_username);
            $encrypted_mobile = $user_in_database['user_mobile'];
            // Base64 encoded
            $encoded_mobile = encode($app, $encrypted_mobile);
            // replace with decrypted data
            $user_in_database['user_mobile'] = decrypt($app, $encoded_mobile);

            // store encrypted data to session database
            $encryted_session_data['id'] = encrypt($app, $user_in_database['user_id']);
            $encryted_session_data['mobile'] = encrypt($app, $user_in_database['user_mobile']);
            $encryted_session_data['username'] = encrypt($app, $user_in_database['user_username']);
            $encryted_session_data['loginout'] = encrypt($app, 'Logout');

            $session_data_handler = $app->getContainer()->get('sessionDataHandler');

            $session_data_handler->storeSessionVariables($app, $encryted_session_data);
            $response = $response->withStatus(200);
            $view_homepage_arr = VIEW_ARRAY;
            $view_homepage_arr['image_path'] = USER_HOMEPAGE_JPG;
            $view_homepage_arr['navbar_username'] = $user_in_database['user_username'];
            $view_homepage_arr['page_mobile'] = $user_in_database['user_mobile'];
            $view_homepage_arr['nav_button_login'] = 'Logout';
            $view_homepage_arr['page_heading_1'] = 'Welcome';
            $view_homepage_arr['page_text'] = 'The Registered User Area';
            return $this->view->render($response, 'userhomepage.html.twig',$view_homepage_arr);
        }
    })->setName('processuserlogin');

// returns an array of $cleaned_parameters
function encode($app, $encrypted_string)
{
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');
    $encoded_string = $base64_wrapper->encode_base64($encrypted_string);
    return $encoded_string;
}
function decrypt($app, $encoded_string)
{
    $decrypted_values = [];
    $base64_wrapper = $app->getContainer()->get('base64Wrapper');
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
    $decrypted_string = $libsodium_wrapper->decrypt($base64_wrapper, $encoded_string);
    return $decrypted_string;
}
