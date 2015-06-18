<?php
require_once 'AbstractBaseMysql.php';

/**
 * Common mysql model
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class AbstractCommonMysql extends AbstractBaseMysql
{
    /**
     * {@inheritDoc}
     */
    public function count($where = array())
    {
        if (!is_array($where)) {
            show_error('Given where condition is not array.');
        }
        
        $table = $this->getTable();
        $this->db->select('count(*) AS count');
        foreach ($where as $key => $val) {
            if (false !== strpos($key, '?')) {
                list($key, $symbol, $ignore) = explode(' ', $key);
                if ('like' === strtolower($symbol)) {
                    $this->db->like($key, $val);
                }
                continue;
            }
            $this->db->where($key, $val);
        }
        $query = $this->db->get($table);
        
        $result = 0;
        foreach ($query->result() as $row) {
            $result = (int) $row->count;
            break;
        }
        
        return $result;
    }
    
    /**
     * {@inheritDoc}
     */
    public function parseCreationSql($query = null, $options = array())
    {
        if (empty($query)) {
            return false;
        }
        
        $table = $this->getTable($options);
        $query = preg_replace("/\{{$this->table}\}/", $table, $query);
        $query = rtrim($query, ';');
        $query .= ' ' . $this->parseEngineString() . ';';
        
        return $query;
    }
}
