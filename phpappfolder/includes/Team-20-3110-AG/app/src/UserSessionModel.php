<?php
/**
 * UserSessionModel.php
 * Model for User Sessions
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 20/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

namespace Users;
/**
 * Class UserSessionModel
 * @package Users
 */
class UserSessionModel
{

    private $session_id;
    private $mobile;
    private $username;
    private $loginout;
    private $server_type;
    private $session_file_manager;
    private $session_database_manager;
    private $session_database_settings;
    private $sql_queries;
    private $storage_result;

    /**
     * UserSessionModel constructor.
     */
    public function __construct()
    {

        $this->user_id = null;
        $this->mobile = null;
        $this->username = null;
        $this->loginout = null;

        $this->server_type = null;

        $this->sql_queries = null;
        $this->session_database_manager = null;
        $this->session_database_settings = null;
        $this->session_file_manager = null;

        $this->storage_result = null;

    }

    public function __destruct() { }

    /**
     * @param $user_id
     */
    public function setSessionUserId($user_id)
    {
        $this->user_id= $user_id;
    }
    /**
     * @param $mobile
     */
    public function setSessionMobile($mobile)
    {
        $this->mobile= $mobile;
    }

    /**
     * @param $username
     */
    public function setSessionUsername($username)
    {
        //assign username passed in to private member variable
        $this->username = $username;
    }

    /**
     * @param $loginout
     */
    public function setSessionLoginout($loginout)
    {
        //assign username passed in to private member variable
        $this->loginout = $loginout;
    }

    /**
     * @param $session_database
     */
    public function setSessionDatabase($session_database)
    {
        $this->session_database_manager = $session_database;
    }

    /**
     * @param $session_database_settings
     */
    public function setDatabaseConnectionSettings($session_database_settings)
    {
        $this->session_database_settings = $session_database_settings;
    }
    /**
     * @param $session_sql_queries
     */
    public function setUserSqlQueries($session_sql_queries)
    {
        $this->sql_queries = $session_sql_queries;
    }
    /**
     * @param $session_key
     * @return mixed
     */
    //get the username from the database
    public function getSessionVarFromDatabase($session_key)
    {
        $this->session_database_manager->setUserSqlQueries($this->sql_queries);
        $this->session_database_manager->setUserDatabaseSettings($this->session_database_settings);
        $this->session_database_manager->makeUserDatabaseConnection();
        $row = $this->session_database_manager->getSessionDataFromDB($session_key);
        return $row;

    }

    /**
     * @return bool
     */
    public function storeSessionData()
    {

        $store_result = false;

        $this->session_database_manager->setUserSqlQueries($this->sql_queries);
        $this->session_database_manager->setUserDatabaseSettings($this->session_database_settings);
        $this->session_database_manager->makeUserDatabaseConnection();

        $store_result_id= $this->session_database_manager->setSessionVar('id', $this->user_id);

        $store_result_mobile = $this->session_database_manager->setSessionVar('mobile', $this->mobile);
        $store_result_username = $this->session_database_manager->setSessionVar('username', $this->username);
        $store_result_loginout = $this->session_database_manager->setSessionVar('loginout', $this->loginout);

        if ($store_result_id !== false
            && $store_result_mobile !== false
            && $store_result_username !== false
            && $store_result_loginout !== false)
        {
            $store_result = true;
        }
        return $store_result;
    }

    /**
     * @return null
     */
    public function getStorageResult()
    {
        return $this->storage_result;
    }


}
