<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Custom navigation helper
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */

if ( ! function_exists('getNavConfig')) {
    /**
     * Get navigation configuration
     * 
     * @param string  $section   Front section or admin section
     * @param string  $filename  Navigation configuration filename
     * @return array
     */
    function getNavConfig($section = 'front', $filename = 'navigation')
    {
        $configPath = sprintf('%s/config/my_%s.php', APPPATH, $filename);
        if (!file_exists($configPath)) {
            return array();
        }
        
        $config = include $configPath;
        
        return isset($config[$section]) ? $config[$section] : array();
    }
}

if ( ! function_exists('renderNav')) {
    /**
     * Generate navigation HTML tag according to its configuration
     * 
     * @param array  $pages  Navigation pages
     * @param bool   $class  Whether to use class
     * @return string
     */
    function renderNav($pages = array(), $class = true)
    {
        if (empty($pages)) {
            return;
        }
        
        $className = $class ? 'nav' : '';
        $html = sprintf('<ul class="%s">', $className);
        
        foreach ($pages as $page) {
            if (!empty($page['url'])) {
                $html .= sprintf(
                    '<li><a href="%s">%s</a>',
                    $page['url'],
                    $page['label']
                );
            } else {
                $html .= '<li><a href="#">' . $page['label'] . '</a>';
            }
            if (isset($page['child'])) {
                $html .= renderNav($page['child'], false);
            }
            $html .= '</li>';
        }
        
        return $html . '</ul>';
    }
}


/* End of file navigation_helper.php */
/* Location: ./application/helpers/nagivation_helper.php */