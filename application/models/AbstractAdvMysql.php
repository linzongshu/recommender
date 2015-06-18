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
     * @var array 
     */
    protected $partition = array(
        // This type only need to create table in other host, no extra tables
        // need to create
        'type'  => 'vertical',
        // Segment table by which mode if type is horizontal
        //'mode'  => null,
        // Segment step
        //'step'  => null,
        // Field use for segmentation
        //'field' => null,
    );
    
    /**
     * {@inheritDoc}
     */
    public function getTable($data = array())
    {
        if ('horizontal' !== $this->partition['type']) {
            return parent::getTable();
        }
        
        $suffix = '';
        $value  = isset($data[$this->partition['field']])
            ? $data[$this->partition['field']] : null;
        $step   = $this->partition['step'];
        switch ($this->partition['mode']) {
            case 'id':
                $value  = null === $value ? 1 : $value;
                $suffix = floor($value / $step) + 1;
                break;
            case 'year':
                $value  = null === $value ? time() : $value;
                if (!is_numeric($value)) {
                    $value = strtotime($value);
                }
                $suffix = date('Y', $value);
                break;
            case 'mod':
                $value  = null === $value ? 1 : $value;
                $suffix = ($value % $step) + 1;
                break;
            case 'md5':
                $suffix = ord(substr(md5($value), 0, $step));
            default:
                $suffix = '';
                break;
        }
        if ($suffix) {
            $suffix = '_' . $suffix;
        }
        
        return parent::getTable() . $suffix;
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
