<?php

require_once 'RestInterface.php';

/**
* RestExecutor Service make request via HTTP methods to retrieve remote data.
* 
* @author Marco Rosner
* @package RequestAPI
*/
class RestExecutor implements RestInterface {

    private $options;

    // Populated after execution:
    public $response;
    public $info;
    public $error;

    /**
    * RestExecutor constructor
    *  
    * @param   array $options
    */
    public function __construct($options=array()){
        $default_options = array(
            'headers' => array(), 
            'parameters' => array(), 
            'curl_options' => array(), 
            'user_agent' => "RestClient"
            );

        $this->options = array_merge($default_options, $options);
    }

    /**
    * Send back HTTP headers like content-type (for json files) and authorization.
    * 
    * @param   Authentication $auth
    * @return  array
    */
    public function getHeaders($auth) {
        $header = array();
        $header['Content-type'] = 'application/json';
        $header['Authorization'] = $auth->token_type.' '.$auth->token;
        return $header;
    }

    /**
    * Format query to curl
    * 
    * @param   array $parameters
    * @param   string $primary
    * @param   string $secondary
    * @return  string
    */
    private function format_query($parameters, $primary='=', $secondary='&'){
        $query = "";
        foreach($parameters as $key => $value){
            $pair = array(urlencode($key), urlencode($value));
            $query .= implode($primary, $pair) . $secondary;
        }
        return rtrim($query, $secondary);
    }

    /**
    * Make an HTTP get request
    * 
    * @param   string $url
    * @param   array $parameters
    * @param   array $headers
    * @return  array
    */
    public function get($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'GET', $parameters, $headers);
    }

    /**
    * Make an HTTP post request
    * 
    * @param   string $url
    * @param   array $parameters
    * @param   array $headers
    * @return  array
    */
    public function post($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'POST', $parameters, $headers);
    }

    /**
    * Make an HTTP put request
    * 
    * @param   string $url
    * @param   array $parameters
    * @param   array $headers
    * @return  array
    */
    public function put($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'PUT', $parameters, $headers);
    }

    /**
    * Make an HTTP delete request
    * 
    * @param   string $url
    * @param   array $parameters
    * @param   array $headers
    * @return  array
    */
    public function delete($url, $parameters=array(), $headers=array()){
        return $this->execute($url, 'DELETE', $parameters, $headers);
    }

    /**
    * Execute the requests with property methods to retrieve remote data.
    * 
    * @param   string $url
    * @param   string $method
    * @param   array $parameters
    * @param   array $headers
    * @return  array
    */
    private function execute($url, $method='GET', $parameters=array(), $headers=array()){
        $ch = curl_init();
        $curlopt = array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => TRUE, 
            CURLOPT_USERAGENT => $this->options['user_agent']
            );

        if(count($this->options['headers']) || count($headers)){
            $curlopt[CURLOPT_HTTPHEADER] = array();
            $headers = array_merge($this->options['headers'], $headers);
            foreach($headers as $key => $value){
                $curlopt[CURLOPT_HTTPHEADER][] = sprintf("%s:%s", $key, $value);
            }
        }

        if(is_array($parameters)){
            $parameters = array_merge($this->options['parameters'], $parameters);
            $parameters_string = $this->format_query($parameters);
        }
        else
            $parameters_string = (string) $parameters;

        if(strtoupper($method) == 'POST'){
            $curlopt[CURLOPT_POST] = TRUE;
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        }
        elseif(strtoupper($method) != 'GET'){
            $curlopt[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
            $curlopt[CURLOPT_POSTFIELDS] = $parameters_string;
        }
        elseif($parameters_string){
            $this->url .= strpos($this->url, '?')? '&' : '?';
            $this->url .= $parameters_string;
        }

        curl_setopt_array($ch, $curlopt);

        $this->response = curl_exec($ch);
        $this->info = (object) curl_getinfo($ch);
        $this->error = curl_error($ch);

        curl_close($ch);

        return json_decode($this->response, true);
    }

}