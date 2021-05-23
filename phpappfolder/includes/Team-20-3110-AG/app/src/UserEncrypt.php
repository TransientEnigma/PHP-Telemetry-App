<?php
/**
 * UserEncrypt.php
 * Encrypts and decrypts user data using fixed nonce
 * Author: J Masiano
 * Email: P2430705@my365.dmu.ac.uk
 * Date: 18/05/2021
 *
 * @author J Masiano <P2430705@my365.dmu.ac.uk>
 * @copyright DGJ Project Group - 20-3110-AG
 */
namespace Users;
class userEncrypt
{
    //32 characters
    private $fixedKey = 'QH4oQ6zE7DC3QlFmKCrPtT8dlB24nOaz';
    //24 characters
    private $fixedNonce = 'x5CcY2bsQ0TEDzMuzTcERJyN';

    public function userEncryption($userData)
    {
        $encryptedUserData = base64_encode(
            sodium_crypto_secretbox(
                $userData,
                $this->fixedNonce,
                $this->fixedKey
            )
        );
        return $encryptedUserData;
    }

    public function userDecryption($encryptedUserData)
    {
        // decode the base64 on $encryptedUserData first
        $decodedUserData = base64_decode($encryptedUserData);
        $decrypted = sodium_crypto_secretbox_open($decodedUserData,$this->fixedNonce,$this->fixedKey);
        return $decrypted;
    }
}