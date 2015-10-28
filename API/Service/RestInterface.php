<?php
/**
 * Rest interface. 
 * Define the signature of basics methods of RestFul and make sure that they exist.
 * 
 * @author Marco Rosner
 * @package RequestAPI
 */
interface RestInterface {
    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     */
    public function get($url, $parameters, $headers);

    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     */
    public function post($url, $parameters, $headers);

    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     */
    public function put($url, $parameters, $headers);

    /**
     * @param string $url
     * @param array $parameters
     * @param array $headers
     */
    public function delete($url, $parameters, $headers);

}
?>