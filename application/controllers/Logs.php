<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Logs extends Front_Controller {

	function __construct()
	{
		parent::__construct();

		$this->load->helper(array('form', 'url','security'));
		$this->load->library('form_validation');
$this->logViewer = new \CILogViewer\CILogViewer();

	}
  public function index() {
      echo $this->logViewer->showLogs();
      return;
  }
}
