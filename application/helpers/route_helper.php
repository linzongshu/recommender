<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Custom router helper
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */

if ( !function_exists('assemble_url')) {
    /**
    * Assemble URL by given parameters.
    *
    */
    function assembleUrl($params, $query = array())
    {
        foreach (array('section', 'controller', 'action') as $name) {
            $$name = isset($params[$name]) ? $params[$name] : 'index';
            unset($params[$name]);
        }
        $url = sprintf(
            '%s%s/%s/%s',
            base_url(),
            $section,
            $controller,
            $action
        );
        
        if (!empty($params)) {
            $temp = array();
            foreach ($params as $key => $val) {
                $temp[] = $key;
                $temp[] = $val;
            }
            $url   .= '/' . implode('/', $temp);
        }
        if ($query) {
            $temp = array();
            foreach ($query as $key => $val) {
                $temp[] = sprintf('%s=%s', $key, urlencode($val));
            }
            $url .= '?' . implode('&', $temp);
        }
        
        return $url;
    }
}

/* End of file route_helper.php */
/* Location: ./application/helpers/route_helper.php */
