<?php
require_once 'AbstractAdvMysql.php';

/**
 * abstract user model
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class UserModel extends AbstractAdvMysql
{
    /**
     * {@inheritDoc}
     */
    protected $segment = array(
        0   => array(
            'mode'  => 'id',
            'step'  => 100000,
            'field' => 'uid',
        ),
    );
    
    /**
     * {@inheritDoc}
     */
    public function count($where = array())
    {
        
    }
}
