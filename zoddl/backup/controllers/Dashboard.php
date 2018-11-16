<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Dashboard Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description User Dashboard Controller
* @link http://zoddl.com/dashboard
*/

class Dashboard extends CI_Controller {

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Dashboard>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Dashboard() 
    {
        parent::__construct();
		$this->load->database();			
        $this->load->library('form_validation');
        $this->load->model("user_model");	
				 
        	/*cache control*/
		$this->output->set_header('Last-Modified: ' . gmdate("D, d M Y H:i:s") . ' GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate, post-check=0, pre-check=0');
		$this->output->set_header('Pragma: no-cache');
		$this->output->set_header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
		check_login();
         
         
    }

     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <index>
    * @Description:     <this function will user dashboard listing view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

	public function index()
	{
		
		$data = '';

		$data['susers'] = $this->db->get_where('tbl_customer', array('User_Type =' => 1))->num_rows();
		$data['gusers'] = $this->db->get_where('tbl_customer', array('User_Type =' => 2))->num_rows();
		$data['cusers'] = $this->db->get_where('tbl_customer', array('User_Type =' => 3))->num_rows();
		$data['ptag'] = $this->db->get_where('tbl_primary_tag')->num_rows();
		$data['stag'] = $this->db->get_where('tbl_secondary_tag')->num_rows();
		$data['images'] = $this->db->get_where('tbl_tag')->num_rows();
		$data['documents'] = $this->db->get_where('tbl_doc_tag')->num_rows();

		$data['PageTitle'] = "Zoddl | Dashboard";
		$this->load->view('admin/template/header',$data);	
		$this->load->view('admin/template/sidebar');		
		$this->load->view('admin/dashboard-view');
		$this->load->view('admin/template/footer');
			  
	}

	/**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <changepassword>
    * @Description:     <this function will user change password to admin panel>
    * @Parameters:      <password, oldpassword>
    * @Method:          <POST>
    * @Returns:         <Yes>
    * @Return Type:     <Boolean>
    */
	
	public function changepassword()
	{
		
		$data = '';
		$data['PageTitle'] = "Zoddl | Changepassword";
		$this->form_validation->set_rules('opassword', 'Old Password', 'required');
        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[6]|max_length[12]');
	    $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
		
		if($this->form_validation->run() == FALSE)		
			{
				$data['PageTitle'] = "Zoddl | Changepassword";
				$this->load->view('admin/template/header',$data);	
				$this->load->view('admin/template/sidebar');
				$this->load->view('admin/user/changepassword-view');
				$this->load->view('admin/template/footer');
		    }
		elseif( $this->user_model->check_password() == 0 )
		{
			$this->session->set_flashdata('flashSuccess5',"Please enter valid old password!");
		    redirect(base_url('dashboard/changepassword'),'refresh');
		}
			else
			{
				if( $this->user_model->password_update() )
				{
					
						$session_data = array(
							  "username"   => userdata('email'),
							  "userhash"   => md5( userdata('password').$this->config->item('password_hash')  )
								);
											  
						$this->session->set_userdata($session_data);
						$this->session->set_flashdata('flashSuccess5',"Password Update Successfully!");
					    	redirect(base_url('dashboard/changepassword'),'refresh');
				}
				else
				{
						$this->session->set_flashdata('flashSuccess5',"Password Update Successfully!");
					    	redirect(base_url('dashboard/changepassword'),'refresh');
				}
				$this->load->view('admin/template/header',$data);	
				$this->load->view('admin/template/sidebar');
				$this->load->view('admin/user/changepassword-view');
				$this->load->view('admin/template/footer');
				
			}
	}
		
}
