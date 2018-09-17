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
	
	$data['comments']=$this->users->get_comment_by_id($id);
	
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
	//$data['comment']=$comment->comment;
	//$data['verified_by']=$comment->verified_by;
	//$data['verification_at']=$comment->verification_at;


  if($this->input->post('processed'))
  {

 	 $userData = array(
 					 'comment' => $this->input->post('comment'),
 					 'verified_by' => $this->session->userdata('email'),
 					 'created_at'=>date('Y-m-d H:i:s'),
					 'verification_at'=>date('Y-m-d H:i:s'),
					 'user_id'=>$id,
					 'status'=>'processed',
 					 );
	$statusdata=array('status'=>'processed');
	$this->users->update_user_verifications($id,$statusdata);
	$this->users->insert_user_verifications_comment($userData);
   $this->session->set_flashdata('message', 'Information updated successfully');
   $data['comments']=$this->users->get_comment_by_id($id);
  }
	if($this->input->post('cancelled')){
		$userData = array(
						 'comment' => $this->input->post('comment'),
 					 'verified_by' => $this->session->userdata('email'),
 					 'created_at'=>date('Y-m-d H:i:s'),
					 'verification_at'=>date('Y-m-d H:i:s'),
					 'user_id'=>$id,
					 'status'=>'cancelled',
						 );
		$statusdata=array('status'=>'cancelled');
		$this->users->update_user_verifications($id,$statusdata);
		$this->users->insert_user_verifications_comment($userData);
		$this->session->set_flashdata('message', 'Information updated successfully');
		$data['comments']=$this->users->get_comment_by_id($id);
	}
  $this->view('admin/details_view.php',$data);
  }
  
  function delete($id)
  {
	  if(!is_null($datas=$this->users->get_user_verifications_by_ids($id)))
	  {
		 if(file_exists(FCPATH."upload/user/".$datas->user_image)){
			$old_user_image=FCPATH."upload/user/".$datas->user_image;
			$new_user_image=FCPATH."upload/decline_user/users/".$datas->user_image;
			rename($old_user_image,$new_user_image);
			$data['user_image']=$datas->user_image;
			unlink(FCPATH."upload/user/".$datas->user_image);
		 }
		 if(file_exists(FCPATH."upload/document/".$datas->document_path)){
			 $old_document_path=FCPATH."upload/document/".$datas->document_path;
			 $new_document_path=FCPATH."upload/decline_user/documents/".$datas->document_path;
			 rename($old_document_path,$new_document_path);
			 $data['document_path']=$datas->document_path;
			 unlink(FCPATH."upload/documents/".$datas->document_path);
		 }
		 $data['status']='decline';
		 $data['document_type']=$datas->document_type;
		 $profile=$this->users->get_profile_details($id);
		 $data['email']=$profile->email;
		 $data['created_at']=date('Y-m-d H:i:s');
		 $data['first_name']=$profile->first_name;
		 $data['last_name']=$profile->last_name;
		 $data['birth_date']=$profile->birth_date;
		 $data['country']=$profile->country;
		 $data['state']=$profile->state;
		 $data['city']=$profile->city;
		 $data['zipcode']=$profile->zipcode;
		 $data['nationality']=$profile->nationality;
		 $data['home_address']=$profile->home_address;
		 $data['mailing_address']=$profile->mailing_address;
		 $decline_user=$this->users->insert_decline_user_details($data);
		 $comments=$this->users->get_comment_by_id($id);
		 
		 foreach($comments as $comment)
		 { 
			 $save=array();
			 $save['user_id']=$decline_user;
			 $save['comment']=$comment->comment;
			 $save['verified_by']=$comment->verified_by;
			 $save['created_at']=$comment->created_at;
			 $save['verification_at']=$comment->verification_at;
			 $save['status']=$comment->status;
			 $this->users->insert_user_old_comment($save);
		 }
		 $this->users->delete_profile($id);
		 $this->users->delete_userdata($id);
		 $this->users->delete_user_verifications($id);
		 $this->users->delete_comment($id);
		 $this->session->set_flashdata('message', 'User profile has been decline successfully');
		 redirect('userverification');
		 
		 
	  }
  }

}
