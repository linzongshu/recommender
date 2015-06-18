<?php
require_once 'AbstractSetup.php';

/**
 * Business controller for setup business
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class Business extends AbstractSetup
{
    /**
     * Setup default business, it will redirect to source page if it already
     * setup.
     * 
     */
    public function index()
    {
        $this->requireLogin();
        
        $redirect = $this->uri->params('redirect', base_url() . 'admin');
        $redirect = $this->uri->urlDecode($redirect);
        
        // Check if business has been installed
        $isInstalled = false;
        $this->load->model('core_config');
        $table = $this->core_config->getTable();
        $this->db->where(array('name' => 'business'));
        $query = $this->db->get($table);
        foreach ($query->result() as $row) {
            $isInstalled = (bool) $row->value;
        }
        if ($isInstalled) {
            return redirect($redirect);
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Setup Business';
        $data['csrf']  = md5(uniqid());

        $rules = array(
            array(
                'field' => 'title',
                'label' => 'Title',
                'rules' => 'required',
            ),
            array(
                'field' => 'name',
                'label' => 'Name',
                'rules' => 'required|callback_checkName',
            ),
            array(
                'field' => 'csrf',
                'label' => 'CSRF',
                'rules' => 'required',
            ),
        );
        $this->form_validation->set_rules($rules);

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('content/setup/business-index', $data);
            $this->load->view('layout/footer');
            
            return;
        }
        $name = $this->input->post('name');
        
        // Setup tables for given business
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
            if (preg_match('/^core\.[a-z]+$/', $filename)) {
                continue;
            }
            $result = $this->setupTable($filename, array('business' => $name));
            if (!$result) {
                show_error('Error occured when install table.');
            }
        }
        
        // Add default business data
        $this->load->model('core_business');
        $businessTable = $this->core_business->getTable();
        $key           = uniqid();
        $secret        = md5($key . time());
        $business      = array(
            'title'          => $this->input->post('title'),
            'name'           => $name,
            'key'            => $key,
            'secret'         => $secret,
            'active'         => 1,
            'time_created'   => time(),
            'in_use'         => 1,
        );
        $this->db->insert($businessTable, $business);
        
        // Specify business installed
        $data = array(
            'name'         => 'business',
            'title'        => 'Whether Business Installed',
            'category'     => 'general',
            'value'        => 1,
            'visible'      => 0,
        );
        $this->db->insert($table, $data);
        
        return redirect($redirect);
    }
    
    /**
     * Check whether name value is valid
     * 
     * @param string $value
     * @return bool
     */
    public function checkName($value)
    {
        if (!preg_match('/^[a-z][a-z_]{4,31}$/', $value)) {
            $this->form_validation->set_message(
                'checkName',
                'The business name must start with letter and can only contain
                lowcase letter and underscore and with length 5 - 32'
            );
            return false;
        }
        
        return true;
    }
}
