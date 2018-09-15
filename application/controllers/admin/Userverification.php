<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Userverification extends Admin_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url', 'security','date','text',));
		$this->load->library(array('form_validation','pagination'));
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->load->model('tank_auth/users');
	}

  public function index($sort_by='first_name',$sort_order='DESC', $page=0, $rows=10,$search=0)
  {
		if (!$this->tank_auth->is_admin_login()) {
		 redirect('admin/login');
	 }
	 if($this->input->post('search'))
			{
				$search=$this->input->post('search');
			}else{ $search=''; }
			$data['total']=$this->users->count_all_users();//print_r($this->users->count_all_users());die;
			$config['base_url']			= site_url('admin/userverification/index/'.$sort_by.'/'.$sort_order);
			$config['total_rows']		= $data['total'];
			$config['per_page']			= $rows;
			$config['uri_segment']		= 6;
			$config['first_link']		= 'First';
			$config['first_tag_open']	= '<li class="page-item">';
			$config['first_tag_close']	= '</li>';
			$config['last_link']		= 'Last';
			$config['last_tag_open']	= '<li class="page-item">';
			$config['last_tag_close']	= '</li>';

			$config['full_tag_open']	= '<ul class="pagination">';
			$config['full_tag_close']	= '</ul>';
			$config['cur_tag_open']		= '<li class="page-item active"><a class="page-link" href="#">';
			$config['cur_tag_close']	= '</a></li>';

			$config['num_tag_open']		= '<li class="page-item">';
			$config['num_tag_close']	= '</li>';

			$config['prev_link']		= 'Previous';
			$config['prev_tag_open']	= '<li class="page-item">';
			$config['prev_tag_close']	= '</li>';

			$config['next_link']		= 'Next';
			$config['next_tag_open']	= '<li class="page-item">';
			$config['next_tag_close']	= '</li>';
			$page = ($this->uri->segment(6)) ? $this->uri->segment(6) : 0;
			$this->pagination->initialize($config);
			$data['search']	= $search;
			$data['sort_by']	= $sort_by;
			$data['sort_order']	= $sort_order;
			$data['alladmins']	= $this->users->get_all_users_details($sort_by, $sort_order,$config['per_page'],$page,$search);
    $data['page_title']='User Verification';
		$this->view('admin/userverification_view',$data);
  }


	function edit($id){
  $data['page_title']	= 'User Verification Details';
  $data['user_id'] =$id;
  $data['first_name']='';
  $data['last_name']='';
  $data['email']='';
	$data['birth_date']='';
	$data['country']='';
	$data['state']='';
	$data['city']='';
	$data['zipcode']='';
	$data['home_address']='';
	$data['mailing_address']='';
	$data['status']='';
	$data['document_type']='';
	$data['document_path']='';
	$data['user_image']='';
	$data['comment']='';
	$data['verified_by']='';
	$data['verification_at']='';

  $profiledata = $this->users->get_profile_details($id);
	$userdata=$this->users->get_user_verifications_by_ids($id);
  $data['first_name'] = $profiledata->first_name;
  $data['last_name'] = $profiledata->last_name;
  $data['email'] = $profiledata->email;
	$data['birth_date'] = $profiledata->birth_date;
	$data['country'] = $profiledata->country;
	$data['state'] = $profiledata->state;
	$data['city'] = $profiledata->city;
	$data['zipcode'] = $profiledata->zipcode;
	$data['home_address'] = $profiledata->home_address;
	$data['mailing_address'] = $profiledata->mailing_address;
	$data['status']=$userdata->status;
	$data['document_type']=$userdata->document_type;
	$data['document_path']=$userdata->document_path;
	$data['user_image']=$userdata->user_image;
	$data['comment']=$userdata->comment;
	$data['verified_by']=$userdata->verified_by;
	$data['verification_at']=$userdata->verification_at;


  if($this->input->post('processed'))
  {

 	 $userData = array(
 					 'comment' => $this->input->post('comment'),
 					 'verified_by' => $this->session->userdata('email'),
 					 'verification_at'=>date('Y-m-d H:i:s'),
					 'status'=>'processed',
 					 );
					 $this->users->update_user_verifications($id,$userData);
   $this->session->set_flashdata('message', 'Information updated successfully');
  }
	if($this->input->post('cancelled')){
		$userData = array(
						 'comment' => $this->input->post('comment'),
						 'verified_by' => $this->session->userdata('email'),
						 'verification_at'=>date('Y-m-d H:i:s'),
					 'status'=>'cancelled',
						 );
						$this->users->update_user_verifications($id,$userData);
						$this->session->set_flashdata('message', 'Information updated successfully');
	}
  $this->view('admin/details_view.php',$data);
  }

}
