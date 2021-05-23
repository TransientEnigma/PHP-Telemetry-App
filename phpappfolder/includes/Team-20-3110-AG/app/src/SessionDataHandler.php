<?php
/**
 * SessionDataHandler.php
 * Session variable storage and retrieval
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 26/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
namespace Users;
/**
 * Class SessionDataHandler
 * @package Users
 */
class SessionDataHandler{
    /**
     * SessionDataHandler constructor.
     */
    public function __construct(){}

    public function __destruct(){}

    /**
     * @param $app
     * @param $session_variables
     * @return mixed
     */
    public function storeSessionVariables($app, $session_variables)
    {

        session_regenerate_id();

        $store_result = '';

        $session_model_object = $app->getContainer()->get('sessionModelObject');
        $session_model_object->setSessionUserId($session_variables['id']);
        $session_model_object->setSessionMobile($session_variables['mobile']);
        $session_model_object->setSessionUsername($session_variables['username']);
        $session_model_object->setSessionLoginout($session_variables['loginout']);
        $user_sql_queries = $app->getContainer()->get('userSqlQueriesObject');
        $session_database_object = $app->getContainer()->get('userDatabaseObject');
        $sessions_db_settings = $app->getContainer()->get('settings');
        $session_database_settings = $sessions_db_settings['database_settings'];
        $session_model_object->setDatabaseConnectionSettings($session_database_settings);
        $session_model_object->setUserSqlQueries($user_sql_queries);
        $session_model_object->setSessionDatabase($session_database_object);
        $session_model_object->storeSessionData();
        $store_result = $session_model_object->getStorageResult();
        return $store_result;
    }

    /**
     * @param $app
     * @return array
     */
    public function retrieveFromSessionDb($app)
    {
        $session_variables=[];
        $session_model_object = $app->getContainer()->get('sessionModelObject');
        $user_sql_queries = $app->getContainer()->get('userSqlQueriesObject');
        $session_database_object = $app->getContainer()->get('userDatabaseObject');
        $sessions_db_settings = $app->getContainer()->get('settings');
        $session_database_settings = $sessions_db_settings['database_settings'];
        $session_model_object->setDatabaseConnectionSettings($session_database_settings);
        $session_model_object->setUserSqlQueries($user_sql_queries);
        $session_model_object->setSessionDatabase($session_database_object);
        $user_id_key_value = $session_model_object->getSessionVarFromDatabase('id');
        $mobile_key_value = $session_model_object->getSessionVarFromDatabase('mobile');
        $username_key_value = $session_model_object->getSessionVarFromDatabase('username');
        $loginout_key_value = $session_model_object->getSessionVarFromDatabase('loginout');
        if(!empty($user_id_key_value))
        {
            $session_variables['user_id'] = $user_id_key_value['session_value'];
        }
        if(!empty($mobile_key_value))
        {
            $session_variables['mobile'] = $mobile_key_value['session_value'];
        }
        if(!empty($username_key_value))
        {
            $session_variables['username'] = $username_key_value['session_value'];
        }
        if(!empty($username_key_value)){
            $session_variables['loginout'] = $loginout_key_value['session_value'];
        }
        if(empty($session_variables['mobile']))
        {
            $session_variables['mobile'] = 'DJG';
        }
        if(empty($session_variables['username']))
        {
            $session_variables['username'] = 'DJG';
        }
        if(empty($session_variables['loginout']))
        {
            $session_variables['loginout'] = 'Login';
        }
        return $session_variables;
    }
}
