<?php
/**
 * DataValidator.php
 * For validating user data
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 22/12/2020
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */

namespace Users;
/**
 * Class DataValidator
 * @package Users
 */
class DataValidator
{
    /**
     * DataValidator constructor.
     */
    public function __construct() { }
    public function __destruct() { }
    /**
     * @param $tainted_mobile
     * @return bool
     */
    public function validateMobile($tainted_mobile)
    {
        $validated_mobile = false;

        $minimum_length = 8;
        $maximum_length = 15;

        if (!empty($tainted_mobile))
        {
            $mobile_length = strlen($tainted_mobile);
            if (($mobile_length < $minimum_length) or ($mobile_length > $maximum_length)) {
                return false;
            }
            if($this->notNumbers($tainted_mobile)){
                return false;
            }
            else {
                return $tainted_mobile;
            }
        }
        return $validated_mobile;
    }
    /**
     * @param $tainted_username
     * @return bool
     */
    public function validateUsername($tainted_username)
    {

        $validated_username = false;

        $minimum_length = 6;
        $maximum_length = 15;

        if (!empty($tainted_username)) {
            $username_length = strlen($tainted_username);
            if (($username_length < $minimum_length) or ($username_length > $maximum_length)) {
                return false;
            } else {
                return $tainted_username;
            }
        }
        return $validated_username;
    }
    /**
     * @param $tainted_password
     * @return bool
     */
    public function validatePassword($tainted_password)
    {
        $minimum_length = 8;
        $maximum_length = 15;
        $validated_password = false;
        $illegal_characters = " ' < > ^ ? : ; , ( ) * { } ";
        $tainted_password_length = strlen($tainted_password);
        if (( $tainted_password_length < $minimum_length) or ($tainted_password_length > $maximum_length)){
            return false;
        }
        // split into a character array
        $tainted_password_array = str_split($tainted_password, 1);
        // traverse the array of characters
        foreach ($tainted_password_array as $pw_character) {
            //check there are no illegal characters
            if (stristr($illegal_characters, $pw_character) === false) {
                return $tainted_password;
            }
            else{
                return false;
            }
        }
    }
    /**
     * @param $mobile_num
     * @return bool
     */
    function notNumbers($mobile_num)
    {
        // split into a character array
        $mobile_num_arr = str_split($mobile_num, 1);
        // traverse the array of characters
        foreach ($mobile_num_arr as $digit) {
            //checks for a none number
            if (!((strtoupper($digit) >= "0") and (strtoupper($digit) <= "9"))) {
                return true;

            }
        }
        return false;
    }
}