<?php
require_once 'AbstractAdvMysql.php';

/**
 * Mysql model for partition.
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class AbstractPartitionMysql extends AbstractAdvMysql
{
    /**
     * Parse table partition SQL query, it will be append to the create statement.
     * 
     * @return string
     */
    abstract protected function parseTablePartition();
    
    /**
     * {@inheritDoc}
     */
    public function parseCreationSql($query = null, $options = array())
    {
        $result = parent::parseCreationSql($query, $options);
        $result = rtrim($result, ';');
        
        $result .= ' ' . $this->parseTablePartition() . ';';
        
        return $result;
    }
}
