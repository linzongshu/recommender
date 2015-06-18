<?php
require_once 'TagModel.php';

/**
 * tag_relevancy table model
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
class Tag_relevancy extends TagModel
{
    /**
     * {@inheritDoc} 
     */
    protected $partition = array(
        'type'  => 'horizontal',
        'mode'  => 'id',
        'step'  => 100000,
        'field' => 'tid',
    );
    
    public function count($where = array())
    {
        ;
    }
}
