<?php
/**
 * Custom pagination
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class MY_Pagination extends CI_Pagination
{
    /**
     * Query parameters
     * @var array
     */
    protected $queryParams = null;
    
    /**
     * Assembling query parameter into base url.
     * 
     * @param array $params
     */
    public function initialize(array $params = array())
    {
        if (empty($params)) {
            return;
        }
        
        if (isset($params['query_params'])
            && is_array($params['query_params'])
        ) {
            $this->queryParams = $params['query_params'];
            unset($params['query_params']);
        }
        
        if (!empty($this->queryParams)) {
            $params['base_url'] .= '/' . $this->toString($this->queryParams);
        }
        $params['base_url'] = rtrim($params['base_url'], '/page') . '/page';
        parent::initialize($params);
    }
    
    /**
     * Convert data from array to string
     * 
     * @param array $params
     * @return string
     */
    protected function toString($params)
    {
        $temp = array();
        foreach ((array)$params as $key => $val)
        {
            $temp[] = $key;
            $temp[] = $val;
        }

        return implode('/', $temp);
    }
}
