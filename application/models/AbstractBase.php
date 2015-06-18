<?php
/**
 * Abstract base model definition class.
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class AbstractBase extends CI_Model
{
    /**
     * Configuration identifier
     * @var string
     */
    protected $identifier = 'default';
    
    /**
     * Table name
     * @var string 
     */
    protected $table = null;
    
    /**
     * Table section
     * @var string 
     */
    protected $section = null;
    
    /**
     * Database configuration
     * @var array 
     */
    protected $config = array();
    
    /**
     * Constructor, load database
     */
    public function __construct()
    {
        $section = $table = '';
        $class   = strtolower(get_class($this));
        if (false !== strpos($class, '_')) {
            list($section, $table) = explode('_', $class, 2);
        } else {
            $section = 'core';
            $table   = $class;
        }
        // Split by uppercase
        /*if (empty($this->table)) {
            $class = implode('_', preg_split('/(?=[A-Z])/', get_class($this)));
            $this->table = $table;
        }*/
        $this->table   = $this->table ?: $table;
        $this->section = $this->section ?: $section;
        
        $this->load->database();
        
        $this->loadConfig();
    }
    
    /**
     * Remove un-exist columns
     * 
     * @param array $data
     * @return mixed
     */
    public function canonizeColumns(&$data)
    {
        $data    = (array) $data;
        $columns = $this->getColumns();
        foreach (array_keys($data) as $key) {
            if (!in_array($key, $columns) || null === $data[$key]) {
                unset($data[$key]);
            }
        }
    }
    
    /**
     * Free mysql connection resource
     */
    public function unconnect()
    {
        $this->conn = null;
    }
    
    /**
     * Load database configuration.
     * Config of different table can be specified by `$identifier` parameter.
     * 
     * @return array
     */
    abstract public function loadConfig();
    
    /**
     * Get mysql connection handler
     * 
     * @return type
     */
    abstract protected function connect();
    
    /**
     * Get table name
     * 
     * @return string
     */
    abstract public function getTable();

    /**
     * Get all fields of table.
     * 
     * @return array
     */
    abstract public function getColumns();
    
    /**
     * Get total records number according to where condition
     * 
     * @param array $where
     * @return int
     */
    abstract public function count($where = array());
    
    /**
     * Insert data into table
     * 
     * @param array $data
     * @return bool
     */
    abstract public function insert(array $data);
}
