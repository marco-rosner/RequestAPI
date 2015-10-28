<?php

/**
 * Order class
 * 
 * @author Marco Rosner
 * @package RequestAPI
 */
class Order{

	// Order informations
	public $jsonData;
	private $arrayData;
	
  // If necessary, set others attributes here.

  /**
   * Order constructor
   * 
   * @param json $json
   */
  public function __construct($json) {
  	$this->jsonData = $json;
  	$this->arrayData = json_decode($json,true);
  	
    // If necessary, set others attributes here.

  }

}

?>