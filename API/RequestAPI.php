<?php

require_once 'Order.php';
require_once 'Service/TicketProcessor.php';

/**
 * RequestAPI class, main class of this API. This class is responsible for forward information to
 * authentication and retrieve data and, in the end, send back the data retrieved in json format.
 * 
 * @author Marco Rosner
 * @package RequestAPI
 */
class RequestAPI {

	// URL request default
  	static $URL_DEFAULT = 'http://api.com/activity';

	// Order informations
	public $order;

	// Authenticator
	public $authenticator;

	// RestExecutor Service
	public $rest;

	// Url request
	public $url;

	/**
	 * RequestAPI constructor
	 * 
	 * @param   json $json
	 * @param   Authenticator $auth
	 * @param   RestExecutor $RestExecutor
	 * @param   string $url
	 */
	public function __construct($json, $auth, $RestExecutor, $url=null) {
		$this->url = (is_null($url)) ? self::$URL_DEFAULT : $url;
		$this->order = new Order($json);
		$this->authenticator = $auth;
		$this->rest = $RestExecutor;

	}

	/**
	 * Send informations (username and password) to Authenticator class do remote authentication.
	 * 
	 * @param   string $username
	 * @param   string $password
	 * @return  boolean
	 */
	public function auth($username, $password){
		return $this->authenticator->remoteAuth($username, $password);
	}

	/**
	 * Make a post request, via RestExecutor Service class, to retrieve data.
	 * 
	 * @param   string $method
	 * @return  array
	 */
	public function request($method = 'POST'){

		switch ($method) {
			case 'GET':
				$data = $this->rest->get($this->url, $this->order->jsonData, $this->rest->getHeaders($this->authenticator));
				break;
			case 'POST':
				$data = $this->rest->post($this->url, $this->order->jsonData, $this->rest->getHeaders($this->authenticator));
				break;
			case 'PUT':
				$data = $this->rest->put($this->url, $this->order->jsonData, $this->rest->getHeaders($this->authenticator));
				break;
			case 'DELETE':
				$data = $this->rest->delete($this->url, $this->order->jsonData, $this->rest->getHeaders($this->authenticator));
				break;
			default:
				echo "Method do not supported.";
				break;
		}

		echo json_encode($data);

	}

}
?>