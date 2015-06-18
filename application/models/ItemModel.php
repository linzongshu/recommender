<?php
require_once 'AbstractAdvMysql.php';

/**
 * abstract item model
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class ItemModel extends AbstractAdvMysql
{
    /**
     * {@inheritDoc}
     */
    public function count($where = array())
    {
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTable($data = array())
    {
        $business = 'default';
        if (isset($data['business'])) {
            $business = $data['business'];
        }
        
        return sprintf('%s_%s', parent::getTable($data), $business);
    }
}
