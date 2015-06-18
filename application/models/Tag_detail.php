<?php
require_once 'TagModel.php';

/**
 * tag_detail table model
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
class Tag_detail extends TagModel
{
    /**
     * {@inheritDoc} 
     */
    protected $engine = 'MERGE';
    
    /**
     * {@inheritDoc}
     */
    protected $segment = array(
        0   => array(
            'mode'  => 'id',
            'step'  => 100000,
            'field' => 'id',
        ),
    );
    
    /**
     * {@inheritDoc}
     */
    public function count($where = array())
    {
        ;
    }
    
    /**
     * {@inheritDoc}
     */
    public function parseCreationSql($query = null, $options = array())
    {
        $sql      = array();
        $sql[]    = parent::parseCreationSql($query, $options) . "\r\n";
        $tables[] = $this->getTable($options, 'suffix');
        
        $unionTable = sprintf(
            '%s%s_%s',
            $this->config['prefix'],
            $this->section,
            $this->table
        );
        $unionSql = preg_replace("/\{{$this->table}\}/", $unionTable, $query);
        $unionSql = rtrim($unionSql, ';');
        $unionSql .= ' ENGINE=' 
               . $this->engine 
               . ' UNION=(' 
               . implode(',', $tables) 
               . ') INSERT_METHOD=LAST;';
        $sql[] = $unionSql;
        
        return $sql;
    }
    
    /**
     * {@inheritDoc}
     */
    /*protected function parseTablePartition()
    {
        $query =<<<EOD
PARTITION BY RANGE (id) (
    PARTITION p0 VALUES LESS THAN (100000),
    PARTITION p1 VALUES LESS THAN (200000),
    PARTITION p2 VALUES LESS THAN (300000),
    PARTITION p3 VALUES LESS THAN (400000),
    PARTITION p4 VALUES LESS THAN (500000),
    PARTITION p5 VALUES LESS THAN (600000),
    PARTITION p6 VALUES LESS THAN (700000),
    PARTITION p7 VALUES LESS THAN (800000),
    PARTITION p8 VALUES LESS THAN (MAXVALUE),
)
EOD;
        return $query;
    }*/
}
