<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The base controller which is used by the Front and the Admin controllers
 */
class Base_Controller extends CI_Controller
{

	public function __construct()
	{

		parent::__construct();

		//kill any references to the following methods
		$mthd = $this->router->method;
		if($mthd == 'view' || $mthd == 'partial' || $mthd == 'set_template')
		{
			show_404();
		}

		//load base libraries, helpers and models
		$this->load->database();

		//load the default libraries
		$this->load->library(array('session','form_validation'));
		$this->load->helper(array('url', 'file', 'string', 'html', 'language','form','security','date'));
        $this->lang->load('tank_auth');

	}

}//end Base_Controller

class Front_Controller extends Base_Controller
{

	function __construct(){
		parent::__construct();
		 $this->load->library('tank_auth');
	}

	/*
	This works exactly like the regular $this->load->view()
	The difference is it automatically pulls in a header and footer.
	*/
	function view($view, $vars = array(), $string=false)
	{
		if($string)
		{
			$result	 = $this->load->view('header', $vars, true);
			$result	.= $this->load->view($view, $vars, true);
			$result	.= $this->load->view('footer', $vars, true);

			return $result;
		}
		else
		{
			$this->load->view('header', $vars);
			$this->load->view($view, $vars);
			$this->load->view('footer', $vars);
		}
	}

	/*
	This function simply calls $this->load->view()
	*/
	function partial($view, $vars = array(), $string=false)
	{
		if($string)
		{
			return $this->load->view($view, $vars, true);
		}
		else
		{
			$this->load->view($view, $vars);
		}
	}
}

class Admin_Controller extends Base_Controller
{

	function __construct()
	{
		parent::__construct();
	}

	function view($view, $vars = array(), $string=false)
	{

		if($string)
		{
			$result	 = $this->load->view('admin/header', $vars, true);
			$result	.= $this->load->view($view, $vars, true);
			$result	.= $this->load->view('admin/footer', $vars, true);

			return $result;
		}
		else
		{
			$this->load->view('admin/header', $vars);
			$this->load->view($view, $vars);
			$this->load->view('admin/footer', $vars);
		}


	}


}
require APPPATH . '/libraries/REST_Controller.php';
class Api_Controller extends REST_Controller
{

	function __construct()
	{
		parent::__construct();
		$this->load->helper(array('url', 'file', 'string', 'html', 'language','form','security','date'));
		$this->load->library('form_validation');
		$this->load->library('tank_auth');
		$this->lang->load('tank_auth');
		$this->form_validation->set_error_delimiters('', '');
	}




}
