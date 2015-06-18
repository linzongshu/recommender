<?php
/**
 * Class use for check user permission
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class Permission extends CI_Controller
{
    /**
     * Check action permission
     * 
     * @return 
     */
    public function checkAction()
    {
        $this->load->helper('url');
        $this->load->library('session');
        
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