<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Custom config helper
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */

if (!function_exists('getConfig')) {
    /**
     * Get configuration data.
     * Parameter 1 specify data of which file to fetch.
     * File prefixed with `my_` in `config` folder will be included if a pure
     * string a given, the string is the filename, for example: `meta` point to
     * `my_meta.php` config file.
     * Or given a string with format `var:{filename}`, means config file in `var`
     * folder will be include.
     * 
     * Parameter 2, 3, ... is the key of array want to access.
     * 
     * @return mixed
     */
    function getConfig()
    {
        $argv      = func_get_args();
        $component = array_shift($argv);
        if (empty($component)) {
            return array();
        }
        $identifier = 'config/my_';
        if (false !== strpos($component, ':')) {
            list($identifier, $component) = explode(':', $component);
            if ('var' !== $identifier) {
                return array();
            }
            $identifier .= '/';
        }
        
        $configPath = sprintf('%s/%s%s.php', APPPATH, $identifier, $component);
        if (!file_exists($configPath)) {
            return array();
        }
        
        $config = include $configPath;
        if (empty($argv)) {
            return $config;
        }
        
        $getElement = function ($keys, $params) use (&$getElement) {
            $key = array_shift($keys);
            if (!empty($keys)) {
                return $getElement($keys, $params[$key]);
            } else {
                return $params[$key];
            }
        };
        
        return $getElement($argv, $config);
    }
}


/* End of file config_helper.php */
/* Location: ./application/helpers/config_helper.php */