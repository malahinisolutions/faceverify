<?php

defined('BASEPATH') OR exit('No direct script access allowed');



/**
 * Keys Controller
 * This is a basic Key Management REST controller to make and delete keys
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Key extends Api_Controller {

   function __construct()
    {
        // Construct the parent class
        parent::__construct();
		$this->load->model('tank_auth/users');
    }

    /**
     * Insert a key into the database
     *
     * @access public
     * @return void
     */
    public function generate_key_post()
    {
		if(count($this->post()) > 1 || count($this->post()) != '1')
		{
			$this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
		}
		$user_id=$this->post('user_id');
		if (!isset($user_id) && empty($user_id))
		{
			$this->response(array('error'=>array('user_id'=>'The User ID field is required.')), REST_Controller::HTTP_BAD_REQUEST);
		}
		$level = 1;
        $ignore_limits = 1;
		$this->form_validation->set_data($this->post());
		$this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|greater_than[0],is_natural_no_zero');
		if ($this->form_validation->run()) {
		if($this->_key_exist_userid($user_id))
		{
			$keydata=$this->_get_key_user_id($user_id);
			$key=$keydata->key;
			if ($this->_update_key($key, ['user_id'=>$user_id,'level' => $level, 'ignore_limits' => $ignore_limits]))
			{
			   $this->set_response(array('message'=>'Authentication key successfully updated','authentication'=>$key) , REST_Controller::HTTP_OK);
			}
		}else{
			// Build a new key
			$key = $this->_generate_key();
			// Insert the new key
			if ($this->_insert_key($key, ['user_id'=>$user_id,'level' => $level, 'ignore_limits' => $ignore_limits]))
			{
			   $this->set_response(array('message'=>'Authentication key successfully created','authentication'=>$key) , REST_Controller::HTTP_OK);
			}
		}
		}else{
				$this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
				}
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
/* End of file Key.php */
/* Location: ./application/controllers/Key.php */
