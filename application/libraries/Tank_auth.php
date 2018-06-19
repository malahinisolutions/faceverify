<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

require_once('phpass-0.1/PasswordHash.php');

define('STATUS_ACTIVATED', '1');
define('STATUS_NOT_ACTIVATED', '0');

/**
 * Tank_auth
 *
 * Authentication library for Code Igniter.
 *
 * @package		Tank_auth
 * @author		Ilya Konyukhov (http://konyukhov.com/soft/)
 * @version		1.0.9
 * @based on	DX Auth by Dexcell (http://dexcell.shinsengumiteam.com/dx_auth)
 * @license		MIT License Copyright (c) 2008 Erick Hartanto
 */
class Tank_auth
{
	private $error = array();

	function __construct()
	{
		$this->ci =& get_instance();

		$this->ci->load->config('tank_auth', TRUE);

		$this->ci->load->library('session');
		$this->ci->load->database();
		$this->ci->load->model('tank_auth/users');
		$this->ci->load->helper(array('form', 'url','security','date'));
		$this->ci->lang->load('rest_controller');
		// Try to autologin
		$this->autologin();
	}

	/**
	 * Login user on the site. Return TRUE if login is successful
	 * (user exists and activated, password is correct), otherwise FALSE.
	 *
	 * @param	string	(username or email or both depending on settings in config file)
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function login($login, $password, $remember, $login_by_username, $login_by_email)
	{
		if ((strlen($login) > 0) AND (strlen($password) > 0)) {

			// Which function to use to login (based on config)
			if ($login_by_email  AND $login_by_username) {
				$get_user_func = 'get_user_by_login';
			} else if ($login_by_username) {
				$get_user_func = 'get_user_by_username';
			} else {
				$get_user_func = 'get_user_by_email';
			}

			if (!is_null($user = $this->ci->users->$get_user_func($login))) {	// login ok

				// Does password match hash in database?
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
						$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
				if ($hasher->CheckPassword($password, $user->password)) {		// password ok

					if ($user->banned == 1) {									// fail - banned
						$this->error = array('banned' => $user->ban_reason);

					} else {
						if ($user->activated == 0) {							// fail - not activated
							$this->error = array('not_activated' => $this->ci->lang->line('auth_message_not_activated'));
							$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'name'	=> $user->name,
								'status'	=> ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,));
						} else {												// success
							if ($remember) {
								$this->create_autologin($user->id);
							}
							$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'name'	=> $user->name,
								'status'	=> ($user->activated == 1) ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED,));

							$this->clear_login_attempts($login);

							$this->ci->users->update_login_info(
									$user->id,
									$this->ci->config->item('login_record_ip', 'tank_auth'),
									$this->ci->config->item('login_record_time', 'tank_auth'));
							return TRUE;
						}
					}
				} else {														// fail - wrong password
					$this->increase_login_attempt($login);
					$this->error = array('password' => $this->ci->lang->line('auth_incorrect_password'));
				}
			} else {															// fail - wrong login
				$this->increase_login_attempt($login);
				$this->error = array('login' => $this->ci->lang->line('auth_incorrect_login'));
			}
		}
		return FALSE;
	}

	/**
	 * Logout user from the site
	 *
	 * @return	void
	 */
	function logout()
	{
		$this->delete_autologin();

		// See http://codeigniter.com/forums/viewreply/662369/ as the reason for the next line
		$this->ci->session->set_userdata(array('user_id' => '', 'username' => '', 'status' => ''));

		$this->ci->session->sess_destroy();
	}

	/**
	 * Check if user logged in. Also test if user is activated or not.
	 *
	 * @param	bool
	 * @return	bool
	 */
	function is_logged_in($activated = TRUE)
	{
		return $this->ci->session->userdata('status') === ($activated ? STATUS_ACTIVATED : STATUS_NOT_ACTIVATED);
	}



	/**
	 * Get user_id
	 *
	 * @return	string
	 */
	function get_user_id()
	{
		return $this->ci->session->userdata('user_id');
	}

	/**
	 * Get username
	 *
	 * @return	string
	 */
	function get_username()
	{
		return $this->ci->session->userdata('username');
	}

