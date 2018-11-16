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
        $this->load->model("customer_model");	
				 
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
		$currentdate = date("Y-m-d");

		$data['susers'] = $this->db->get_where('tbl_users', array('user_type =' => 2))->num_rows();
		$data['gusers'] = $this->db->get_where('tbl_customer', array('User_Type =' => 2))->num_rows();
		$data['cusers'] = $this->db->get_where('tbl_users', array('user_type =' => 3))->num_rows();
		$data['ptag'] = $this->db->get_where('tbl_primary_tag')->num_rows();
		$data['stag'] = $this->db->get_where('tbl_secondary_tag')->num_rows();
		$data['images'] = $this->db->get_where('tbl_tag')->num_rows();
		$data['documents'] = $this->db->get_where('tbl_doc_tag')->num_rows();

		$data['pendingImageDataEntry'] = $this->db->get_where('tbl_tag', array('Tag_Status =' => 2))->num_rows();
		$data['pendingDocumentDataEntry'] = $this->db->get_where('tbl_doc_tag', array('Tag_Status =' => 2))->num_rows();
		$data['newImageDataEntry'] = $this->db->get_where('tbl_tag', array('Tag_Send_Date =' => $currentdate))->num_rows();
		$data['newDocumentDataEntry'] = $this->db->get_where('tbl_doc_tag', array('Tag_Send_Date =' => $currentdate))->num_rows();
		$data['newUser'] = $this->db->get_where('tbl_customer', array('Sign_Up_Date =' => $currentdate))->num_rows();
		$data['freeUser'] = $this->db->get_where('tbl_customer', array('Paid_Status =' => 0))->num_rows();
		$data['paidUser'] = $this->db->get_where('tbl_customer', array('Paid_Status =' => 1))->num_rows();
		$data['api_report'] = $api_report = $this->db->group_by('Api_Name')->get_where('tbl_api_report', array('Api_Date =' => $currentdate))->result();

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

	public function editprofile()
	{

			$data = '';

			$uid = $this->db->get_where('tbl_users',array('email' => $this->session->userdata('username')))->row()->id;

            $data['PageTitle'] = "Zoddl | Edit Profile Details";            

	        $data['country'] = $this->customer_model->get_country();

	        $data['user'] = $user = $this->customer_model->get_user_data($uid);

	        $data['state'] = $this->customer_model->get_country_by_state($user->country);

	        $data['city'] = $this->customer_model->get_state_by_city($user->state);

	        $this->load->view('admin/template/header2',$data);  
	        $this->load->view('admin/template/sidebar');        
	        $this->load->view('admin/user/useredit_view',$data);
	        $this->load->view('admin/template/footer2');
	}

	public function profileedit_process()
     {
         	$data = '';
            $data['PageTitle'] = "Zoddl | Edit Profile Details";
                       
            $uid = $this->db->get_where('tbl_users',array('email' => $this->session->userdata('username')))->row()->id;

        	if( $updateId = $this->customer_model->update_profile($uid) )
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success"> Updated Succesfully!</div>'); 
                redirect(base_url('dashboard/editprofile/'));               
            }
            else
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success"> Sorry some technical problem!</div>'); 
                redirect(base_url('dashboard/editprofile/')); 
            }  

     }
		
}
