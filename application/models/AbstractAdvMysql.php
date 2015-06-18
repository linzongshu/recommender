<?php
require_once 'AbstractBaseMysql.php';

/**
 * Mysql model for advance operating. Such as segment table horizontally.
 * 
 * @author Zonghu Lin <lin40553024@163.com>
 */
abstract class AbstractAdvMysql extends AbstractBaseMysql
{
    /**
     * Table segmentation config
     * - <mode>: Segment table by which mode
     * - <step>: Data size of each table
     * - <field>: Field used for segmentation
     * 
     * @var array 
     */
    protected $segment = array();
    
    /**
     * Initialize segmentation config
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->canonizeSegment();
    }
    
    /**
     * Format segment array
     */
    protected function canonizeSegment()
    {
        foreach ($this->segment as &$segment) {
            foreach (array('mode', 'step', 'field') as $key) {
                if (!isset($segment[$key])) {
                    $segment[$key] = null;
                }
            }
        }
    }
    
    /**
     * {@inheritDoc}
     */
    public function getTable($options = array(), $type = 'suffix')
    {
        $data  = $options;
        $table = parent::getTable(array(), 'prefix');
        
        if ('raw' === $type) {
            return $this->table;
        } elseif ('prefix' === $type) {
            return $table;
        } elseif ('suffix' !== $type) {
            return '';
        }
        
        foreach ($this->segment as $segment) {
            $suffix = '';
            
            $value = null;
            if (isset($data[$segment['field']])) {
                $value = $data[$segment['field']];
            }
            $step  = $segment['step'];
            switch ($segment['mode']) {
                case 'id':
                    $value  = $value ?: 1;
                    $suffix = floor($value / $step) + 1;
                    break;
                case 'year':
                    $value  = $value ?: time();
                    if (!is_numeric($value)) {
                        $value = strtotime($value);
                    }
                    $suffix = date('Y', $value);
                    break;
                case 'mod':
                    $value  = $value ?: 1;
                    $suffix = ($value % $step) + 1;
                    break;
                case 'md5':
                    $suffix = ord(substr(md5($value), 0, $step));
                    break;
                case 'field':
                    $value  = $value ?: 'default';
                    $suffix = $value;
                    break;
                default:
                    $suffix = '';
                    break;
            }
            
            if ($suffix) {
                $table .= '_' . $suffix;
            }
        }
        
        return $table;
    }
    
    /**
     * Parse table fields structure from file in sql folder,
     * it will be used to create new tables.
     * 
     * @return string
     */
    protected function parseTableStructure($section = null)
    {
        $section = $section ?: $this->section;
        if (empty($section)) {
            show_error('Table section is required.');
        }
        
        $filename = sprintf('%s/sql/%s.sql', APPPATH, $section);
        if (!file_exists($filename)) {
            show_error(sprintf('file %s not exists', $filename));
        }
        
        $handle = fopen($filename, 'r');
        $sql    = fread($handle, filesize($filename));
        fclose($handle);
        
        // Remove comments and parse sql query
        $query  = preg_replace('/#[^\n]+\n/', '', $sql);
        $querys = explode(';', trim($query));
        foreach ($querys as $item) {
            if (empty($item)) {
                continue;
            }
            
            $start  = strpos($item, '(');
            $header = substr($item, 0, $start + 1);
            if (false !== strpos($header, "`{{$this->table}}`")) {
                continue;
            }
            $end      = strrpos($item, ')');
            $tableSql = substr($item, $start + 1, $end - $start);
        }
        
        return $tableSql;
    }
    
    /**
     * {@inheritDoc}
     */
    public function parseCreationSql($query = null, $options = array())
    {
        $query = $query ?: $this->parseTableStructure();
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
