<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Capture_image extends Front_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url','security'));
		$this->load->library('form_validation');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('tank_auth/users');
	}

  public function index()
  {
		if (!$this->tank_auth->is_logged_in()) {									// logged in
			redirect('login');
		}
		$data['user_image']="";
		if(!is_null($datas=$this->users->get_user_verifications_by_ids($this->session->userdata('user_id'))))
		{
			if(!is_null($datas->user_image))
			{
				$data['user_image']=$datas->user_image;
			}else{
				$data['user_image']='0';
			}
		}
		$this->form_validation->set_rules('user_image', 'User Image', 'trim|xss_clean');
		if ($this->form_validation->run()) {
			$user_image=$this->form_validation->set_value('user_image');
			if($user_image!='0')
			{
				$this->session->set_flashdata('message', $this->lang->line('auth_message_user_image'));
				redirect('complete_verification');
			}
		}

    $data['name'] = empty($this->session->userdata('name')) ? $this->session->userdata('username') : $this->session->userdata('name');
    $data['page_title']='Capture Image';
		$this->view('capture_image_view',$data);
  }

	public function upload()
	{
		$filename='';
		$filename = 'pic_'.strtotime("now"). '.jpeg';
		$url = '';
		if(!is_null($datas=$this->users->get_user_verifications_by_ids($this->session->userdata('user_id'))))
		{
			if(file_exists(FCPATH."/upload/user/".$datas->user_image)){
			 unlink(FCPATH."/upload/user/".$datas->user_image);
		 }
		}
		if( move_uploaded_file($_FILES['webcam']['tmp_name'],'upload/user/'.$filename) ){
				$save['user_image']=$filename;
				$save['updated_at']=date('Y-m-d H:i:s');
				$this->users->update_user_verifications($this->session->userdata('user_id'),$save);
		}
		echo $filename;die;

	}
}
