<?php
/**
 * processregistration.php
 * Route to that Process Registration and
 * Conditionally redirects to views depending
 * on state of posted data
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 23/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

use \Psr\Http\Message\ServerRequestInterface as Request;
use \Psr\Http\Message\ResponseInterface as Response;

$app->post('/processregistration',
    function (Request $request, Response $response) use ($app){
        $universal_logger = $app->getContainer()->get('universalLogger');
        $tainted_registration_details = $request->getParsedBody();
        $tainted_mobile = $tainted_registration_details['mobile'];
        $tainted_username= $tainted_registration_details['username'];
        $tainted_password = $tainted_registration_details['password'];
        $sanitised_mobile =
            filter_var($tainted_mobile, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
        $sanitised_username =
            filter_var($tainted_username, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);

        $data_validator = $app->getContainer()->get('dataValidationObject');
        $return_to_page = false;
        $page_error ='';
        $add_error = false;
        if(( $sanitised_mobile === "") or ($sanitised_username === "") or ($tainted_password === "")){
            $page_error = 'Please ensure all fields are filled';
            $return_to_page =true;
        }
        $mobile_error ='';
        $cleaned_mobile = $data_validator->validateMobile($tainted_mobile);
        if($cleaned_mobile === false)
        {
            $mobile_error = 'Mobile number must be between 8 and 15 digits';
            $return_to_page =true;
        }
        $username_error ='';
        $cleaned_username = $data_validator->validateUsername($tainted_username);
        if($cleaned_username === false)
        {
            $username_error = 'Username must be between 6 and 15 characters';
            $return_to_page =true;
        }
        $password_error ='';
        $cleaned_password = $data_validator->validatePassword($tainted_password);
        if($cleaned_password === false)
        {
            $password_error = 'Password must be between 8 and 15 characters. ';
            $password_error .=  'The following characters are not allowed: \' < > ^ ? : ; , ( ) * { }  ';
            $return_to_page =true;
        }




        if($return_to_page){
            //Status 400 Bad Request
            $response = $response->withStatus(400);
            return $this->view->render($response,
                'registrationform.html.twig',
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
                    'page_text' => 'Hello and welcome to the Telemetary App By Team-20-3110-AG',
                    'page_error' => $page_error,
                    'mobile_error' => $mobile_error,
                    'username_error' => $username_error,
                    'password_error' => $password_error,
                ]
            );
        }

        $user_model = $app->getContainer()->get('userModelObject');
        $user_database = $app->getContainer()->get('userDatabaseObject');
        $user_sql_queries = $app->getContainer()->get('userSqlQueriesObject');
        $user_model->setSqlQueries($user_sql_queries);
        $user_model->setUserDatabase($user_database);
        $settings_array = $app->getContainer()->get('settings');
        $database_connection_settings = $settings_array['database_settings'];
        $user_model->setDatabaseConnectionSettings($database_connection_settings);
        $user_model->setUniversalLogger($universal_logger);

        // encrypt the data
        $encrypted_mobile = encrypt($app, $cleaned_mobile);
        $user_model->setUserMobile($encrypted_mobile);


        $encrypted_username = usernameEncrypt($app, $cleaned_username);
        $user_model->setUserUsername($encrypted_username);

        $username_in_database = $user_model->checkUsernameInDatabase();
        // if username is not taken
        if(empty($username_in_database['user_username'])) {
            $hash_manager = $app->getContainer()->get('hashManager');
            $hashed_password = $hash_manager->createHash($cleaned_username, $cleaned_password);
            $user_model->setUserPassword($hashed_password);
            $user_model->addUserToDatabase();
            $add_error = $user_model->getAddResult();
        }
        //if username is taken
        if(!empty($username_in_database['user_username']))
        {
            // Status 406 Not Acceptable
            $response = $response->withStatus(406);
            return $this->view->render($response,
                'registrationform.html.twig',
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
                    'page_heading_1' => 'Status 406 Not Acceptable',
                    'page_error' => 'Sorry we could not register you. Please select a different username',
                ]
            );
        }

        if($add_error === false)
        {
            //Status 201 Created
            $response = $response->withStatus(201);
            $universal_logger->logEvent('New User:  Registered Successfully');
            return $this->view->render($response,
                'selectlogin.html.twig',
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
                    'page_heading_1' => 'Thank you for resistering',
                    'page_text' => 'Please Select a Login',
                ]
            );
        }
        if($add_error !== false) {
            // Status 500 Internal Server Error
            $response = $response->withStatus(500);
            $universal_logger->logError('Status 500' . ':  Internal Server Error');
            return $this->view->render($response,
                'error.html.twig',
                [
                    'bootstrap_css_path' => BOOTSTRAP_CSS,
                    'bootstrap_jq_path' => BOOTSTRAP_JQ,
                    'bootstrap_js_path' => BOOTSTRAP_JS,
                    'page_name' => PAGE_PATH,
                    'navbar_username' => 'DJG',
                    'page_heading_1' => 'Status 500 Internal Server Error',
                    'page_footing' => 'Copyright(c) 2020 ',
                    'page_text' => 'Sorry we could not register you. Please try again later',
                ]
            );
        }

})->setName('processregistration');


function encrypt($app, $string_to_encrypt)
{
    $libsodium_wrapper = $app->getContainer()->get('libSodiumWrapper');
    $encrypted_string = $libsodium_wrapper->encrypt($string_to_encrypt);
    return $encrypted_string;
}
function usernameEncrypt($app, $username_to_encrypt)
{
    $userEncrypt = $app->getContainer()->get('userEncrypt');
    $encrypted_username = $userEncrypt->userEncryption($username_to_encrypt);
    return $encrypted_username;
}
function usernameDecrypt($app, $username_to_dencrypt)
{
    $userDencrypt = $app->getContainer()->get('userEncrypt');
    $decrypted_username = $userDencrypt->userDecryption($username_to_dencrypt);
    return $decrypted_username;
}