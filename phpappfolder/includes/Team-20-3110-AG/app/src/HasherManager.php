<?php
/**
 * HasherManager.php
 * Hashes and Checks Login with hashed passwords
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 27/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
namespace Users;
/**
 * Class HasherManager
 * @package Users
 */
class HasherManager
{
    /**
     * HasherManager constructor.
     */
    public function __construct(){}

    public function __destruct(){}

    /**
     * @param $username
     * @param $password
     * @return false|string|null
     */
    public function createHash($username, $password)
    {
        $superhashed_password = 'false';

        if (!empty($username) and !empty($password))
        {
            $options = array('cost' => BCRYPT_COST); //cost is how long it takes to compute the hash
            $salt = BCRYPT_SALT;
            $password_string = $username.$password.$salt;
            $superhashed_password = password_hash($password_string, BCRYPT_ALGO, $options);
        }
        return $superhashed_password;
    }

    /**
     * @param $username_from_user
     * @param $password_from_user
     * @param $stored_hash_password
     * @return bool
     */
    public function checkHash($username_from_user, $password_from_user, $stored_hash_password)
    {
        $salt = BCRYPT_SALT;
        $password_string = $username_from_user.$password_from_user.$salt;

        $user_authenticated = false;
         if (!empty($password_from_user) && !empty($stored_hash_password))
        {
            if (password_verify($password_string, $stored_hash_password))
            {
                $user_authenticated = true;
            }
        }
        return $user_authenticated;
    }
}