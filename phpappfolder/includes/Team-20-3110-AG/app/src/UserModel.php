<?php
/**
 * UserModel.php
 * Model for Users
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 20/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
namespace Users;
/**
 * Class UserModel
 * @package Users
 */
class UserModel
{
    private $user_database;
    private $sql_queries;
    private $database_connection_settings;
    private $universalLogger;
    private $user_id;
    private $mobile;
    private $username;
    private $password;

    private $add_result;
    private $update_result;
    private $delete_result;

    public function __construct()
    {
        $this->user_database = null;
        $this->sql_queries = null;
        $this->database_connection_settings = null;
        $this->universalLogger = null;

        $this->user_id = null;
        $this->mobile = null;
        $this->username = null;
        $this->password = null;

        $this->add_result = null;
        $this->update_result = null;
        $this->delete_result = null;
    }

    public function __destruct() { }

    public function setUserUserId($user_id)
    {
        $this->user_id = $user_id;
    }
    public function setUserMobile($mobile)
    {
        $this->mobile = $mobile;
    }
    public function setUserUsername($username)
    {
        $this->username = $username;
    }
    public function setUserPassword($password)
    {
        $this->password = $password;
    }

    public function setUserDatabase($user_database)
    {
        $this->user_database = $user_database;

    }
    public function setDatabaseConnectionSettings($database_connection_settings)
    {
        $this->database_connection_settings = $database_connection_settings;
    }
    public function setSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }
    public function setUniversalLogger($universalLogger)
    {
        $this->universalLogger = $universalLogger;
    }

    public function getAddResult()
    {
        return $this->add_result;
    }
    public function checkUsernameInDatabase()
    {
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $user = $this->user_database->checkUsername($this->username);
        return $user;
    }
    public function authenticateUserInDatabase()
    {
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $user = $this->user_database->authenticateUser($this->username);
        return $user;
    }

    public function getUserFromDatabase()
    {
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $user = $this->user_database->getUserRowById($this->user_id);
        return $user;
    }

    public function getAllUsersFromDatabase()
    {
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $all_user_array = $this->user_database->getAllUsers();
        return $all_user_array;
    }

    public function addUserToDatabase()
    {
        $add_result = false;
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $add_result =
            $this->user_database->addUser($this->mobile, $this->username, $this->password);
        $this->add_result = $add_result;
    }

    public function updateUserInDatabase()
    {
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $update_result =
            $this->user_database->updateUser($this->user_id,$this->mobile, $this->username, $this->password);

        if ($update_result !== false)
        {
            $update_result = true;
        }
        $this->update_result = $update_result;
    }

    public function deleteUserFromDatabase()
    {
        $delete_result = false;
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $delete_result = $this->user_database->deleteUser($this->user_id);
        if ($delete_result !== false)
        {
            $delete_result = true;
        }
        $this->delete_result = $delete_result;
    }
    public function deleteSession()
    {
        $delete_result = false;
        $this->user_database->setUserSqlQueries($this->sql_queries);
        $this->user_database->setUserDatabaseSettings($this->database_connection_settings);
        $this->user_database->setLogger($this->universalLogger);
        $this->user_database->makeUserDatabaseConnection();
        $delete_result = $this->user_database->deleteSessionVars();
        if ($delete_result !== false)
        {
            $delete_result = true;
        }
        $this->delete_result = $delete_result;
    }

    private function setUpDatabase()
    {

    }

}
