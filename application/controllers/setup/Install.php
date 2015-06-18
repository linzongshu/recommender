<?php
require_once 'AbstractSetup.php';

/**
 * Site installation controller
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class Install extends AbstractSetup
{
    /**
     * Default action, create tables and setup basic site configuration data
     * 
     */
    public function index()
    {
        // Check permission of boot.php
        $perms = substr(sprintf('%o', fileperms(dirname(BASEPATH))), -4);
        if ('0777' !== $perms) {
            show_error('Please change permission of `boot.php` to 0777');
        }
        
        $isInstalled = false;
        $this->load->model('core_config');
        $table = $this->core_config->getTable();
        
        // Check whether the table exist
        $sql = "SHOW TABLES LIKE '{$table}'";
        $query = $this->db->query($sql);
        foreach ($query->result() as $row) {
            $isInstalled = true;
        }
        
        // Skip if table already exist, it mean the site been installed
        if ($isInstalled) {
            show_error('The site has already been installed');
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Install Site';
        $data['csrf']  = md5(uniqid());

        $rules = $this->getRules();
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('content/setup/install-index', $data);
            $this->load->view('layout/footer');
            
            return;
        }
        
        // Setup tables for system
        $path = sprintf('%s/sql', APPPATH);
        if (!is_dir($path)) {
            show_error('Path not exists.');
        }
        $directory = new DirectoryIterator($path);
        foreach ($directory as $fileinfo) {
            if ($fileinfo->isDir()
                || $fileinfo->isDot()
                || $fileinfo->isLink()
            ) {
                continue;
            }
            $filename = basename($fileinfo->getFilename());
            
            // Skip if table is core
            if (!preg_match('/^core\.[a-z]+$/', $filename)) {
                continue;
            }
            $result = $this->setupTable($filename);
            if (!$result) {
                show_error('Error occured when install table.');
            }
        }
        
        // Initial system data
        $this->load->helper('config');
        $meta = getConfig('meta');
        $data = array(
            array(
                'name'         => 'installed',
                'title'        => 'Whether Installed',
                'category'     => 'general',
                'value'        => 1,
                'visible'      => 0,
            ),
            array(
                'name'         => 'version',
                'title'        => 'Version',
                'category'     => 'general',
                'value'        => $meta['version'],
                'visible'      => 0,
            ),
            array(
                'name'         => 'author',
                'title'        => 'Author',
                'category'     => 'general',
                'value'        => json_encode($meta['author']),
                'visible'      => 0,
            ),
        );
        $this->db->insert_batch($table, $data);
        
        // Add super administrator
        $this->load->model('core_account');
        $accountTable = $this->core_account->getTable();
        $salt         = md5(uniqid());
        $password     = md5($this->input->post('password') . $salt);
        $time         = time();
        $account      = array(
            'identity'       => $this->input->post('identity'),
            'name'           => ucfirst($this->input->post('identity')),
            'credential'     => $password,
            'salt'           => $salt,
            'active'         => 1,
            'time_created'   => $time,
            'time_activated' => $time,
        );
        $this->db->insert($accountTable, $account);
        
        // Remove boot.php file then site will not redirect to install page again
        chdir(dirname(BASEPATH));
        rename('boot.php', 'boot.php.dist');
        
        $this->load->view('layout/header');
        $this->load->view('content/setup/success');
        $this->load->view('layout/footer');
    }
    
    /**
     * Get rule configuration
     * 
     * @return array
     */
    protected function getRules()
    {
        $config = array(
            array(
                'field' => 'identity',
                'label' => 'Username',
                'rules' => 'required|callback_checkIdentity',
            ),
            array(
                'field' => 'password',
                'label' => 'Password',
                'rules' => 'required|min_length[4]|max_length[32]',
            ),
            array(
                'field' => 'confirm_password',
                'label' => 'Confirm Password',
                'rules' => 'required|min_length[4]|max_length[32]|callback_checkPassword',
            ),
            array(
                'field' => 'csrf',
                'label' => 'CSRF',
                'rules' => 'required',
            ),
        );
        
        return $config;
    }
    
    /**
     * Check whether identity value is valid
     * 
     * @param string $value
     * @return bool
     */
    public function checkIdentity($value)
    {
        if (!preg_match('/^[a-z][a-z_]{3,19}$/', $value)) {
            $this->form_validation->set_message(
                'checkIdentity',
                'The identity must start with letter and can only contain
                lowcase letter and underscore and with length 4 - 20'
            );
            return false;
        }
        
        return true;
    }
    
    /**
     * Check whether identity value is valid
     * 
     * @param string $value
     * @return bool
     */
    public function checkPassword($value)
    {
        $password = $this->input->post('password');
        if ($password !== $value) {
            $this->form_validation->set_message(
                'checkPassword',
                'The two passwords mismatched'
            );
            return false;
        }
        
        return true;
    }
}
