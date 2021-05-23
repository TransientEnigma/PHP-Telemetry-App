<?php
/**
 * UserDatabase.php
 * Database class that uses injected database
 * settings and queries to handle data management
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 20/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
namespace Users;
/**
 * Class UserDatabase
 * @package Users
 */
// class that has a function you can use to set the database connection settings
class UserDatabase implements UserInterface
{
    private $database_connection_settings;
    private $sql_queries;

    private $db_handle;
    private $sql_statement;
    private $prepared_statement;
    private $errors;

    private $num_rows;
    private $record_set;
    private $execute_result;

    private $universalLogger;

    /**
     * UserDatabase constructor.
     */
    public function __construct()
    {
        $this->database_connection_settings = null;
        $this->sql_queries = null;

        $this->db_handle = null;
        $this->sql_statement=null;
        $this->prepared_statement = null;
        $this->errors = [];

        $this->num_rows = null;
        $this->record_set = null;
        $this->execute_result = null;

        $this->universalLogger = null;
    }

    public function __destruct() { }

    /**
     * @param $connection_settings
     */
    public function setUserDatabaseSettings($connection_settings)
    {
        $this->database_connection_settings = $connection_settings;
    }

    /**
     * @param $sql_queries
     */
    public function setUserSqlQueries($sql_queries)
    {
        $this->sql_queries = $sql_queries;
    }
    public function setLogger($universalLogger)
    {
        $this->universalLogger = $universalLogger;

    }

//    '\' character in front of the PDO class name signifies that it is a globally available class
//     and is not part of the Sessions namespace
    /**
     * @return string
     */
    public function makeUserDatabaseConnection()
    {
        $pdo_error = '';

        // declare a variable to store the database settings
        $database_settings = $this->database_connection_settings;

        $db_host = $database_settings['rdbms'] . ':host=' . $database_settings['host'];
        $db_port = ';port=' . $database_settings['port'];
        $db_name = ';dbname=' . $database_settings['database_name'];
        $host_details = $db_host . $db_port . $db_name;
        $db_username = $database_settings['username'];
        $db_password = $database_settings['password'];
        $pdo_attributes = $database_settings['options'];

        try
        {
            // create a PDO object to connect to the database
            $pdo_handle = new \PDO($host_details, $db_username, $db_password , $pdo_attributes);
            $this->db_handle = $pdo_handle;
        }
        catch (\PDOException $exception_object)
        {
            $pdo_error = 'Exception Caught: in UserDatabase.php "makeUserDatabaseConnection" ';
            $pdo_error .= 'failed to connect to database';
            // Logging Error
            $this->universalLogger->logError($pdo_error);
        }

        return $pdo_error;
    }

    /**
     * @param $username
     * @return mixed
     */
    public function checkUsername($username)
    {
        //echo 'authenticateUser() Method';

        $query_string = $this->sql_queries->checkUsernameQuery();

        $query_parameters = [
            ':user_username' => $username,
        ];

        $user = $this->fetchArray($query_string, $query_parameters);
        return $user;
    }

    /**
     * @param $username
     * @return mixed
     */
    public function authenticateUser($username)
    {

        $query_string = $this->sql_queries->authenticateUserQuery();

        $query_parameters = [
            ':user_username' => $username,

        ];

        $user = $this->fetchArray($query_string, $query_parameters);
        return $user;
    }

    /**
     * @param $user_id
     * @return mixed
     */
    public function getUserRowById($user_id)
    {
        echo 'The getUserRow() Method';

        $query_string = $this->sql_queries->getUserRowByIdQuery();


        $query_user_id_parameter = [
            ':user_id' => $user_id
        ];

        $user_array = $this->fetchArray($query_string, $query_user_id_parameter);

        return $user_array;
    }

    /**
     * @return mixed
     */

    public function getAllUsers(){
        $this->errors['db_error'] = false;
        try
        {
            $query_string = $this->sql_queries->getAllUsersQuery();
            $this->sql_statement = $this->db_handle->query($query_string);
            $users = $this->sql_statement->fetchAll(\PDO::FETCH_ASSOC);
            $this->sql_statement->closeCursor();

            //var_dump($users);

            return $users;
        }
        catch (\PDOException $fetch_exception)
        {
            $error_message  = 'Exception Caught: in UserDatabase.php "getAllUsers" method encountered a problem. ';
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . $this->sql_statement->errorInfo() ."\n";
            $this->errors['db_error'] = true;
            // Logging Error
            $this->universalLogger->logError($error_message);
        }
        return $this->errors['db_error'];
    }

    /**
     * @param $query_string
     * @param null $params
     * @return mixed
     */
    //Fetches an array after it creates the prepared statement and executes the query with parameters
    public function fetchArray($query_string, $params = null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;
        try
        {
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            //Use for error log test:
            //$this->prepared_statement = $this->db_handle->prepare($query_string.'some crap');
            $this->prepared_statement->execute($query_parameters);
            $records = $this->prepared_statement->fetch(\PDO::FETCH_ASSOC);
            $this->prepared_statement->closeCursor();
            return $records;
        }
        catch (\PDOException $fetch_exception)
        {
            $error_message  = 'Exception Caught: in UserDatabase.php "fetchArray" method encountered a problem. ';
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . $this->prepared_statement->errorInfo() . "\n";
            $this->errors['db_error'] = true;
            // Logging Event
            $this->universalLogger->logError($error_message);
        }
        return $this->errors['db_error'];
    }

