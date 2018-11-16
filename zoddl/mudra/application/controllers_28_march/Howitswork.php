<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Customer Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description Customer Controller
* @link http://zoddl.com/appcustomer
*/

class Howitswork extends CI_Controller
{
    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Customer>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Howitswork()
    {
        parent::__construct();
        $this->load->database();
        //$this->load->model("customer_model");
        //$this->load->library('form_validation');
        
        /*cache control*/
	    $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		//check_login();

    }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <changepassword>
    * @Description:     <this function will Customer change password to admin panel>
    * @Parameters:      <password, cpassword>
    * @Method:          <POST>
    * @Returns:         <YES>
    * @Return Type:     <boolean>
    */
    
   public function index()
   {
        $this->load->view('how_it_work_view');
   }
    
    
    
}
