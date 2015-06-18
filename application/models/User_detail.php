<?php
require_once 'UserModel.php';

/**
 * user_detail table model
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
class User_detail extends UserModel
{
    /**
     * Set partition field to id
     */
    public function __construct()
    {
        $this->partition['field'] = 'id';
        
        parent::__construct();
    }
}
