<?php
require_once 'AbstractAdvMysql.php';

/**
 * abstract item model
 * 
 * Table will segment by business field and item ID range
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class ItemModel extends AbstractAdvMysql
{
    /**
     * {@inheritDoc}
     */
    protected $segment = array(
        0   => array(
            'mode'  => 'field',
            'field' => 'business',
        ),
        1   => array(
            'mode'  => 'id',
            'step'  => 5000000,
            'field' => 'iid',
        ),
    );
    
    /**
     * {@inheritDoc}
     */
    public function count($where = array())
    {
        
    }
}
