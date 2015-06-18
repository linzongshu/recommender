<?php
/**
 * Abstract action controller, it will do the following tasks:
 * - Process something before call action
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
abstract class AbstractController extends CI_Controller
{
    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
        
        $this->load->helper('url');
        $this->load->helper('route');
        $this->load->library('session');
        
        $this->preAction();
    }
    
    /**
     * Check action permission
     * 
     * @return 
     */
    protected function preAction()
    {
        $denyAccess = true;
        
        $query = uri_string();
        if (false !== strpos($query, '/')) {
            list($section, $skips) = explode('/', $query, 2);
        } else {
            $section = $query;
        }
        if ('admin' === $section) {
            $userState = $this->session->userdata('user_state');
            $userState = unserialize($userState);
            if (isset($userState['id']) && $userState['id']) {
                $denyAccess = !$this->checkAccess($userState['id']);
            } else {
                $currentSite = $this->uri->urlEncode(
                    sprintf('%s%s', base_url(), uri_string())
                );
                return redirect(sprintf(
                    '%suser/login/index/redirect/%s',
                    base_url(),
                    $currentSite
                ));
            }
        } else {
            $denyAccess = false;
        }
        
        if ($denyAccess) {
            echo 'Access denied';
            exit;
        }
    }
    
    /**
     * @todo
     */
    protected function checkAccess()
    {
        return true;
    }
}