<?php
/**
 * UserInterface.php
 * Interface for the Database
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 20/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
namespace Users;
/**
 * Interface UserInterface
 * @package Users
 */
interface UserInterface
{
    public function makeUserDatabaseConnection();
    public function getUserRowById($userId);
    public function getAllUsers();
    public function addUser($mobile, $username, $password);
    public function updateUser($userId, $mobile, $username, $password);
    public function deleteUser($userId);
    public function authenticateUser($username);
    public function setSessionVar($session_key, $session_value_to_set);
    public function getSessionVar($session_key);
    public function unsetSessionVar($session_key);
    public function setLogger($universalLogger);
    public function deleteSessionVars();
}