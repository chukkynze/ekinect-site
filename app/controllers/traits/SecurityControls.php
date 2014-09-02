<?php
 /**
  * Trait SecurityControls
  *
  * filename:   SecurityControls.php
  * 
  * @author      Chukwuma J. Nze <chukkynze@ekinect.com>
  * @since       8/29/14 11:26 PM
  * 
  * @copyright   Copyright (c) 2014 www.eKinect.com
  */
 

trait SecurityControls
{

    protected function createHash($val,$key)
    {
        $hash   =   hash_hmac('sha512', $val, $key);
        return $hash;
    }

    /**
     *
     * Encrypt/Decrypt function
     * Note strings should already hashed, salted and md5ed or sha1ed before even thinking of using this
     *
     * @param           $mode 'e'|'d' ==> encrypt|decrypt
     * @param           $string_to_convert
     * @param           $key
     *
     * @return array|bool|string
     */
    public function twoWayCrypt($mode, $string_to_convert, $key)
    {
        $encryptionMethod   =   "AES-256-CBC";
        $raw_output         =   FALSE;

        if($mode === "e")
        {
            // Encrypt
            $iv             =   mcrypt_create_iv(mcrypt_get_iv_size(MCRYPT_CAST_256, MCRYPT_MODE_CBC), MCRYPT_RAND);
            return  $iv . self::POLICY_EncryptedURLDelimiter . openssl_encrypt($string_to_convert, $encryptionMethod, $key, $raw_output, $iv);
        }
        elseif($mode === "d")
        {
            // Decrypt
            $expld          =   explode(self::POLICY_EncryptedURLDelimiter, $string_to_convert);
            return  openssl_decrypt($expld[1], $encryptionMethod, $key, $raw_output, $expld[0]);
        }
        else
        {
            return FALSE;
        }
    }

}