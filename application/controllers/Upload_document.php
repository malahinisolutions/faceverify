<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Upload_document extends Front_Controller {

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
		if (!$this->tank_auth->is_logged_in(FALSE)) {									// logged in
			redirect('login');
		}
		$data['document_type']='';
		if(!is_null($profile_data=$this->users->get_user_verifications_by_ids($this->session->userdata('user_id'))))
		{
			$data['document_type']=$profile_data->document_type;
			$data['document_path']=$profile_data->document_path;
		}else{
			$data['document_type']=$this->form_validation->set_value('document_type');
			$data['document_path']='0';
		}
    $this->form_validation->set_rules('document_type', 'Document Type', 'trim|required');

    if ($this->form_validation->run()) {
      if((!empty($_FILES['document']['name'])))
			{
          $config['upload_path'] = FCPATH.'upload/document';
          $config['web_path']  = base_url() . 'upload/document/' .$_FILES['document']['name'];
          $config['allowed_types'] = 'png|jpg|jpeg';
          $config['file_name'] = time().$_FILES['document']['name'];
          $config['overwrite'] = TRUE;
          //Load upload library and initialize configuration
          $this->load->library('upload',$config);
          $this->upload->initialize($config);
          if($this->upload->do_upload('document'))
					{
            $uploadData = $this->upload->data();
            $document = $uploadData['file_name'];
						if(!is_null($profile_data1=$this->users->get_user_verifications_by_ids($this->session->userdata('user_id'))))
						{
							$save['document_type']=$this->form_validation->set_value('document_type');
							$save['document_path']=$document;
							$this->users->upload_document($this->session->userdata('user_id'),$save);
							$this->session->set_flashdata('message', $this->lang->line('auth_message_document_updated'));
							redirect('capture_image');
						}else{
							$save1['document_type']=$this->form_validation->set_value('document_type');
							$save1['document_path']=$document;
							$this->users->insert_document($this->session->userdata('user_id'),$save1);
							$this->session->set_flashdata('message', $this->lang->line('auth_message_document_inserted'));
							redirect('capture_image');
						}
          }else{
              $error = array('error' => $this->upload->display_errors());
               $this->session->set_flashdata('error', $error);
          }
      }else{
				if(!is_null($profile_data2=$this->users->get_user_verifications_by_ids($this->session->userdata('user_id'))))
				{
					$save2['document_type']=$this->form_validation->set_value('document_type');
					$save2['document_path']=$profile_data2->document_path;
					$this->users->upload_document($this->session->userdata('user_id'),$save2);
					$this->session->set_flashdata('message', $this->lang->line('auth_message_document_updated'));
					redirect('capture_image');
				}else{
					$save3['document_type']=$this->form_validation->set_value('document_type');
					$save3['document_path']=$profile_data2->document_path;
					$this->users->insert_document($this->session->userdata('user_id'),$save3);
					$this->session->set_flashdata('message', $this->lang->line('auth_message_document_inserted'));
					redirect('capture_image');
				}
			}
    }
    $data['name'] = empty($this->session->userdata('name')) ? $this->session->userdata('username') : $this->session->userdata('name');
    $data['page_title']='Upload Document';
		$this->view('upload_document_view',$data);
  }

}
