<?php defined('BASEPATH') or exit('No direct script access allowed');


/**
 * This is an profile of a few basic user interaction methods
 *
 * @package         CodeIgniter
 * @subpackage      Rest Server
 * @category        Controller
 * @author          Phil Sturgeon, Chris Kacerguis
 * @license         MIT
 * @link            https://github.com/chriskacerguis/codeigniter-restserver
 */
class Profile extends Api_Controller
{
    public function __construct()
    {
        // Construct the parent class
        parent::__construct();

        $this->load->model('tank_auth/users');
    }

    public function basic_profile_post()
    {
        if (count($this->post()) > 1 || count($this->post()) != '1') {
            $this->response(array('error'=>'The invalid fields provided.'), REST_Controller::HTTP_BAD_REQUEST); // BAD_REQUEST (400) being the HTTP response code
        }
        $user_id=$this->post('user_id');
        if (!isset($user_id) && empty($user_id)) {
            $this->response(array('error'=>array('user_id'=>'The User ID key is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|is_natural_no_zero');
        $profiledetails='';
        $data['errors'] = array();
        if ($this->form_validation->run()) {
            if ($profiledetails=$this->tank_auth->profile_details(
                        $this->form_validation->set_value('user_id'))) {        // success
                    $this->set_response(array('profiledetails'=>$profiledetails), REST_Controller::HTTP_OK);
            } else {                                                        // fail
                    $this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
        }
    }

    public function update_profile_post()
    {
        $user_id=$this->post('user_id');
        $first_name=$this->post('first_name');
        $last_name=$this->post('last_name');
        $birth_date=$this->post('birth_date');
        $country=$this->post('country');
        $state=$this->post('state');
        $city=$this->post('city');
        $zipcode=$this->post('zipcode');
        $nationality=$this->post('nationality');
        $home_address=$this->post('home_address');
        $mailing_address=$this->post('mailing_address');
        if (!isset($user_id) && empty($user_id)) {
            $this->response(array('error'=>array('user_id'=>'The User ID field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($first_name) && empty($first_name)) {
            $this->response(array('error'=>array('first_name'=>'The First Name field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($last_name) && empty($last_name)) {
            $this->response(array('error'=>array('last_name'=>'The Last Name field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($birth_date) && empty($birth_date)) {
            $this->response(array('error'=>array('birth_date'=>'The Birth Date field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($country) && empty($country)) {
            $this->response(array('error'=>array('country'=>'The country field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($state) && empty($state)) {
            $this->response(array('error'=>array('state'=>'The state field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($city) && empty($city)) {
            $this->response(array('error'=>array('city'=>'The city field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($zipcode) && empty($zipcode)) {
            $this->response(array('error'=>array('zipcode'=>'The zipcode field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($nationality) && empty($nationality)) {
            $this->response(array('error'=>array('nationality'=>'The nationality field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }
        if (!isset($home_address) && empty($home_address)) {
            $this->response(array('error'=>array('home_address'=>'The home_address field is required.')), REST_Controller::HTTP_BAD_REQUEST);
        }

        if (!empty($this->post('profile_image'))) {
            $filename = $user_id.".png";
            file_put_contents(FCPATH."upload/user/".$filename, base64_decode($this->post('profile_image')));
        }
        $this->form_validation->set_data($this->post());
        $this->form_validation->set_rules('user_id', 'User ID', 'trim|required|xss_clean|is_natural_no_zero');
        $this->form_validation->set_rules('first_name', 'First Name', 'trim|required|xss_clean|alpha');
        $this->form_validation->set_rules('last_name', 'Last Name', 'trim|required|xss_clean|alpha');
        $this->form_validation->set_rules('new_email', 'New Email', 'trim|xss_clean|valid_email');
        $this->form_validation->set_rules('birth_date', 'Birth Date', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|xss_clean');

        $this->form_validation->set_rules('country', 'Country', 'trim|required|xss_clean');
        $this->form_validation->set_rules('state', 'State', 'trim|required|xss_clean');
        $this->form_validation->set_rules('city', 'City', 'trim|required|xss_clean');
        $this->form_validation->set_rules('zipcode', 'Zipcode', 'trim|required|xss_clean');
        $this->form_validation->set_rules('nationality', 'Nationality', 'trim|required|xss_clean');
        $this->form_validation->set_rules('home_address', 'Home Address', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mailing_address', 'Mailing Address', 'trim|xss_clean');

        $data['errors'] = array();
        $profiledetails='';

        if ($this->form_validation->run()) {
            $save['first_name'] =  $this->form_validation->set_value('first_name');
            $save['last_name'] =  $this->form_validation->set_value('last_name');
            $save['birth_date'] =  $this->form_validation->set_value('birth_date');
            $save['country'] =  $this->form_validation->set_value('country');
            $save['state'] =  $this->form_validation->set_value('state');
            $save['city'] =  $this->form_validation->set_value('city');
            $save['zipcode'] =  $this->form_validation->set_value('zipcode');
            $save['nationality'] =  $this->form_validation->set_value('nationality');
            $save['home_address'] =  $this->form_validation->set_value('home_address');
            $save['mailing_address'] =  $this->form_validation->set_value('mailing_address');
            if ($this->tank_auth->update_profile(
                        $this->form_validation->set_value('user_id'), $save)) {
                if (!empty($this->post('new_email'))) {
                    if (!is_null($data = $this->tank_auth->set_new_email_profile(
                                        $this->form_validation->set_value('user_id'),
                                        $this->form_validation->set_value('new_email')))) {
                        $data['site_name'] = $this->config->item('website_name', 'tank_auth');

                                    // Send email with new email address and its activation link
                                    $this->tank_auth->send_email('change_email', $data['new_email'], $data);
                        $this->set_response(array('message'=>sprintf($this->lang->line('auth_message_new_email_sent'), $data['new_email']) ), REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
                    }
                }
                if (!empty($this->post('new_password'))) {
                    if ($this->tank_auth->change_password_profile(
                                        $this->form_validation->set_value('user_id'),
                                        $this->form_validation->set_value('new_password'))) {
                        $this->set_response(array('message'=>$this->lang->line('auth_message_password_changed') ), REST_Controller::HTTP_OK);
                    } else {
                        $this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
                    }
                }
                $profiledetails=$this->tank_auth->profile_details($this->form_validation->set_value('user_id'));
                $this->set_response(array('message'=>$this->lang->line('auth_message_profile_updated'),'profiledetails'=>$profiledetails ), REST_Controller::HTTP_OK);
            } else {
                $this->set_response(array('error' => $this->tank_auth->get_error_message()), REST_Controller::HTTP_NOT_FOUND);
            }
        } else {
            $this->set_response(array('error' => $this->form_validation->error_array()), REST_Controller::HTTP_NOT_FOUND);
        }
    }
}
/* End of file Profile.php */
/* Location: ./application/controllers/Profile.php */
