<?php

/**
 * Authentication class send informations to RestExecutor class to make an HTTP post request.
 * 
 * @author Marco Rosner
 * @package RequestAPI
 */
class Authenticator{

	// Authentication's Default informations
  static $URL_AUTH = 'http://api.com/token';  	
	static $USERNAME = 'user!@#';
	static $PASSWORD = '!@#resu';

	public $token = '';
	public $token_type = '';
  public $urlAuth;

  /**
   * Authentication constructor
   * 
   * @param   RestExecutor $RestExecutor
   * @param   string $urlAuth
   */
	public function __construct($restExecutor, $urlAuth = null) {
    $this->urlAuth = (is_null($urlAuth)) ? self::$URL_AUTH : $urlAuth;
    $this->rest = $restExecutor;
	}

  /**
   * Forward Authentication informations to RestExecutor class to 
   * make an HTTP post request and get token and token_type data.
   * 
   * @param   string $user
   * @param   string $pwd
   */
	public function remoteAuth($user = null, $pwd = null){
    $user = (is_null($user)) ? self::$USERNAME : $user;
    $pwd = (is_null($pwd)) ? self::$PASSWORD : $pwd;
    
		$fields = array(
				'grant_type' => 'password',
				'username' => $user,
				'password' => $pwd
			);

		$headers = array('Content-Type' => 'application/x-www-form-urlencoded');

		$arr = $this->rest->post($this->urlAuth, $fields, $headers);

    $this->token = $arr['access_token'];
    $this->token_type = $arr['token_type'];
    
    if ($arr){
      return true;  
    } else {
      return false;
    }
  }

}

?>