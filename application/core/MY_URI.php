<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Custom URI
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class MY_URI extends CI_URI
{
    /**
     * Query parameters
     * @var array
     */
    protected $params = null;
    
    /**
     * Get query parameter
     * 
     * @param string  $name
     * @param mixed   $default
     * @return mixed
     */
    public function params($name = null, $default = null)
    {
        if (null === $this->params) {
            $this->params = $this->uri_to_assoc(4);
        }
        $this->params = array_merge($_POST, $this->params);
        $this->params = array_merge($_GET, $this->params);
        
        if (null === $name) {
            return $this->params;
        }
        
        return isset($this->params[$name]) ? $this->params[$name] : $default;
    }
    
    /**
     * Encode URL, encode % to %25
     * 
     * @param string $url
     * @return string
     */
    public function urlEncode($url)
    {
        $url = preg_replace('/index\.php/', 'indexdotphp', $url);
        return str_replace('%', '%25', urlencode($url));
    }
    
    /**
     * Decode URL
     * 
     * @param string $url
     * @return string
     */
    public function urlDecode($url)
    {
        $url = preg_replace('/indexdotphp/', 'index.php', $url);
        return urldecode(str_replace('%25', '%', $url));
    }
}
