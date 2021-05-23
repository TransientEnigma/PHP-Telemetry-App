<?php
/**
 * Settings.php
 * Settings and Constants for Global Use
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 29/11/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

ini_set('display_errors', 'On');
ini_set('html_errors', 'On');
ini_set('xdebug.trace_output_name', 'Team-20-3110-AG.%t');
ini_set('xdebug.trace_format', '1');

define('SLASH', DIRECTORY_SEPARATOR);
$logs_folder_path = dirname(dirname(dirname(dirname(__FILE__)))).SLASH.'logs'.SLASH;
define('EVENTS_LOG_FILE', $logs_folder_path.'events.log');
define('WARNINGS_LOG_FILE', $logs_folder_path.'warning.log');
define('ERRORS_LOG_FILE', $logs_folder_path.'errors.log');
define('CRITICAL_LOG_FILE', $logs_folder_path.'critical.log');
//var_dump(EVENTS_LOG_FILE);
//die();



$app_url = dirname($_SERVER['SCRIPT_NAME']);
$bootstrap_css_path = $app_url . '/css/bootstrap.css';
define('BOOTSTRAP_CSS', $bootstrap_css_path);

$keypad_css_path = $app_url . '/css/keypad.css';
define('KEYPAD_CSS', $keypad_css_path);

$images_css_path = $app_url . '/css/images.css';
define('IMAGES_CSS', $images_css_path);

$bootstrap_js_path = $app_url . '/javascript/bootstrapjavascript.js';
define('BOOTSTRAP_JS', $bootstrap_js_path);


$bootstrap_jquery_path = $app_url . '/javascript/bootstrapjquery.js';
define('BOOTSTRAP_JQ', $bootstrap_jquery_path);


$custom_jquery_path = $app_url . '/javascript/customjquery.js';
define('CUSTOM_JQ', $custom_jquery_path);

$telemetry_js_path = $app_url . '/javascript/telemetry.js';
define('TELEMETRY_JS', $telemetry_js_path);

//free stock photo from pexels.com
$homepage_image_path = $app_url . '/images/pexels-fauxels-3183197.jpg';
define('HOMEPAGE_JPG', $homepage_image_path);

//free stock photo from pexels.com
$admin_homepage_image_path = $app_url . '/images/pexels-pixabay-38568.jpg';
define('ADMIN_HOMEPAGE_JPG', $admin_homepage_image_path);

//free stock photo from pexels.com
$user_homepage_image_path = $app_url . '/images/pexels-ylanite-koppens-796602.jpg';
define('USER_HOMEPAGE_JPG', $user_homepage_image_path);

define('PROJECT_LOGO', 'DJG');
define('PAGE_PATH', $_SERVER['SCRIPT_NAME']);

$ee_wsdl = 'https://m2mconnect.ee.co.uk/orange-soap/services/MessageServiceByCountry?wsdl';
define ('EEWSDL', $ee_wsdl);

define ('BCRYPT_ALGO', PASSWORD_DEFAULT);
define ('BCRYPT_COST', 10);
define ('BCRYPT_SALT', 'akldfjdklfidjfior');

$view_unlogged_array =
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
        'page_heading_1' => 'Telemetary App By Team-20-3110-AG',
        'page_text' => 'Please Register or Login',
    ];
define('VIEW_UNLOGGED_ARRAY', $view_unlogged_array);

$view_array = [
    'keypad_css_path' => KEYPAD_CSS,
    'bootstrap_css_path' => BOOTSTRAP_CSS,
    'images_css_path' => IMAGES_CSS,
    'bootstrap_jq_path'=> BOOTSTRAP_JQ,
    'bootstrap_js_path' => BOOTSTRAP_JS,
    'telemetry_js_path' =>TELEMETRY_JS,
    'homepage_image_path' => HOMEPAGE_JPG,
    'custom_jq_path'=> CUSTOM_JQ,
    'page_name' => PAGE_PATH,
    'navbar_username' => PROJECT_LOGO,
    'homepage' => 'homepage',
    'autosenddata' => 'autosenddata',
    'senddata' => 'senddata',
    'readdata' => 'readdata',
    'viewtelemdata' => 'viewtelemdata',
    'reportdata' => 'reportdata',
    'selectlogin' => 'selectlogin',
    'nav_button_homepage' => 'Homepage',
    'nav_button_register' => 'Register',
    'nav_button_auto' => 'Simulation',
    'nav_button_send' => 'Manual Entry',
    'nav_button_import' => 'Import Data',
    'nav_button_view' => 'View Data',
    'nav_button_report' => 'Reports',
    'nav_button_login' => 'Login',
];
define('VIEW_ARRAY', $view_array);

$soap_array = [
    'soap_username' => 'XXXXXXXXX',
    'soap_password' => 'XXXXXXXXXXXXXX',
    'soap_telephony_id' => '447817814149',// EE M2M
    'soap_key' => 'a59b393d0adf',
];

define('SOAP_ARRAY', $soap_array);


$settings = [
    "settings" => [
        'displayErrorDetails' => true,
        'addContentLengthHeader' => false,
        'mode' => 'development',
        'debug' => true,
        'view' => [
            'template_path' => __DIR__ . '/templates/',
            'twig' => [
                'cache' => false,
                'auto_reload' => true,
            ]],
        'database_settings' => [
            'rdbms' => 'mysql',
            'host' => 'localhost',
            'database_name' => 'p2430705db',
            'port' => '3306',
            'username' => 'root',
            'password' => '1234',
            'charset' => 'utf8',
            'collation' => 'utf8_unicode_ci',
            'options' => [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => true,
            ],
        ]
    ],
];

return $settings;
