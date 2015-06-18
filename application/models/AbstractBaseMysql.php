<?php
require_once 'AbstractBase.php';

/**
 * Base mysql model, for connecting database. Provide some common APIs
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class AbstractBaseMysql extends AbstractBase
{
    /**
     * Table engine type
     * @var string 
     */
    protected $engine = 'MyISAM';
    
    /**
     * Allowed engine type
     * @var array 
     */
    protected $allowedEngine = array(
        'MyISAM', 'InnoDB', 'MERGE', 'ARCHIVE', 'CSV', 'MEMORY'
    );
    
    /**
     * Table default auto increment
     * @var string 
     */
    protected $autoIncrement = 0;
    
    /**
     * Connect to host according to config
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->connect();
    }
    /**
     * {@inheritDoc}
     */
    public function loadConfig()
    {
        $filename = APPPATH . '/config/database.php';
        if (!file_exists($filename)) {
            show_error(sprintf('file %s not exists', $filename));
        }
        include $filename;
        
        $identifier = isset($db[$this->identifier]) ? $this->identifier : 'default';
        $this->config = $db[$identifier];
        
        return $this->config;
    }
    
    /**
     * {@inheritDoc}
     */
    protected function connect()
    {
        $params = sprintf(
            'mysql://%s:%s@%s:%s/%s',
            $this->config['username'],
            $this->config['password'],
            $this->config['hostname'],
            $this->config['port'],
            $this->config['database']
        );

        require_once BASEPATH . '/database/DB.php';
        $this->db = DB($params);
        
        return $this->db;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getColumns()
    {
        $sql = 'select COLUMN_NAME as name from information_schema.columns '
             . 'where table_name=\'' 
             . $this->table . '\' and table_schema=\'' 
             . $this->config['database'] . '\'';
        try {
            $query = $this->db->query($sql);
        } catch (\Exception $e) {
            show_error($e->getMessage());
        }
        
        $fields = array();
        foreach ($query->result() as $row) {
            $fields[] = $row->name;
        }
        
        return $fields;
    }
    
    /**
     * Get table engine
     * 
     * @return string
     */
    protected function getEngine()
    {
        if (empty($this->engine) ||
            !in_array($this->engine, $this->allowedEngine)
        ) {
            $this->engine = 'MyISAM';
        }
        
        return $this->engine;
    }
    
    /**
     * Generate mysql table engine structure
     * 
     * @return string
     */
    protected function parseEngineString()
    {
        $engine = $this->getEngine();
        
        $autoIncrement = '';
        if ($this->autoIncrement) {
            $autoIncrement = ' AUTO_INCREMENT=' . $this->autoIncrement;
        }
        
        $result =<<<EOD
ENGINE={$engine} DEFAULT CHARSET=utf8{$autoIncrement}
EOD;
        
        return $result;
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTable($options = array(), $type = 'suffix')
    {
        $table = sprintf('%s%s_%s', $this->config['prefix'], $this->section, $this->table);
        
        if ('raw' === $type) {
            return $this->table;
        } elseif ('suffix' === $type) {
            if (isset($options['suffix'])) {
                $table .= '_' . $options['suffix'];
            }
        } elseif ('prefix' !== $type) {
            $table = '';
        }
        
        return $table;
    }
    
    /**
     * {@inheritDoc}
     */
    public function insert(array $data)
    {
        $table = $this->getTable($data);
        
        $this->canonizeColumns($data);
        return $this->db->insert($table, $data);
    }
    
    /**
     * Generate table creation query
     * 
     * @param  string  $query    Query to parse
     * $param  array   $options  Optional data
     * @return string|array
     */
    abstract public function parseCreationSql($query = null, $options = array());
}
