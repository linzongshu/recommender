<?php
require_once APPPATH . 'controllers/AbstractController.php';

/**
 * Dashboard of admin end
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class Index extends AbstractController
{
    /**
     * Constructor, load model
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('navigation');
    }
    
    /**
     * Dashboard action
     * 
     * @return View
     */
    public function index()
    {
        // Check if business has been installed
        $isInstalled = false;
        $this->load->model('core_config');
        $table = $this->core_config->getTable();
        $this->db->where(array('name' => 'business'));
        $query = $this->db->get($table);
        foreach ($query->result() as $row) {
            $isInstalled = (bool) $row->value;
        }
        
        if (!$isInstalled) {
            $currentUrl = base_url() . uri_string();
            $redirect = assembleUrl(array(
                'section'    => 'setup',
                'controller' => 'business',
                'action'     => 'index',
            ), array(
                'redirect' => $this->uri->urlEncode($currentUrl),
            ));
            return redirect($redirect);
        }
        
        $this->load->view('layout/header');
        $this->load->view('content/admin/index-index');
        $this->load->view('layout/footer');
    }
}
