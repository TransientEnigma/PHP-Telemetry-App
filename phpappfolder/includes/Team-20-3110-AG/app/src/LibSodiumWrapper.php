<?php
/**
 * Wrapper class for the PHP LibSodium library.  Takes the pain out of using the library.
 *
 * Encrypt/decrypt the given string
 * Encrypt/Decrypt the given string with base 64 encoding
 *
 * @author CF Ingrams <cfi@dmu.ac.uk>
 * @copyright De Montfort University
 *
 */

namespace Telemetry;

use RangeException;

class LibSodiumWrapper
{
    private $key;

    public function __construct()
    {
        $this->initialiseEncryption();
    }

    public function __destruct()
    {
        sodium_memzero($this->key);
    }

    private function initialiseEncryption()
    {
        $this->key = 'yjkau0DKae7YqSXuXNph156gyX4ZdYUX';
        $length = mb_strlen($this->key, 'UTF-8');
        if($length !== SODIUM_CRYPTO_SECRETBOX_KEYBYTES) {
            throw new RangeException('Key is not the correct size (must be 32 bytes) = '.$length);
        }
    }

    /**
     * Return an array containing individual values for each of the actual encrypted string and the nonce used to
     * perform the encryption
     * Need to append the two together for the decryption to work
     *
     * @param $string_to_encrypt
     * @return array
     * @throws \Exception
     */
    public function encrypt($string_to_encrypt): string
    {
        $nonce = random_bytes(SODIUM_CRYPTO_SECRETBOX_NONCEBYTES);

        $encrypted_string = sodium_crypto_secretbox($string_to_encrypt, $nonce, $this->key);

        $encryption_string = $nonce . $encrypted_string;

        sodium_memzero($string_to_encrypt);
        return $encryption_string;
    }

    public function decrypt($base64_wrapper, $string_to_decrypt)
    {
        $decrypted_string = '';
        $decoded = $base64_wrapper->decode_base64($string_to_decrypt);

        if ($decoded === false)
        {
            throw new \Exception('Ooops, the encoding failed');
        }
        
        if (mb_strlen($decoded, '8bit') < (SODIUM_CRYPTO_SECRETBOX_NONCEBYTES + SODIUM_CRYPTO_SECRETBOX_MACBYTES))
        {
            throw new \Exception('Oops, the message was truncated');
        }

        $nonce = mb_substr($decoded, 0, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, '8bit');

        $ciphertext = mb_substr($decoded, SODIUM_CRYPTO_SECRETBOX_NONCEBYTES, null, '8bit');

        $decrypted_string = sodium_crypto_secretbox_open(
            $ciphertext,
            $nonce,
            $this->key
        );

        if ($decrypted_string === false)
        {
            throw new \Exception('the message was tampered with in transit');
        }

        sodium_memzero($ciphertext);

        return $decrypted_string;
    }



}
