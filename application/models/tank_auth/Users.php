<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Users
 *
 * This model represents user authentication data. It operates the following tables:
 * - user account data,
 * - user profiles
 *
 * @package	Tank_auth
 * @author	Ilya Konyukhov (http://konyukhov.com/soft/)
 */
class Users extends CI_Model
{
	private $table_name			= 'users';			// user accounts
	private $profile_table_name	= 'user_profiles';	// user profiles
	private $user_verifications_table_name='user_verifications';
	function __construct()
	{
		parent::__construct();

		$ci =& get_instance();
		$ci->load->helper(array('date'));
		$this->table_name			= $ci->config->item('db_table_prefix', 'tank_auth').$this->table_name;
		$this->profile_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->profile_table_name;
		$this->user_verifications_table_name	= $ci->config->item('db_table_prefix', 'tank_auth').$this->user_verifications_table_name;
	}

	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_id($user_id, $activated)
	{
		$this->db->where('id', $user_id);
		$this->db->where('activated', $activated ? 1 : 0);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}



	/**
	 * Get user record by Id
	 *
	 * @param	int
	 * @param	bool
	 * @return	object
	 */
	function get_user_by_new_pass_key($new_pass_key, $activated)
	{
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('activated', $activated ? 1 : 0);

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by login (username or email)
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_login($login)
	{
		$this->db->where('LOWER(username)=', strtolower($login));
		$this->db->or_where('LOWER(email)=', strtolower($login));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by username
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_username($username)
	{
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_user_by_email($email)
	{
		$this->db->where('LOWER(email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Get user record by email and profile data
	 *
	 * @param	string
	 * @return	object
	 */
	function get_loginuser_by_email($email)
	{
		$this->db->select($this->table_name.'.id,'.
							$this->profile_table_name.'.first_name,'.
							$this->profile_table_name.'.last_name,'.
							$this->table_name.'.email,'.
							$this->profile_table_name.'.birth_date,'.
							$this->profile_table_name.'.country,'.
							$this->profile_table_name.'.state,'.
							$this->profile_table_name.'.city,'.
							$this->profile_table_name.'.zipcode,'.
							$this->profile_table_name.'.nationality,'.
							$this->profile_table_name.'.home_address,'.
							$this->profile_table_name.'.mailing_address,'.
							$this->table_name.'.created,'.
							$this->table_name.'.last_login,');

		$this->db->join($this->profile_table_name, $this->profile_table_name.'.user_id='.$this->table_name.'.id' );

		$this->db->where('LOWER(users.email)=', strtolower($email));
		$query = $this->db->get($this->table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Check if username available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_username_available($username)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(username)=', strtolower($username));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	/**
	 * Check if email available for registering
	 *
	 * @param	string
	 * @return	bool
	 */
	function is_email_available($email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 0;
	}

	function check_email_available($email)
	{
		$this->db->select('1', FALSE);
		$this->db->from($this->table_name);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->or_where('LOWER(new_email)=', strtolower($email));

		$query = $this->db->count_all_results();
		if ($query > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
	}

	/**
	 * Create new user record
	 *
	 * @param	array
	 * @param	bool
	 * @return	array
	 */
	function create_user($data,$profile_data, $activated = TRUE)
	{
		$data['created'] = date('Y-m-d H:i:s');
		$data['activated'] = $activated ? 1 : 0;

		if ($this->db->insert($this->table_name, $data)) {
			$user_id = $this->db->insert_id();
			$this->create_profile($user_id,$profile_data);
			return array('user_id' => $user_id);
		}
		return NULL;
	}

	/**
	 * Activate user if activation key is valid.
	 * Can be called for not activated users only.
	 *
	 * @param	int
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function activate_user($user_id, $activation_key, $activate_by_email)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		if ($activate_by_email) {
			$this->db->where('new_email_key', $activation_key);
		} else {
			$this->db->where('new_password_key', $activation_key);
		}
		$this->db->where('activated', 0);
		$query = $this->db->get($this->table_name);

		if ($query->num_rows() == 1) {

			$this->db->set('activated', 1);
			$this->db->set('new_email_key', NULL);
			$this->db->where('id', $user_id);
			$this->db->update($this->table_name);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Purge table of non-activated users
	 *
	 * @param	int
	 * @return	void
	 */
	function purge_na($expire_period = 172800)
	{
		$this->db->where('activated', 0);
		$this->db->where('UNIX_TIMESTAMP(created) <', time() - $expire_period);
		$this->db->delete($this->table_name);
	}

	/**
	 * Delete user record
	 *
	 * @param	int
	 * @return	bool
	 */
	function delete_user($user_id,$email)
	{
		$this->db->where('id', $user_id);
		$this->db->where('LOWER(email)=', strtolower($email));
		$this->db->delete($this->table_name);
		if ($this->db->affected_rows() > 0) {
			$this->delete_profile($user_id);
			return TRUE;
		}
		return FALSE;
	}

	/**
	 * Set new password key for user.
	 * This key can be used for authentication when resetting user's password.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function set_password_key($user_id, $new_pass_key)
	{
		$this->db->set('new_password_key', $new_pass_key);
		$this->db->set('new_password_requested', date('Y-m-d H:i:s'));
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Check if given password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	int
	 * @return	void
	 */
	function can_reset_password($user_id, $new_pass_key, $expire_period = 900)
	{
		$this->db->select('1', FALSE);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);
		$this->db->where('UNIX_TIMESTAMP(new_password_requested) >', time() - $expire_period);

		$query = $this->db->get($this->table_name);
		return $query->num_rows() == 1;
	}

	/**
	 * Change user password if password key is valid and user is authenticated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	int
	 * @return	bool
	 */
	function reset_password($user_id, $new_pass, $new_pass_key)
	{
		$this->db->set('password', $new_pass);
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_password_key', $new_pass_key);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Change user password
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function change_password($user_id, $new_pass)
	{
		$this->db->set('password', $new_pass);
		$this->db->where('id', $user_id);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Set new email for user (may be activated or not).
	 * The new email cannot be used for login or notification before it is activated.
	 *
	 * @param	int
	 * @param	string
	 * @param	string
	 * @param	bool
	 * @return	bool
	 */
	function set_new_email($user_id, $new_email, $new_email_key, $activated)
	{
		//$this->db->set($activated ? 'new_email' : 'email', $new_email);
		$this->db->set('new_email_key', $new_email_key);
		//$this->db->set('activated', $activated ? 0 : 1);
		$this->db->where('id', $user_id);
		//$this->db->where('activated', $activated ? 1 : 0);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Activate new email (replace old email with new one) if activation key is valid.
	 *
	 * @param	int
	 * @param	string
	 * @return	bool
	 */
	function activate_new_email($user_id, $new_email_key)
	{
		$this->db->set('email', 'new_email', FALSE);
		$this->db->set('activated', 1);
		$this->db->set('username', 'new_email', FALSE);
		$this->db->set('new_email', NULL);
		$this->db->set('new_email_key', NULL);
		$this->db->where('id', $user_id);
		$this->db->where('new_email_key', $new_email_key);

		$this->db->update($this->table_name);
		return $this->db->affected_rows() > 0;
	}

	/**
	 * Update user login info, such as IP-address or login time, and
	 * clear previously generated (but not activated) passwords.
	 *
	 * @param	int
	 * @param	bool
	 * @param	bool
	 * @return	void
	 */
	function update_login_info($user_id, $record_ip, $record_time)
	{
		$this->db->set('new_password_key', NULL);
		$this->db->set('new_password_requested', NULL);

		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update($this->table_name);
	}

	/**
	 * Ban user
	 *
	 * @param	int
	 * @param	string
	 * @return	void
	 */
	function ban_user($user_id, $reason = NULL)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 1,
			'ban_reason'	=> $reason,
		));
	}

	/**
	 * Unban user
	 *
	 * @param	int
	 * @return	void
	 */
	function unban_user($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->update($this->table_name, array(
			'banned'		=> 0,
			'ban_reason'	=> NULL,
		));
	}

	/**
	 * Create an empty profile for a new user
	 *
	 * @param	int
	 * @return	bool
	 */
	private function create_profile($user_id,$profile_data)
	{
		$this->db->set('user_id', $user_id);
		$this->db->set('first_name', $profile_data['first_name']);
		$this->db->set('last_name', $profile_data['last_name']);
		return $this->db->insert($this->profile_table_name);
	}

	/**
	 * Update profile for a existing user
	 *
	 * @param	int
	 * @return	bool
	 */
	public function update_user_profile($user_id,$data)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update($this->profile_table_name, $data);
		return true;
	}

	/**
	 * Update users for a existing user
	 *
	 * @param	string
	 * @return	bool
	 */
	public function update_users_name($user_id,$name)
	{
		$this->db->where('users.id', $user_id);
		$this->db->update($this->table_name, $name);
		return true;
	}



	/**
	 * Get profile details for a existing user
	 *
	 * @param	int
	 * @return	bool
	 */
	public function get_profile_details($user_id)
	{
		$this->db->select('user_profiles.user_id,users.id,user_profiles.first_name,user_profiles.last_name,users.email,user_profiles.birth_date,user_profiles.country,user_profiles.state,user_profiles.city,user_profiles.zipcode,user_profiles.nationality,user_profiles.home_address,user_profiles.mailing_address,users.created,users.modified,users.activated');
		$this->db->where('user_profiles.user_id', $user_id);
		$this->db->join($this->table_name, $this->table_name.'.id='.$this->profile_table_name.'.user_id' );
		$query = $this->db->get($this->profile_table_name);
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	/**
	 * Delete user profile
	 *
	 * @param	int
	 * @return	void
	 */
	function delete_profile($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->profile_table_name);
	}
	
	function delete_userdata($user_id)
	{
		$this->db->where('id', $user_id);
		$this->db->delete($this->table_name);
	}
	function delete_user_verifications($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete($this->user_verifications_table_name);
	}
	function delete_comment($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->delete('comment');
	}

	function get_user_by_ids($user_id)
	{
		$this->db->where('id', $user_id);
		$query = $this->db->get($this->table_name);
		if($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	public function insert_document($user_id,$data)
	{
		$data['created_at']=date('Y-m-d H:i:s');
		$data['status']='pending';
		$data['user_id']=$user_id;
		$this->db->insert('user_verifications', $data);
		return true;
	}

	public function upload_document($user_id,$data)
	{
		$data['updated_at']=date('Y-m-d H:i:s');
		$data['status']='pending';
		$this->db->where('user_id', $user_id);
		$this->db->update('user_verifications', $data);
		return true;
	}

	public function api_insert_document($user_id,$data)
	{
		$data['created_at']=date('Y-m-d H:i:s');
		$data['status']='none';
		$data['user_id']=$user_id;
		$this->db->insert('user_verifications', $data);
		return true;
	}

	public function api_upload_document($user_id,$data)
	{
		$data['updated_at']=date('Y-m-d H:i:s');
		$data['status']='none';
		$this->db->where('user_id', $user_id);
		$this->db->update('user_verifications', $data);
		return true;
	}

	function can_user_verifided($user_id)
	{

		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_verifications');
		if($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	function get_user_verification_status($user_id)
	{
		$this->db->select('status');
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_verifications');
		if($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	function get_user_verifications_by_ids($user_id)
	{
		$this->db->where('user_id', $user_id);
		$query = $this->db->get('user_verifications');
		if($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	public function update_user_verifications($user_id,$save)
	{
		$this->db->where('user_id', $user_id);
		$this->db->update('user_verifications', $save);
		return true;
	}
	public function insert_user_verifications_comment($save)
	{
		$this->db->insert('comment', $save);
		return true;
	}
	public function insert_user_old_comment($save)
	{
		$this->db->insert('oldcomment', $save);
		return true;
	}
	 
	function get_comment_by_id($user_id)
	{
		$this->db->where('user_id', $user_id);
		$this->db->order_by('created_at', 'ASC');		
		$query = $this->db->get('comment');
		return $query->result();
	}
	
	function insert_decline_user_details($data)
	{
		$this->db->insert('decline_users', $data);
		return $this->db->insert_id();
	}

	/**
	 * Get admin user record by email
	 *
	 * @param	string
	 * @return	object
	 */
	function get_adminuser_by_email($email)
	{
		$this->db->where('LOWER(email)=', strtolower($email));

		$query = $this->db->get('adminuser');
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}

	function admin_update_login_info($user_id, $record_ip, $record_time)
	{
		if ($record_ip)		$this->db->set('last_ip', $this->input->ip_address());
		if ($record_time)	$this->db->set('last_login', date('Y-m-d H:i:s'));

		$this->db->where('id', $user_id);
		$this->db->update('adminuser');
	}

	function count_all_users()
		{
			$this->db->join($this->table_name, $this->table_name.'.id='.$this->profile_table_name.'.user_id' );
			$this->db->join($this->user_verifications_table_name, $this->user_verifications_table_name.'.user_id='.$this->profile_table_name.'.user_id' );
			$result = $this->db->count_all_results($this->profile_table_name);
			return $result;
		}

		function get_all_users_details($order_by='first_name', $direction='DESC',$limit=0, $offset=0,$search=false)
		{
			$this->db->order_by($order_by, $direction);
			if($limit>0)
			{
				$this->db->limit($limit, $offset);
			}
			if($search)
			{
				$this->db->like('user_profiles.first_name', $search);
				$this->db->or_like('user_profiles.last_name', $search);
				$this->db->or_like('users.email', $search);
				//$this->db->or_like('users.username', $search);
			}
			$this->db->join($this->table_name, $this->table_name.'.id='.$this->profile_table_name.'.user_id' );
			$this->db->join($this->user_verifications_table_name, $this->user_verifications_table_name.'.user_id='.$this->profile_table_name.'.user_id' );

			$query = $this->db->get($this->profile_table_name);
			return $query->result();
		}
	function count_all_declineusers()
	{
		$result = $this->db->count_all_results('decline_users');
		return $result;
	}
	function get_all_declineusers($order_by='first_name', $direction='DESC',$limit=0, $offset=0,$search=false)
	{
		$this->db->order_by($order_by, $direction);
			if($limit>0)
			{
				$this->db->limit($limit, $offset);
			}
			if($search)
			{
				$this->db->like('first_name', $search);
				$this->db->or_like('last_name', $search);
				$this->db->or_like('email', $search);
			}
		$query = $this->db->get('decline_users');
		return $query->result();
	}
	function get_decline_user_profile_details($id)
	{
		$this->db->where('id', $id);
		$query = $this->db->get('decline_users');
		if ($query->num_rows() == 1) return $query->row();
		return NULL;
	}
	function get_decline_user_comment_by_id($id)
	{
		$this->db->where('user_id', $id);
		$this->db->order_by('created_at', 'ASC');		
		$query = $this->db->get('oldcomment');
		return $query->result();
	}







}

/* End of file users.php */
/* Location: ./application/models/auth/users.php */
