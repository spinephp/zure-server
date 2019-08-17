<?php
namespace woo\base;
require_once("base/Registry.php");

class SessionRegistry extends Registry{
	private static $instance;

	private function __construct(){
	    @session_start();
	}

	static function instance(){
		if(!isset(self::$instance)){self::$instance = new self();}
		return self::$instance;
	}

	public function get($key){
		if(isset($_SESSION[$key])){
		    return $_SESSION[$key];
		}
		return null;
	}

	public function set($key,$val){
		$_SESSION[$key] = $val;
	}

	public function delete($key){
		unset($_SESSION[$key]);
	}

	public function destroy(){
	    session_destroy();
	}
    // function setComplex(Complex $complex){
		// self::instance()->set('complex',$complex);
	// }

    // function getComplex(){
		// return self::instance()->get('complex');
	// }
}

class Rsa
{
    public $privateKey = '';

    public $publicKey = '';
    
    public function __construct()
    {
        $resource = openssl_pkey_new();
        openssl_pkey_export($resource, $this->privateKey);
        $detail = openssl_pkey_get_details($resource);
        $this->publicKey = $detail['key'];
    }

    public function publicEncrypt($data, $publicKey)
    {
        openssl_public_encrypt($data, $encrypted, $publicKey);
        return $encrypted;
    }

    public function publicDecrypt($data, $publicKey)
    {
        openssl_public_decrypt($data, $decrypted, $publicKey);
        return $decrypted;
    }

    public function privateEncrypt($data, $privateKey)
    {
        openssl_private_encrypt($data, $encrypted, $privateKey);
        return $encrypted;
    }

    public function privateDecrypt($data, $privateKey)
    {
        openssl_private_decrypt($data, $decrypted, $privateKey);
        return $decrypted;
    }
}

?>