<?php
require_once APPPATH . 'controllers/AbstractController.php';

/**
 * Logout controller
 * 
 * @author Zongshu Lin <lin40553024@163.com>
 */
class Logout extends AbstractController
{
    /**
     * Logout action
     */
    public function index()
    {
        $redirect = $this->uri->params('redirect', null);
        if (null !== $redirect) {
            $redirect = $this->uri->urlDecode($redirect);
        } else {
            $redirect = '/admin';
        }
        
        $this->session->unset_userdata('user_state');

        return redirect($redirect);
    }
}
