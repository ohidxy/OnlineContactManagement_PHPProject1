<?php
class Token{
	public static function generateToken(){
		return $_SESSION['csrf_token'] = base64_encode(openssl_random_pseudo_bytes(32));
	}
	public static function checkToken($token){
		if(isset($_SESSION['csrf_token']) && $token == $_SESSION['csrf_token']){
			unset($_SESSION['csrf_token']);
			return true;
		}else{
			return false;
		}
	}
}
?>
