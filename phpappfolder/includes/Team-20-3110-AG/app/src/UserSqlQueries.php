<?php
/**
 * UserSqlQueries.php
 * Sql queries to manage user data in the tbl_user table
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 20/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

namespace Users;
/**
 * Class UserSqlQueries
 * @package Users
 */
class UserSqlQueries
{
    public function __construct() {}

    public function __destruct() {}

    /**
     * @return string
     */
    public static  function rebuildFreshUserTable()
    {
        $query_string = "USE p2430705db; ";
        $query_string .= "DROP TABLE IF EXISTS tbl_user; ";
        $query_string .= "CREATE TABLE IF NOT EXISTS tbl_user( ";
        $query_string .= "user_id int COLLATE utf8_unicode_ci NOT NULL AUTO_INCREMENT, ";
        $query_string .= "user_mobile varchar(100) COLLATE utf8_unicode_ci NOT NULL, ";
        $query_string .= "user_username varchar(100) COLLATE utf8_unicode_ci NOT NULL, ";
        $query_string .= "user_password varchar(100) COLLATE utf8_unicode_ci NOT NULL, ";
        $query_string .= "user_timestamp timestamp COLLATE utf8_unicode_ci NOT NULL DEFAULT ";
        $query_string .= "current_timestamp() ON UPDATE current_timestamp(), ";
        $query_string .= "PRIMARY KEY (user_id) );";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function getUserRowByIdQuery()
    {
        $query_string  = "SELECT tbl_user.user_mobile, tbl_user.user_username, ";
        $query_string .= "tbl_user.user_password ";
        $query_string .= "FROM tbl_user";
        $query_string .= "WHERE user_id = :user_id ";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function checkUsernameQuery()
    {
        $query_string  = "SELECT ";
        $query_string .= "tbl_user.user_username ";
        $query_string .= "FROM tbl_user ";
        $query_string .= "WHERE user_username = :user_username ";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function authenticateUserQuery()
    {
        $query_string  = "SELECT tbl_user.user_id, tbl_user.user_mobile, ";
        $query_string .= "tbl_user.user_username, tbl_user.user_password ";
        $query_string .= "FROM tbl_user ";
        $query_string .= "WHERE user_username = :user_username ";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function getAllUsersQuery()
    {
        $query_string  = "SELECT * ";
        $query_string .= "FROM tbl_user ";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function addUserQuery()
    {
        $query_string  = "INSERT INTO tbl_user ";
        $query_string .= "SET user_mobile = :user_mobile, ";
        $query_string .= "user_username = :user_username, ";
        $query_string .= "user_password = :user_password ";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function updateUserQuery()
    {
        $query_string  = "UPDATE tbl_user ";
        $query_string .= "SET user_mobile = :user_mobile ";
        $query_string .= "user_username = :user_username, ";
        $query_string .= "user_password = :user_password ";
        $query_string .= "WHERE user_id = :user_id ";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function deleteUserQuery()
    {
        $query_string  = "DELETE FROM tbl_user ";
        $query_string .= "WHERE user_id = :user_id ";
        return $query_string;
    }

    /**
     * @return string
     */
    public static function rebuildFreshSessionTableQuery()
    {
        $query_string = "USE p2430705db; ";
        $query_string .= "DROP TABLE IF EXISTS tbl_session; ";
        $query_string .= "CREATE TABLE tbl_session( ";
        $query_string .= "session_id varchar(100)  COLLATE utf8_unicode_ci NOT NULL, ";
        $query_string .= "session_variable varchar(100)  COLLATE utf8_unicode_ci NOT NULL,";
        $query_string .= "session_value varchar(100)  COLLATE utf8_unicode_ci NOT NULL, ";
        $query_string .= "session_timestamp timestamp  COLLATE utf8_unicode_ci NOT NULL DEFAULT ";
        $query_string .= "current_timestamp() ON UPDATE current_timestamp());";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function checkSessionVarQuery()
    {
        $query_string  = "SELECT session_variable ";
        $query_string .= "FROM tbl_session ";
        $query_string .= "WHERE session_id = :session_id ";
        $query_string .= "AND session_variable = :session_variable";
        $query_string .= "LIMIT 1";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function createSessionVarQuery()
    {
        $query_string  = "INSERT INTO tbl_session ";
        $query_string .= "SET session_id = :session_id, ";
        $query_string .= "session_variable = :session_variable, ";
        $query_string .= "session_value = :session_value ";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function updateSessionVarQuery()
    {
        $query_string  = "UPDATE tbl_session ";
        $query_string .= "SET session_value = :session_value ";
        $query_string .= "WHERE session_id = :session_id ";
        $query_string .= "AND session_variable = :session_variable";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function getSessionVarQuery()
    {
        $query_string  = "SELECT session_value ";
        $query_string .= "FROM tbl_session ";
        $query_string .= "WHERE session_id = :session_id ";
        $query_string .= "AND session_variable = :session_variable limit 1;";
        return $query_string;
    }
    /**
     * @return string
     */
    public static function deleteSessionVarsQuery()
    {
        $query_string  = "DELETE FROM tbl_session ";
        $query_string .= "WHERE session_id = :session_id ";
        return $query_string;
    }
}