	/**
	 * Create new user on the site and return some data about it:
	 * user_id, username, password, email, new_email_key (if any).
	 *
	 * @param	string
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	array
	 */
	function create_user($username,$first_name,$last_name, $email, $password, $email_activation)
	{
		 $profile_data='';$cleanname='';
			// Hash password using phpass
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			$hashed_password = $hasher->HashPassword($password);
			$cleanfirstname = preg_replace("/[^A-Za-z0-9]+/", " ", $first_name);
			$cleanlastname = preg_replace("/[^A-Za-z0-9]+/", " ", $last_name);
			$cleanname = empty($last_name) ? $cleanfirstname : $cleanfirstname.' '.$cleanlastname;
			$data = array(
				'username'	=> $email,
				'name'	=> $cleanname,
				'password'	=> $hashed_password,
				'email'		=> $email,
				'last_ip'	=> $this->ci->input->ip_address(),
			);

			if ($email_activation) {
				$data['new_email_key'] = md5(rand().microtime());
			}
			if ((strlen($first_name) > 0) OR (strlen($last_name) > 0)) {
			$profile_data=array('first_name'=>$first_name,'last_name'=>$last_name);

			}else{
			$profile_data=array('first_name'=> '','last_name'=>'');
			}
			if (!is_null($res = $this->ci->users->create_user($data,$profile_data, !$email_activation))) {
				$data['user_id'] = $res['user_id'];
				$data['password'] = $password;
				unset($data['last_ip']);
				return $data;
			}

		return NULL;
	}

