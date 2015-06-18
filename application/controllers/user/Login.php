<?php
require_once APPPATH . 'controllers/AbstractController.php';

/**
 * Login controller
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class Login extends AbstractController
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
     * Login action
     */
    public function index()
    {
        $redirect = $this->uri->params('redirect', null);
        if (null !== $redirect) {
            $redirect = $this->uri->urlDecode($redirect);
        } else {
            $redirect = '/admin';
        }
        
        $this->load->helper('form');
        $this->load->library('form_validation');

        $data['title'] = 'User Login';
        $data['csrf']  = md5(uniqid());

        $this->form_validation->set_rules('identity', 'Username', 'required');
        $this->form_validation->set_rules('password', 'Password', 'required');
        $this->form_validation->set_rules('csrf', 'Csrf', 'required');

        $render = function($data) {
            $this->load->view('layout/header', $data);
            $this->load->view('content/user/login-index', $data);
            $this->load->view('layout/footer');
        };
        
        if ($this->form_validation->run() === FALSE) {
            return $render($data);
        } else {
            $where = array('identity' => $this->input->post('identity'));
            $this->load->model('core_account');
            $table = $this->core_account->getTable();
            $this->db->where($where)->limit(1, 0);
            $query = $this->db->get($table);
            $identity = $password = $salt = '';
            foreach ($query->result() as $row) {
                $salt     = $row->salt;
                $password = $row->credential;
                $identity = $row->identity;
            }
            $postPwd = md5($this->input->post('password') . $salt);
            if ($identity !== $this->input->post('identity')
                || $password !== $postPwd
            ) {
                $data['message'] = 'Wrong username or password';
                return $render($data);
            }
            
            $this->load->library('session');
            $userState = array(
                'id'       => 1,
                'identity' => $identity,
            );
            $duration = 8 * 3600;
            $this->session->set_userdata('user_state', serialize($userState), $duration);
            
            return redirect($redirect);
        }
        
        $render($data);
    }
    
    /**
     * Get logged user state
     * 
     * @param `redirect`  Redirect address
     * @return JSON
     */
    public function state()
    {
        $user     = $this->session->userdata('user_state');
        $userInfo = unserialize($user);
        $redirect = $this->uri->params('redirect', null);
        if (null === $redirect) {
            $url = isset($_SERVER['HTTP_REFERER'])
                ? $_SERVER['HTTP_REFERER'] : base_url();
            $redirect = $this->uri->urlEncode($url);
        }
        if (empty($userInfo)) {
            $loginUrl = assembleUrl(
                array(
                'section'    => 'user',
                'controller' => 'login',
                'redirect'   => $redirect,
            ));
            $html =<<<EOD
<span class="user-login"><a href="$loginUrl">Login</a></span>
EOD;
        } else {
            $logoutUrl = assembleUrl(
                array(
                'section'    => 'user',
                'controller' => 'logout',
                'redirect'   => $redirect,
            ));
            $html =<<<EOD
<span class="account">{$userInfo['identity']}</span>
<span class="user-logout"><a href="$logoutUrl">Logout</a></span>
EOD;
        }
        
        echo json_encode(array(
            'status' => true,
            'data'   => $html,
        ));
        exit;
    }
}
