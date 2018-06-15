<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Authenticate extends Front_Controller {

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
		if(is_null($datas = $this->users->can_user_verifided($this->session->userdata('user_id'))))
		{
			redirect('personal_information');
		}else{
			$data['status']=$datas->status;
			if(!$datas->document_type && !$datas->document_path)
			{
				redirect('upload_document');
			}
			if(!$datas->user_image)
			{
				redirect('capture_image');
			}
			if($datas->status=='pending')
			{
				redirect('verification');
			}
		}
    $data['name'] = empty($this->session->userdata('name')) ? $this->session->userdata('username') : $this->session->userdata('name');
		$data['page_title']='Authenticate User';
		$this->view('authenticate_view',$data);
  }

  public function upload()
	{
		$filename='';
		$filename = 'pic_'.strtotime("now"). '.jpeg';
		$url = '';
    $datas=$this->users->get_user_verifications_by_ids($this->session->userdata('user_id'));
    $oldimage=base_url('upload/user/').$datas->user_image;
		if( move_uploaded_file($_FILES['webcam']['tmp_name'],'upload/check_user/'.$filename) ){
				$newimage=base_url('upload/check_user/').$filename;
		}
		//print_r(array('oldimage'=>$oldimage,'newimage'=>$newimage));die;
    print_r(base64_encode(file_get_contents($oldimage)));die;
	}

}
