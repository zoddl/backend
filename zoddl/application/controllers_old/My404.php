<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class My404 extends CI_Controller {

    function My404() 
    {
         parent::__construct();		 
         
         
    }

	function index()
	{
		
		$data = '';
		$data['PageTitle'] = "Zoddl | 404 Not Found";
		$this->load->view('admin/404-view',$data);
		
			  
	}
	
	
		
}
