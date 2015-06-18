<?php
require_once 'AbstractAdvMysql.php';

/**
 * abstract stats model
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class StatsModel extends AbstractAdvMysql
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
            'field' => 'item',
        ),
    );
    
    /**
     * {@inheritDoc}
     */
    public function count($where = array())
    {
        
    }
}
