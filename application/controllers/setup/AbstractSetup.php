<?php
/**
 * Abstract setup controller, initialize controller
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
abstract class AbstractSetup extends CI_Controller
{
    /**
     * Database type, at the moment, only MySQL is allowed
     */
    const EXT_SQL   = 'sql';
    //const EXT_MONGO = 'mongo';
    //const EXT_REDIS = 'redis';
    
    protected $validFile = array(
        //self::EXT_MONGO,
        self::EXT_SQL,
        //self::EXT_REDIS,
    );
    
    /**
     * Constructor, load helpers
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('url');
        $this->load->helper('route');
        $this->load->helper('config');
        $this->load->helper('navigation');
    }
    
    /**
     * Default action
     */
    abstract public function index();
    
    /**
     * Check action permission
     * 
     * @return 
     */
    protected function requireLogin()
    {
        $this->load->library('session');
        
        $userState = $this->session->userdata('user_state');
        $userState = unserialize($userState);
        if (!isset($userState['id']) || empty($userState['id'])) {
            $currentSite = $this->uri->urlEncode(
                sprintf('%s%s', base_url(), uri_string())
            );
            return redirect(sprintf(
                '%suser/login/index/redirect/%s',
                base_url(),
                $currentSite
            ));
        }
    }
    
    /**
     * Load file with table query
     * 
     * @param string $filename  Filename
     * @return string
     */
    protected function loadConfig($filename)
    {
        $filepath = sprintf('%s/sql/%s', APPPATH, $filename);
        if (!file_exists($filepath)) {
            show_error(sprintf('The sql file `%s` is not exists.', $filename));
        }
        
        $handle  = fopen($filepath, 'r');
        $content = fread($handle, filesize($filepath));
        fclose($handle);
        
        return $content;
    }
    
    /**
     * Install tables into database
     * 
     * @param string $filename  Sql filename
     * @param array  $options   Optional data
     * @return bool
     */
    protected function setupTable($filename, $options = array())
    {
        // Skip if file type invalid
        $section = substr($filename, 0, strrpos($filename, '.'));
        $ext     = pathinfo($filename, PATHINFO_EXTENSION);
        if (!in_array($ext, $this->validFile)) {
            return true;
        }
        $sql = preg_replace('/#[^\n]+\n/', '', $this->loadConfig($filename));
        if (empty($sql)) {
            return true;
        }
        
        switch ($ext) {
            case self::EXT_SQL:
                $querys = explode(';', trim($sql));
                foreach ($querys as $query) {
                    if (empty($query)) {
                        continue;
                    }
                    $start   = strpos($query, '(');
                    $header  = substr($query, 0, $start + 1);
                    $matches = array();
                    preg_match_all('/^[^\{]+{([^}]+)\}.+$/', $header, $matches);
                    $table   = $matches[1][0];
                    $model   = sprintf('%s_%s', $section, $table);
                    $this->load->model($model);
                    $cQuery  = (array) $this->$model->parseCreationSql(
                        $query,
                        $options
                    );
                    
                    $this->load->database();
                    foreach ($cQuery as $sQuery) {
                        $result = $this->db->query($sQuery);
                        if (!$result) {
                            return false;
                        }
                    }
                }
                break;
            case self::EXT_MONGO:
                /*preg_match_all(
                    '/CREATE\s+TABLE\s+`([a-z_]+)`\s+\(([^\)]+)\);/',
                    $sql,
                    $matches
                );
                foreach ($matches[1] as $key => $table) {
                    $table = $table . '_model';
                    $result = $this->parseMongoQuery($matches[2][$key]);
                    
                    // Initiate table field value
                    $this->load->model($table);
                    $this->$table->collection()->insert($result['field']);
                    
                    // Create index
                    foreach ($result['index'] as $index) {
                        $this->$table->collection()->createIndex($index);
                    }
                    
                    // Create unique index
                    foreach ($result['unique'] as $index) {
                        $this->$table->collection()->createIndex(
                            $index,
                            array('unique' => true)
                        );
                    }
                }*/
                break;
            case self::EXT_REDIS:
                break;
        }
        
        return true;
    }
    
    /**
     * Parse mongo query 
     * @param string $query
     * @return array
     */
    /*protected function parseMongoQuery($query)
    {
        $result = array(
            'field'  => array(),
            'index'  => array(),
            'unique' => array(),
        );
        
        // Parse fields
        $query = trim($query);
        preg_match_all(
            '/\s*`([a-z_]+)`\s+DEFAULT\s+([a-z]+)\{([^\}]+)\}/',
            $query,
            $fields
        );
        foreach ($fields[1] as $key => $field) {
            $val = '';
            if ('int' === $fields[2][$key]) {
                $val = (int) $fields[3][$key];
            } elseif ('var' === $fields[2][$key]) {
                $val = (string) $fields[3][$key];
            }
            $result['field'][$field] = $val;
        }
        
        // Parse index
        $rows = explode(",\n", $query);
        foreach ($rows as $row) {
            $row = trim($row);
            if (false !== strpos($row, 'UNIQUE INDEX')) {
                $type = 'unique';
            } elseif (false !== strpos($row, 'INDEX')) {
                $type = 'index';
            } else {
                continue;
            }
            preg_match_all('`([a-z_]+):([0-1_]+)`', $row, $indexs);
            $tmp = array();
            foreach ($indexs[1] as $key => $field) {
                $tmp[$field] = (int) $indexs[2][$key];
            }
            $result[$type][] = $tmp;
        }
        
        return $result;
    }*/
}