	/**
	 * Check if username available for registering.
	 * Can be called for instant form validation.
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		return ((strlen($username) > 0) AND $this->ci->users->is_username_available($username));
	}

	/**
	 * Check if email available for registering.
	 * Can be called for instant form validation.
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		return ((strlen($email) > 0) AND $this->ci->users->is_email_available($email));
	}

	/**
	 * Change email for activation and return some data about user:
	 * user_id, username, email, new_email_key.
	 * Can be called for not activated users only.
	 *
	 * @param	string
	 * @return	array
	 */
	function change_email($email)
	{
		$user_id = $this->ci->session->userdata('user_id');

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, FALSE))) {

			$data = array(
				'user_id'	=> $user_id,
				'username'	=> $user->username,
				'email'		=> $email,
			);
			if (strtolower($user->email) == strtolower($email)) {		// leave activation key as is
				$data['new_email_key'] = $user->new_email_key;
				return $data;

			} elseif ($this->ci->users->is_email_available($email)) {
				$data['new_email_key'] = md5(rand().microtime());
				$this->ci->users->set_new_email($user_id, $user->username, $data['new_email_key'], FALSE);
				return $data;

			} else {
				$this->error = array('email' => $this->ci->lang->line('auth_email_in_use'));
			}
		}else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_userid'));
		}
		return NULL;
	}

	/**
	 * Activate user using given key
	 *
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email = TRUE)
	{
		$this->ci->users->purge_na($this->ci->config->item('email_activation_expire', 'tank_auth'));

		if ((strlen($user_id) > 0) AND (strlen($activation_key) > 0)) {
			return $this->ci->users->activate_user($user_id, $activation_key, $activate_by_email);
		}
		return FALSE;
	}

	/**
	 * Set new password key for user and return some data about user:
	 * user_id, username, email, new_pass_key.
	 * The password key can be used to verify user when resetting his/her password.
	 *
	 * @param	string
	 * @return	array
	 */
	function forgot_password($login)
	{
		if (strlen($login) > 0) {
			if (!is_null($user = $this->ci->users->get_user_by_login($login))) {
				$length=1;
				$str=substr(str_shuffle(str_repeat($x='ABCDEFGHJKLMNPQRSTUVWXY', ceil($length/strlen($x)) )),1,$length);
				$new_pass_key=$str.rand(9999,99999);
				$data = array(
					'user_id'		=> $user->id,
					'username'		=> $user->username,
					'email'			=> $user->email,
					'new_pass_key'	=> $new_pass_key,
				);

				$this->ci->users->set_password_key($user->id, $data['new_pass_key']);
				return $data;

			} else {
				$this->error = array('login' => $this->ci->lang->line('auth_incorrect_email_or_username'));
			}
		}
		return NULL;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function can_reset_password($user_id, $new_pass_key)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_pass_key) > 0)) {
			return $this->ci->users->can_reset_password(
				$user_id,
				$new_pass_key,
				$this->ci->config->item('forgot_password_expire', 'tank_auth'));
		}
		return FALSE;
	}

	/**
	 * Replace user password (forgotten) with a new one (set by user)
	 * and return some data about it: user_id, username, new_password, email.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function reset_password($new_pass_key, $new_password)
	{
		if ((strlen($new_pass_key) > 0) AND (strlen($new_password) > 0)) {

			if (!is_null($user = $this->ci->users->get_user_by_new_pass_key($new_pass_key, TRUE))) {

				// Hash password using phpass
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
						$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
				$hashed_password = $hasher->HashPassword($new_password);

				if ($this->ci->users->reset_password(
						$user->id,
						$hashed_password,
						$new_pass_key)) {	// success

					// Clear all user's autologins
					$this->ci->load->model('tank_auth/user_autologin');
					$this->ci->user_autologin->clear($user->id);

					return array(
						'user_id'		=> $user->id,
						'username'		=> $user->username,
						'email'			=> $user->email,
						'new_password'	=> $new_password,
					);
				}
			}
		}
		return NULL;
	}

	/**
	 * Change user password (only when user is logged in)
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id,$email,$old_pass, $new_pass)
	{

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {
			if (!is_null($user = $this->ci->users->get_user_by_email($email))) {
			// Check if old password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($old_pass, $user->password)) {			// success

				// Hash new password using phpass
				$hashed_password = $hasher->HashPassword($new_pass);

				// Replace old password with new one
				$this->ci->users->change_password($user_id, $hashed_password);
				return TRUE;

			} else {															// fail
				$this->error = array('old_password' => $this->ci->lang->line('auth_incorrect_password'));
			}
		}else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_email_or_username'));
			}
		}else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_userid1'));
		}
		return FALSE;
	}

	function change_password_profile($user_id,$new_pass)
	{

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));

				// Hash new password using phpass
				$hashed_password = $hasher->HashPassword($new_pass);

				// Replace old password with new one
				$this->ci->users->change_password($user_id, $hashed_password);
				return TRUE;


		}else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_userid1'));
		}
		return FALSE;
	}

	/**
	 * Change user email (only when user is logged in) and return some data about user:
	 * user_id, username, new_email, new_email_key.
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	string
	 * @param	string
	 * @return	array
	 */
	function set_new_email($user_id,$new_email, $password)
	{

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {

			// Check if password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($password, $user->password)) {			// success

				$data = array(
					'user_id'	=> $user_id,
					'username'	=> $user->username,
					'new_email'	=> $new_email,
				);

				if ($user->email == $new_email) {
					$this->error = array('email' => $this->ci->lang->line('auth_current_email'));

				} elseif ($user->new_email == $new_email) {		// leave email key as is
					$data['new_email_key'] = $user->new_email_key;
					return $data;

				} elseif ($this->ci->users->is_email_available($new_email)) {
					$data['new_email_key'] = md5(rand().microtime());
					$this->ci->users->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
					return $data;

				} else {
					$this->error = array('email' => $this->ci->lang->line('auth_email_in_use'));
				}
			} else {															// fail
				$this->error = array('password' => $this->ci->lang->line('auth_incorrect_password'));
			}
		}else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_userid1'));
		}
		return NULL;
	}


	function set_new_email_profile($user_id,$new_email)
	{

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {


				$data = array(
					'user_id'	=> $user_id,
					'username'	=> $user->username,
					'new_email'	=> $new_email,
				);

				if ($user->email == $new_email) {
					$this->error = array('email' => $this->ci->lang->line('auth_current_email'));

				} elseif ($user->new_email == $new_email) {		// leave email key as is
					$data['new_email_key'] = $user->new_email_key;
					return $data;

				} elseif ($this->ci->users->is_email_available($new_email)) {
					$data['new_email_key'] = md5(rand().microtime());
					$this->ci->users->set_new_email($user_id, $new_email, $data['new_email_key'], TRUE);
					return $data;

				} else {
					$this->error = array('email' => $this->ci->lang->line('auth_email_in_use'));
				}

		}else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_userid1'));
		}
		return NULL;
	}

	/**
	 * Activate new email, if email activation key is valid.
	 *
	 * @param	string
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		if ((strlen($user_id) > 0) AND (strlen($new_email_key) > 0)) {
			return $this->ci->users->activate_new_email(
					$user_id,
					$new_email_key);
		}
		return FALSE;
	}

	/**
	 * Delete user from the site (only when user is logged in)
	 *
	 * @param	string
	 * @return	bool
	 */
	function delete_user($user_id,$email,$password)
	{

		if (!is_null($user = $this->ci->users->get_user_by_id($user_id, TRUE))) {
			if (!is_null($user = $this->ci->users->get_user_by_email($email))) {
			// Check if password correct
			$hasher = new PasswordHash(
					$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
					$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
			if ($hasher->CheckPassword($password, $user->password)) {			// success

				$this->ci->users->delete_user($user_id,$email);

				return TRUE;

			} else {															// fail
				$this->error = array('password' => $this->ci->lang->line('auth_incorrect_password'));
			}

		  }else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_email_or_username'));
			}
		}else{
			$this->error = array('password' => $this->ci->lang->line('auth_incorrect_userid1'));
		}
		return FALSE;
	}

	/**
	 * Get error message.
	 * Can be invoked after any failed operation such as login or register.
	 *
	 * @return	string
	 */
	function get_error_message()
	{
		return $this->error;
	}

	/**
	 * Save data for user's autologin
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_autologin($user_id)
	{
		$this->ci->load->helper('cookie');
		$key = substr(md5(uniqid(rand().get_cookie($this->ci->config->item('sess_cookie_name')))), 0, 16);

		$this->ci->load->model('tank_auth/user_autologin');
		$this->ci->user_autologin->purge($user_id);

		if ($this->ci->user_autologin->set($user_id, md5($key))) {
			set_cookie(array(
					'name' 		=> $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
					'value'		=> serialize(array('user_id' => $user_id, 'key' => $key)),
					'expire'	=> $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
			));
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Clear user's autologin data
	 *
	 * @return	void
	 */
	private function delete_autologin()
	{
		$this->ci->load->helper('cookie');
		if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE)) {

			$data = unserialize($cookie);

			$this->ci->load->model('tank_auth/user_autologin');
			$this->ci->user_autologin->delete($data['user_id'], md5($data['key']));

			delete_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'));
		}
	}

	/**
	 * Login user automatically if he/she provides correct autologin verification
	 *
	 * @return	void
	 */
	private function autologin()
	{
		if (!$this->is_logged_in() AND !$this->is_logged_in(FALSE)) {			// not logged in (as any user)

			$this->ci->load->helper('cookie');
			if ($cookie = get_cookie($this->ci->config->item('autologin_cookie_name', 'tank_auth'), TRUE)) {

				$data = unserialize($cookie);

				if (isset($data['key']) AND isset($data['user_id'])) {

					$this->ci->load->model('tank_auth/user_autologin');
					if (!is_null($user = $this->ci->user_autologin->get($data['user_id'], md5($data['key'])))) {

						// Login user
						$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->username,
								'status'	=> STATUS_ACTIVATED,
						));

						// Renew users cookie to prevent it from expiring
						set_cookie(array(
								'name' 		=> $this->ci->config->item('autologin_cookie_name', 'tank_auth'),
								'value'		=> $cookie,
								'expire'	=> $this->ci->config->item('autologin_cookie_life', 'tank_auth'),
						));

						$this->ci->users->update_login_info(
								$user->id,
								$this->ci->config->item('login_record_ip', 'tank_auth'),
								$this->ci->config->item('login_record_time', 'tank_auth'));
						return TRUE;
					}
				}
			}
		}
		return FALSE;
	}

	/**
	 * Check if login attempts exceeded max login attempts (specified in config)
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_max_login_attempts_exceeded($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			$this->ci->load->model('tank_auth/login_attempts');
			return $this->ci->login_attempts->get_attempts_num($this->ci->input->ip_address(), $login)
					>= $this->ci->config->item('login_max_attempts', 'tank_auth');
		}
		return FALSE;
	}

	/**
	 * Increase number of attempts for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function increase_login_attempt($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			if (!$this->is_max_login_attempts_exceeded($login)) {
				$this->ci->load->model('tank_auth/login_attempts');
				$this->ci->login_attempts->increase_attempt($this->ci->input->ip_address(), $login);
			}
		}
	}

	/**
	 * Clear all attempt records for given IP-address and login
	 * (if attempts to login is being counted)
	 *
	 * @param	string
	 * @return	void
	 */
	private function clear_login_attempts($login)
	{
		if ($this->ci->config->item('login_count_attempts', 'tank_auth')) {
			$this->ci->load->model('tank_auth/login_attempts');
			$this->ci->login_attempts->clear_attempts(
					$this->ci->input->ip_address(),
					$login,
					$this->ci->config->item('login_attempt_expire', 'tank_auth'));
		}
	}

	/**
	 * update data for user's profile
	 *
	 * @param	int
	 * @param	array()
	 * @return	bool
	 */
	public function update_profile($user_id,$data)
	{
		if (!is_null($user = $this->ci->users->get_user_by_ids($user_id))) {
			if(is_array($data) && count($data)>0)
			{
				if ($this->ci->users->update_user_profile($user_id,$data)) {
					$cleanfirstname = preg_replace("/[^A-Za-z0-9]+/", " ", $data['first_name']);
					$cleanlastname = preg_replace("/[^A-Za-z0-9]+/", " ", $data['last_name']);
					$cleanname = empty($data['last_name']) ? $cleanfirstname : $cleanfirstname.' '.$cleanlastname;
					$name=array('name'=>$cleanname);
					$this->ci->users->update_users_name($user_id,$name);
					return TRUE;
					}
			}else{
				$this->error = array('error' => 'The invalid fields provided.');
			}
		}else{
			$this->error = array('profile_error' => $this->ci->lang->line('auth_incorrect_userid1'));
		}
		 return FALSE;
	}

	/**
	 * Get user profile details data for user's profile
	 *
	 * @param	int
	 * @param	array()
	 * @return	bool
	 */
	public function profile_details($user_id)
	{
		if (!is_null($user = $this->ci->users->get_user_by_ids($user_id))) {
			$data='';
			if ($data=$this->ci->users->get_profile_details($user_id)) {
				return $data;
			}else{
				$this->error = array('profile_error' => $this->ci->lang->line('auth_incorrect_profile'));
				}

		}else{
			$this->error = array('profile_error' => $this->ci->lang->line('auth_incorrect_userid1'));
		}
		 return FALSE;
	}


	/**
	 * Send email message of given type (activate, forgot_password, etc.)
	 *
	 * @param	string
	 * @param	string
	 * @param	array
	 * @return	void
	 */
	function send_email($type, $email, &$data)
	{
		include(BASEPATH."class.phpmailer.php");
        $this->email= new PHPMailer;
		$this->email->IsSMTP();
		$this->email->SMTPDebug = 1;
		$this->email->Host = 'smtp.googlemail.com';
		$this->email->Port = '465';
		$this->email->SMTPAuth = true;
		$this->email->SMTPSecure = 'ssl';
		$this->email->isHTML(true);
		$this->email->Username = 'malahinisolutions@gmail.com';
		$this->email->Password = 'malahinigmail1';
		$this->email->addReplyTo('malahinisolutions@gmail.com', $this->ci->config->item('website_name', 'tank_auth'));
		$this->email->setFrom('malahinisolutions@gmail.com',$this->ci->config->item('website_name', 'tank_auth'));

        $this->email->AddAddress($email);
        $this->email->Subject=(sprintf($this->ci->lang->line('auth_subject_' . $type), $this->ci->config->item('website_name', 'tank_auth')));
        $this->email->Body=($this->ci->load->view('email/' . $type . '-html', $data, TRUE));
        $this->email->AltBody=($this->ci->load->view('email/' . $type . '-txt', $data, TRUE));
        $this->email->clearAttachments();
		$this->email->send();
		log_message('error', $type.' Email send to '.$email);
	}

	function adminlogin($login, $password)
	{
		if ((strlen($login) > 0) AND (strlen($password) > 0)) {

			if (!is_null($user = $this->ci->users->get_adminuser_by_email($login))) {	// login ok

				// Does password match hash in database?
				$hasher = new PasswordHash(
						$this->ci->config->item('phpass_hash_strength', 'tank_auth'),
						$this->ci->config->item('phpass_hash_portable', 'tank_auth'));
				if ($hasher->CheckPassword($password, $user->password)) {		// password ok

					 		$this->ci->session->set_userdata(array(
								'user_id'	=> $user->id,
								'username'	=> $user->name,
								'email'	=> $user->email,
								'status'	=> STATUS_ACTIVATED,));

							$this->ci->users->admin_update_login_info(
									$user->id,
									$this->ci->config->item('login_record_ip', 'tank_auth'),
									$this->ci->config->item('login_record_time', 'tank_auth'));
							return TRUE;

				} else {														// fail - wrong password
					$this->error = array('password' => $this->ci->lang->line('auth_incorrect_password'));
				}
			} else {															// fail - wrong login

				$this->error = array('login' => $this->ci->lang->line('auth_incorrect_login'));
			}
		}
		return FALSE;
	}

	function is_admin_login()
	{
		if (!is_null($user = $this->ci->users->get_adminuser_by_email($this->ci->session->userdata('email')))) {
			return TRUE;
		}else{
			return FALSE;
		}
	}

}

/* End of file Tank_auth.php */
/* Location: ./application/libraries/Tank_auth.php */
