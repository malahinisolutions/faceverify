<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Accountactivation extends Front_Controller
  {
    function __construct()
      {
        parent::__construct();

        $this->load->library('tank_auth');
        $this->lang->load('tank_auth');
      }

    function index()
      {

            redirect('/');

      }


    /**
     * Activate user account.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function activate()
      {
        $user_id       = $this->uri->segment(3);
        $new_email_key = $this->uri->segment(4);
        // Activate user
        if ($this->tank_auth->activate_user($user_id, $new_email_key)) // success
          {
            //$this->tank_auth->logout();
            $this->_show_message($this->lang->line('auth_message_activation_completed'));

          }
        else // fail
          {
            $this->_show_message($this->lang->line('auth_message_activation_failed'));
          }
      }

    /**
     * Replace user email with a new one.
     * User is verified by user_id and authentication code in the URL.
     * Can be called by clicking on link in mail.
     *
     * @return void
     */
    function reset_email()
      {
        $user_id       = $this->uri->segment(3);
        $new_email_key = $this->uri->segment(4);

        // Reset email
        if ($this->tank_auth->activate_new_email($user_id, $new_email_key)) // success
          {
            //$this->tank_auth->logout();
            $this->_show_message($this->lang->line('auth_message_new_email_activated'));

          }
        else // fail
          {
            $this->_show_message($this->lang->line('auth_message_new_email_failed'));
          }
      }

    /**
     * Show info message
     *
     * @param	string
     * @return	void
     */
    function _show_message($message)
      {

        $this->session->set_flashdata('message', $message);
        redirect('');
      }


    /**
     * Send activation email again, to the same or new email address
     *
     * @return void
     */
    function send_again()
      {
        if (!$this->tank_auth->is_logged_in(FALSE)) // not logged in or activated
          {
            redirect('login');

          }
        else
          {
            $this->form_validation->set_rules('email', 'Email', 'trim|required|xss_clean|valid_email');

            $data['errors'] = array();

            if ($this->form_validation->run()) // validation ok
              {
                if (!is_null($data = $this->tank_auth->change_email($this->form_validation->set_value('email')))) // success
                  {

                    $data['site_name']         = $this->config->item('website_name', 'tank_auth');
                    $data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

                    $this->tank_auth->send_email('activate', $data['email'], $data);

                    $this->_show_message(sprintf($this->lang->line('auth_message_activation_email_sent'), $data['email']));

                  }
                else
                  {
                    $errors = $this->tank_auth->get_error_message();
                    foreach ($errors as $k => $v)
                        $data['errors'][$k] = $this->lang->line($v);
                  }
              }
            $this->view('send_again_view', $data);
          }
      }






  }

/* End of file Accountactivation.php */
/* Location: ./application/controllers/Accountactivation.php */
