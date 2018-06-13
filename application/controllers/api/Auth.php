<?php if (!defined('BASEPATH')) exit('No direct script access allowed');


class Auth extends Api_Controller
{
	function __construct()
	{
		parent::__construct();


		$this->load->model('tank_auth/users');
	}

	public function index_get()
	{
		$this->login_post();
	}
	public function index_post()
	{
		$this->login_post();
	}

	/**
	 * Login user on the site
	 *
	 * @return void
	 */
	public function login_post()
	{
		if(count($this->post()) > 2 || count($this->post()) > '2')
		{
			$this->response(array('error'=>array('login'=>'The invalid fields provided.')), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$loginuser='';
		$login=$this->post('login');
		$password=$this->post('password');
		if (!isset($login) && !isset($password))
		{
			$this->response(array('error'=>array('login'=>'The email and password field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		 $this->form_validation->set_data($this->post());
			$data['login_by_username'] = ($this->config->item('login_by_username', 'tank_auth') AND
					$this->config->item('use_username', 'tank_auth'));
			$data['login_by_email'] = $this->config->item('login_by_email', 'tank_auth');
			if($data['login_by_email'])
			{
				//$this->form_validation->set_rules('login', 'Login', 'trim|required|valid_email|callback_email_valid');
				$this->form_validation->set_rules('login', 'Login', 'trim|required|valid_email');
			}else{
				$this->form_validation->set_rules('login', 'Login', 'trim|required|xss_clean');
			}

			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean');

			// Get login for counting attempts to login
			if ($this->config->item('login_count_attempts', 'tank_auth') AND
					($login = $this->post('login'))) {
				$login = $this->security->xss_clean($login);
			} else {
				$login = '';
			}


			$data['errors'] = array();

			if ($this->form_validation->run()) {
				if ($this->tank_auth->login(
						$this->form_validation->set_value('login'),
						$this->form_validation->set_value('password'),
						FALSE,$data['login_by_username'],$data['login_by_email']))
				{
					$loginuser=$this->users->get_loginuser_by_email($this->form_validation->set_value('login'));
					$authorization='Basic '.base64_encode($this->form_validation->set_value('login').':'.$this->form_validation->set_value('password'));
					if(!is_null($key=$this->generate_key($loginuser->id)))
					{
						$this->set_response(array('loginuserdetails'=>$loginuser,'authentication'=>$key,'authorization'=>$authorization), REST_Controller::HTTP_OK);
					}else{
						$this->set_response(array('loginuserdetails'=>$loginuser,'keyerror'=>'Could not save the key','authorization'=>$authorization), REST_Controller::HTTP_OK); // OK (200) being the HTTP response code
					}

				} else {
					$errors = $this->tank_auth->get_error_message();
					if (isset($errors['banned'])){
						$this->set_response(array('error' => array('login'=>$this->lang->line('auth_message_banned'))), REST_Controller::HTTP_NOT_FOUND); // NOT_FOUND (404) being the HTTP response code
					} elseif (isset($errors['not_activated'])) {
						$this->set_response(array('error' => array('login'=>$this->lang->line('auth_message_not_activated'))), REST_Controller::HTTP_NOT_FOUND);

					} else {
						$this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
					}
				}
			}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}

	}

	public function email_valid($str)
	{
		$regex = "/^\w+[\+\.\w-]*@([\w-]+\.)*\w+[\w-]*\.([a-z]{2,4}|\d+)$/i";
		if(!preg_match($regex, $str))
		{
			$this->set_response(array('email_valid' => $this->lang->line('auth_message_valid_email')), REST_Controller::HTTP_NOT_FOUND);
			return FALSE;
		} else {
			return TRUE;
		}
	}

	/**
	 * Logout user
	 *
	 * @return void
	 */
	public function logout()
	{
		$this->tank_auth->logout();
		$this->set_response(array('message'=>$this->lang->line('auth_message_logged_out')), REST_Controller::HTTP_OK);
	}

	/**
	 * Register user on the site
	 *
	 * @return void
	 */
	public function register_post()
	{ 
		if(count($this->post()) > 4 || count($this->post()) != '4')
		{
			$this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$first_name=$this->post('first_name');
		$last_name=$this->post('last_name');
		$email=$this->post('email');

		$password=$this->post('password');
		if (!isset($first_name) && empty($first_name))
		{
			$this->response(array('error'=>array('first_name'=>'The first name field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($last_name) && empty($last_name))
		{
			$this->response(array('error'=>array('last_name'=>'The last name field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($email) && empty($email))
		{
			$this->response(array('error'=>array('email'=>'The email field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($password) && empty($password))
		{
			$this->response(array('error'=>array('password'=>'The password field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}

		if (!$this->config->item('allow_registration', 'tank_auth')) {
			$this->set_response(array('message'=>$this->lang->line('auth_message_registration_disabled')), REST_Controller::HTTP_FORBIDDEN);
		} else {
			$this->form_validation->set_data($this->post());
			$use_username = $this->config->item('use_username', 'tank_auth');
			if ($use_username) {
				$this->form_validation->set_rules('username', 'Username', 'trim|required|xss_clean|min_length['.$this->config->item('username_min_length', 'tank_auth').']|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
			}
			$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean');
			$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|callback_check_email');
			$this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');

			$data['errors'] = array();

			$email_activation = $this->config->item('email_activation', 'tank_auth');

			if ($this->form_validation->run())
			{
				if (!is_null($data = $this->tank_auth->create_user(
						$use_username ? $this->form_validation->set_value('username') : '',
						$this->form_validation->set_value('first_name'),
						$this->form_validation->set_value('last_name'),
						$this->form_validation->set_value('email'),
						$this->form_validation->set_value('password'),
						$email_activation))) {									// success

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');
					if ($email_activation) {									// send "activate" email
						$data['activation_period'] = $this->config->item('email_activation_expire', 'tank_auth') / 3600;

						$this->tank_auth->send_email('activate', $data['email'], $data);

						unset($data['password']); // Clear password (just for any case)

						$this->set_response(array('message'=>$this->lang->line('auth_message_registration_completed_1')), REST_Controller::HTTP_OK);

					} else {
						if ($this->config->item('email_account_details', 'tank_auth')) {	// send "welcome" email

							$this->tank_auth->send_email('welcome', $data['email'], $data);
						}
						unset($data['password']); // Clear password (just for any case)

						$this->set_response(array('message'=>$this->lang->line('auth_message_registration_completed_2')), REST_Controller::HTTP_OK);
					}

				} else {
					$this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
				}
			}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}
		}
	}
	// use during registration
	public function check_email($email)
	{
		$email = $this->users->check_email_available($email);
		if ($email)
		{
			$this->form_validation->set_message('check_email', $this->lang->line('auth_email_in_use'));
			return FALSE;
		}
		else
		{
			return TRUE;
		}

	}


	/**
	 * Generate reset code (to change password) and send it to user
	 *
	 * @return void
	 */
	function forgot_password_post()
	{
		if(count($this->post()) > 1 || count($this->post()) != '1')
		{
			$this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$email=$this->post('email');
		if (!isset($email) && empty($email))
		{
			$this->response(array('error'=>array('email'=>'The email field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if(!$this->email_valid($email))
		{
			$this->set_response(array('message' => $this->lang->line('auth_incorrect_email')), REST_Controller::HTTP_NOT_FOUND);
		}
		 $this->form_validation->set_data($this->post());
		$this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email');

			$data['errors'] = array();

			if ($this->form_validation->run()) {
				if (!is_null($data = $this->tank_auth->forgot_password(
						$this->form_validation->set_value('email')))) {

					$data['site_name'] = $this->config->item('website_name', 'tank_auth');

					// Send email with password activation link
					$this->tank_auth->send_email('forgot_password', $data['email'], $data);
					$this->set_response(array('message'=>$this->lang->line('auth_message_new_password_sent') ), REST_Controller::HTTP_OK);

				} else {
					$this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
				}
			}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}

	}

	 /**
	 * Replace user password (forgotten) with a new one (set by user).
	 * User is verified by user_id and authentication code in the URL.
	 * Can be called by clicking on link in mail.
	 *
	 * @return void
	 */
	function reset_password_post()
	{
		if(count($this->post()) > 2 || count($this->post()) != '2')
		{
			$this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$new_pass_key=$this->post('new_pass_key');
		$new_password=$this->post('new_password');
		if (!isset($new_pass_key) && empty($new_pass_key))
		{
			$this->response(array('error'=>array('new_pass_key'=>'The new password field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		if (!isset($new_password) && empty($new_password))
		{
			$this->response(array('error'=>array('new_password'=>'The new password field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		$this->form_validation->set_data($this->post());
		$this->form_validation->set_rules('new_pass_key', 'New Password Key', 'trim|required|xss_clean');
		$this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|min_length['.$this->config->item('password_min_length', 'tank_auth').']|max_length['.$this->config->item('password_max_length', 'tank_auth').']|alpha_dash');


		$data['errors'] = array();

		if ($this->form_validation->run()) {								// validation ok
			if (!is_null($data = $this->tank_auth->reset_password(
					$this->form_validation->set_value('new_pass_key'),
					$this->form_validation->set_value('new_password')))) {	// success

				$data['site_name'] = $this->config->item('website_name', 'tank_auth');

				// Send email with new password
				$this->tank_auth->send_email('reset_password', $data['email'], $data);
				$this->set_response(array('message'=>$this->lang->line('auth_message_new_password_activated') ), REST_Controller::HTTP_OK);
			} else {														// fail
				$this->set_response(array('error' => array('new_pass_key'=>$this->lang->line('auth_message_new_password_failed'))), REST_Controller::HTTP_NOT_FOUND);
			}
		}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}

	}




	/**
     * Insert a key into the database
     *
     * @access public
     * @return void
     */
    public function generate_key($user_id)
    {
		$level = 1;
        $ignore_limits = 1;
		if($this->_key_exist_userid($user_id))
		{
			$keydata=$this->_get_key_user_id($user_id);
			$key=$keydata->key;
			if ($this->_update_key($key, ['user_id'=>$user_id,'level' => $level, 'ignore_limits' => $ignore_limits]))
			{
			   return $key;
			}
		}else{
			// Build a new key
			$key = $this->_generate_key();
			// Insert the new key
			if ($this->_insert_key($key, ['user_id'=>$user_id,'level' => $level, 'ignore_limits' => $ignore_limits]))
			{
			   return $key;
			}
		}
		return FALSE;
    }

/* Helper Methods */

    private function _generate_key()
    {
        do
        {
            // Generate a random salt
            $salt = base_convert(bin2hex($this->security->get_random_bytes(64)), 16, 36);

            // If an error occurred, then fall back to the previous method
            if ($salt === FALSE)
            {
                $salt = hash('sha256', time() . mt_rand());
            }

            $new_key = substr($salt, 0, config_item('rest_key_length'));
        }
        while ($this->_key_exists($new_key));

        return $new_key;
    }

    /* Private Data Methods */

    private function _get_key_user_id($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->get(config_item('rest_keys_table'))
            ->row();
    }

    private function _key_exists($key)
    {
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->count_all_results(config_item('rest_keys_table')) > 0;
    }

	private function _key_exist_userid($user_id)
    {
        return $this->db
            ->where('user_id', $user_id)
            ->count_all_results(config_item('rest_keys_table')) > 0;
    }

    private function _insert_key($key, $data)
    {
        $data[config_item('rest_key_column')] = $key;
        $data['date_created'] = function_exists('now') ? now() : time();

        return $this->db
            ->set($data)
            ->insert(config_item('rest_keys_table'));
    }

    private function _update_key($key, $data)
    {
		$data['date_created'] = function_exists('now') ? now() : time();
        return $this->db
            ->where(config_item('rest_key_column'), $key)
            ->update(config_item('rest_keys_table'), $data);
    }



}

/* End of file auth.php */
/* Location: ./application/controllers/auth.php */
