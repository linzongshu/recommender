<?php
require_once APPPATH . 'controllers/AbstractController.php';

/**
 * Business controller
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class Business extends AbstractController
{
    /**
     * Constructor, load model
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->model('core_business');
        $this->load->helper('url');
        $this->load->helper('route');
        $this->load->helper('navigation');
        $this->load->helper('config');
    }
    
    /**
     * Default action
     */
    public function index()
    {
        
    }
    
    /**
     * List action
     */
    public function lists()
    {
        $this->load->helper('form');
        
        $limit   = (int) $this->uri->params('limit', 20);
        $page    = (int) $this->uri->params('page', 0);
        $offset  = $page;
        
        $where = array();
        $table = $this->core_business->getTable();
        $query = $this->db->order_by('time_created', 'ASC')
            ->limit($limit, $offset)->get($table);
        $rowset = $query->result();
        $data['rows']     = $rowset;
        
        $params = compact('limit');
        $params = array_filter($params, function(&$val) {
            return null !== $val;
        });
        $baseUrl = $this->url('lists');
        
        // Paginator
        $count = $this->core_business->count($where);
        $this->load->library('pagination');
        $this->pagination->initialize(array(
            'base_url'     => $baseUrl,
            'total_rows'   => $count,
            'per_page'     => $limit,
            'cur_page'     => $page,
            'query_params' => $params,
        ));
        
        $this->load->view('layout/header', $data);
        $this->load->view('content/admin/business-list', $data);
        $this->load->view('layout/footer');
    }
    
    /**
     * Add business action
     */
    public function add()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Add business';

        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('url', 'URL', 'required|callback_checkUrl');
        $this->form_validation->set_rules('name', 'Name', 'required|callback_checkName');

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('content/admin/business-add');
            $this->load->view('layout/footer');
        } else {
            $this->load->helper('url');

            $key    = uniqid();
            $secret = md5($key . time());
            $row = array(
                'title'        => $this->input->post('title'),
                'url'          => $this->input->post('url'),
                'name'         => $this->input->post('name'),
                'key'          => $key,
                'secret'       => $secret,
                'active'       => 0,
                'time_created' => time(),
                'in_use'       => 0,
            );
            $table = $this->core_business->getTable();
            $this->db->insert($table, $row);
            
            $redirect = $this->url('lists');
            redirect($redirect);
        }
    }
    
    /**
     * Edit business
     */
    public function edit()
    {
        $id = $this->uri->params('id', 0);
        
        $table  = $this->core_business->getTable();
        $this->db->where(array('id' => $id))->limit(1, 0);
        $query  = $this->db->get($table);
        $rowset = array();
        foreach ($query->result() as $row) {
            $rowset = (array) $row;
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'Edit business';
        $data['rows']  = $rowset;
        $data['csrf']  = md5(uniqid());

        $this->form_validation->set_rules('title', 'Title', 'required');
        if (!$rowset['active']) {
            $this->form_validation->set_rules('url', 'URL', 'required|callback_checkUrl');
        }

        if ($this->form_validation->run() === FALSE) {
            $this->load->view('layout/header', $data);
            $this->load->view('content/admin/business-edit');
            $this->load->view('layout/footer');
        } else {
            $this->load->helper('url');

            $updateSet = array(
                'title' => $this->input->post('title'),
            );
            if (!$rowset['active']) {
                $updateSet['url'] = $this->input->post('url');
            }
            $this->db->where(array('id' => $id));
            $this->db->update($table, $updateSet);
            
            $redirect = $this->url('lists');
            redirect($redirect);
        }
    }
    
    /**
     * Activate/deactivate business by id
     * 
     * @return HEADER
     */
    public function active()
    {
        $url = $this->url('lists', array('active' => 1));
        
        $ids      = $this->uri->params('id', 0);
        $redirect = $this->uri->params('redirect', $url);
        $redirect = urldecode($redirect);
        $status   = $this->uri->params('status', 0);
        
        if (empty($ids)) {
            return redirect($redirect);
        }
        $ids = is_numeric($ids) ? $ids : explode(',', urldecode($ids));
        
        $table = $this->core_business->getTable();
        $this->db->where_in('id', $ids);
        $this->db->update($table, array('active' => $status));
        
        return redirect($redirect);
    }
    
    /**
     * View business details
     */
    public function view()
    {
        $data = array();
        
        $id = (int) $this->uri->params('id', 0);
        if (empty($id)) {
            $data['error'] = 'ID is missing.';
        } else {
            $table  = $this->core_business->getTable();
            $this->db->where(array('id' => $id));
            $query  = $this->db->get($table);
            $result = array();
            foreach ($query->result() as $row) {
                $result = (array) $row;
            }
            $data['data'] = $result;
        }
        
        $this->load->view('layout/header', $data);
        $this->load->view('content/admin/business-view', $data);
        $this->load->view('layout/footer');
    }
    
    /**
     * Delete items by id
     * 
     * @return HEADER
     */
    public function delete()
    {
        $url = $this->url('lists', array('active' => 1));
        
        $ids      = $this->uri->params('id', 0);
        $redirect = $this->uri->params('redirect', $url);
        $redirect = urldecode($redirect);
        
        if (!empty($ids)) {
            $ids = is_numeric($ids) ? (array) $ids : explode(',', urldecode($ids));
        
            $table = $this->core_business->getTable();
            $this->db->where_in('id', $ids);
            $this->db->where('in_use', 0);
            $this->db->delete($table);
        }
        
        return redirect($redirect);
    }
    
    /**
     * Check if given url is valid
     * 
     * @param string $value
     * @return bool
     */
    public function checkUrl($value)
    {
        if (!preg_match('/^http(s)?:\/\/(.)+$/', $value)) {
            $this->form_validation->set_message(
                'checkUrl',
                'URL must start with http or https'
            );
            return false;
        }
        
        return true;
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
        
        $action = $this->uri->params('action', 'add');
        $where  = array('name' => $value);
        if ('edit' === $action) {
            $id = $this->uri->params('id', 0);
            $where['id <> ?'] = $id;
        }
        $count = $this->core_business->count($where);
        if ($count) {
            $this->form_validation->set_message(
                'checkName',
                'The name is already exist, please try again'
            );
            return false;
        }
        
        return true;
    }
}