    /**
     * @param $mobile
     * @param $username
     * @param $password
     * @return mixed
     */
    public function addUser($mobile, $username, $password){
        {
            $query_string = $this->sql_queries->addUserQuery();
            $query_parameters = [
                ':user_mobile' => $mobile,
                ':user_username' => $username,
                ':user_password' => $password
            ];
            $add_result = $this->executeQuery($query_string, $query_parameters);
            return $add_result;
        }
    }

    /**
     * @param $userId
     * @param $mobile
     * @param $username
     * @param $password
     */
    public function updateUser($userId, $mobile, $username, $password){
        echo 'The updateUser() Method';
        {
            $query_string = $this->sql_queries->updateUserQuery();
            $query_parameters = [
                ':user_id'=> $userId,
                ':user_mobile' => $mobile,
                ':user_username' => $username,
                ':user_password' => $password
            ];
            $this->executeQuery($query_string, $query_parameters);
        }
    }

    /**
     * @param $userId
     */
    public function deleteUser($userId){
        echo 'The deleteUser() Method';
        $query_parameter = [
            ':user_id'=> $userId
        ];
        {
            $query_string = $this->sql_queries->deleteUserQuery($query_parameter);
            $this->executeQuery($query_string, $query_parameter);
        }
    }
    /**
     * @param $query_string
     * @param null $params
     * @return mixed
     */
    //Does not fetch array, executes a query after creating prepared statement and with parameters
    private function executeQuery($query_string, $params = null)
    {
        $this->errors['db_error'] = false;
        $query_parameters = $params;

        try
        {
            $this->prepared_statement = $this->db_handle->prepare($query_string);
            $execute_result = $this->prepared_statement->execute($query_parameters);
            $this->num_rows = $this->prepared_statement->rowCount();
            $this->errors['execute-OK'] = $execute_result;
        }
        catch (\PDOException $exception_object)
        {
            $error_message  = 'Exception Caught: in UserDatabase.php "executeQuery" method encountered a problem. ';
            $error_message .= 'SQL query: ' . $query_string . "\n";
            $error_message .= 'Error: ' . $this->prepared_statement->errorInfo() . "\n";
            $this->errors['db_error'] = true;
            // Logging Event
            $this->universalLogger->logError($error_message);
        }
        return $this->errors['db_error'];
    }




    /**
     * @param $session_key
     */
    public function unsetSessionVar($session_key){}

    /**
     * @param $session_key
     * @param $session_value
     * @return array
     */
    public function setSessionVar($session_key, $session_value)
    {
        if ($this->getSessionVar($session_key) === true)
        {
            //it stores the session variable under same session_id
            $this->updateSessionVar($session_key, $session_value);
        }
        else
        {
            //it cant find same session_id so creates a new entry
            $this->createSessionVar($session_key, $session_value);
        }

        return($this->errors);
    }

    /**
     * @param $session_key
     * @return bool
     */
    public function getSessionVar($session_key)
    {


        $session_var_exists = false;
        $query_string = $this->sql_queries->getSessionVarQuery();

        $query_parameters = [
            ':session_id' => session_id(),
            ':session_variable' => $session_key
        ];

        $this->executeQuery($query_string, $query_parameters);

        if ($this->countRows() > 0)
        {
            $session_var_exists = true;
        }
        return $session_var_exists;
    }

    /**
     * @param $session_key
     * @return mixed
     */
    public function getSessionDataFromDB($session_key)
    {

        $session_var_exists = false;
        $query_string = $this->sql_queries->getSessionVarQuery();

        $query_parameters = [
            ':session_id' => session_id(),
            ':session_variable' => $session_key
        ];

        return $this->fetchArray($query_string, $query_parameters);

    }

    /**
     * @param $session_key
     * @param $session_value
     */
    private function createSessionVar($session_key, $session_value)
    {
        $query_string = $this->sql_queries->createSessionVarQuery();

        $query_parameters = [
            ':session_id' => session_id(),
            ':session_variable' => $session_key,
            ':session_value' => $session_value
        ];

        $this->executeQuery($query_string, $query_parameters);
    }

    /**
     * @param $session_key
     * @param $session_value
     */
    private function updateSessionVar($session_key, $session_value)
    {
        $query_string = $this->sql_queries->updateSessionVarQuery();

        $query_parameters = [
            ':session_id' => session_id(),
            ':session_variable' => $session_key,
            ':session_value' => $session_value
        ];

        $this->executeQuery($query_string, $query_parameters);
    }


    public function deleteSessionVars(){
        $query_parameter = [
            ':session_id' => session_id(),
        ];
        {
            $query_string = $this->sql_queries->deleteSessionVarsQuery();
            $this->executeQuery($query_string, $query_parameter);
        }
    }

    /**
     * @return int
     */
    public function countRows()
    {

        return $this->num_rows;
    }

}
