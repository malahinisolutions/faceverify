<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Personal_information extends Front_Controller {

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
		$data['first_name']='';
		$data['last_name']='';
		$data['day']='';
		$data['month']='';
		$data['year']='';
		$data['country']='';
		$data['state']='';
		$data['city']='';
		$data['zipcode']='';
		$data['home_address']='';
		$data['mailing_address']='';

		 if(!is_null($profile_data=$this->users->get_profile_details($this->session->userdata('user_id'))))
		 {
			 $data['first_name']=$profile_data->first_name;
			 $data['last_name']=$profile_data->last_name;
			 $birth_date=$profile_data->birth_date;
			 $date = explode('/', $birth_date);
			 $day = $date[0];
			 $month   = $date[1];
			 $year  = $date[2];
	 		$data['day']=$day;
	 		$data['month']=$month;
	 		$data['year']=$year;
	 		$data['country']=$profile_data->country;
	 		$data['state']=$profile_data->state;
	 		$data['city']=$profile_data->city;
	 		$data['zipcode']=$profile_data->zipcode;
	 		$data['home_address']=$profile_data->home_address;
	 		$data['mailing_address']=$profile_data->mailing_address;
		 }else{
			 $data['first_name']=$this->form_validation->set_value('first_name');
			 $data['last_name']=$this->form_validation->set_value('last_name');
	 		$data['day']=$this->form_validation->set_value('day');
	 		$data['month']=$this->form_validation->set_value('month');
	 		$data['year']=$this->form_validation->set_value('year');
	 		$data['country']=$this->form_validation->set_value('country');
	 		$data['state']=$this->form_validation->set_value('state');
	 		$data['city']=$this->form_validation->set_value('city');
	 		$data['zipcode']=$this->form_validation->set_value('zipcode');
	 		$data['home_address']=$this->form_validation->set_value('home_address');
	 		$data['mailing_address']=$this->form_validation->set_value('mailing_address');
		 }
		$this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|max_length['.$this->config->item('username_max_length', 'tank_auth').']|alpha_dash');
		$this->form_validation->set_rules('day', 'Day', 'trim|required');
		$this->form_validation->set_rules('month', 'Month', 'trim|required');
		$this->form_validation->set_rules('year', 'Year', 'trim|required');
		$this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
		$this->form_validation->set_rules('state', 'State', 'trim|xss_clean|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
		$this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
		$this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required|xss_clean|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
		$this->form_validation->set_rules('home_address', 'Home Address', 'trim|required|xss_clean|max_length['.$this->config->item('password_max_length', 'tank_auth').']');
		$this->form_validation->set_rules('mailing_address', 'MAiling Address', 'trim|xss_clean|max_length['.$this->config->item('password_max_length', 'tank_auth').']');

		if ($this->form_validation->run()) {
			$save['first_name']=$this->form_validation->set_value('first_name');
			$save['last_name']=$this->form_validation->set_value('last_name');
			$day=$this->form_validation->set_value('day');
			$month=$this->form_validation->set_value('month');
			$year=$this->form_validation->set_value('year');
			$birth_date=$day.'/'.$month.'/'.$year;
			$save['birth_date']=$birth_date;
			$save['country']=$this->form_validation->set_value('country');
			$save['state']=$this->form_validation->set_value('state');
			$save['city']=$this->form_validation->set_value('city');
			$save['zipcode']=$this->form_validation->set_value('zipcode');
			$save['home_address']=$this->form_validation->set_value('home_address');
			$save['mailing_address']=$this->form_validation->set_value('mailing_address');
			$this->users->update_user_profile($this->session->userdata('user_id'),$save);
			$this->session->set_flashdata('message', $this->lang->line('auth_message_profile_updated'));
			redirect('upload_document');
		}

		$data['name'] = empty($this->session->userdata('name')) ? $this->session->userdata('username') : $this->session->userdata('name');
    $data['page_title']='Personal Information';
		$this->view('personal_information_view',$data);
  }


}
