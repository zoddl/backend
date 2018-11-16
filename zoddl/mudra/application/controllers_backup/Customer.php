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

class Customer extends CI_Controller
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

    function Customer()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("customer_model");
        $this->load->library('form_validation');
        
        /*cache control*/
	    $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		check_login();

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
    
  public function changepassword($usertype, $custmerid)
    {
        $data = '';
		$data['PageTitle'] = "Zoddl | User Change Password";
        $data['customerid'] = $custmerid;
        $data['usertypeid'] = $usertype;

        if($usertype == 1)
        {

            $data['usertype'] = "staffcustomer";
        }
        elseif($usertype == 2)
        {
            $data['usertype'] = "appcustomer";

        }else
        {
            $data['usertype'] = "companycustomer";

        }


        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[6]|max_length[12]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
        if($this->form_validation->run() == FALSE)      
        {
    		$this->load->view('admin/template/header2',$data);	
    		$this->load->view('admin/template/sidebar');		
    		$this->load->view('admin/customer/changepassword',$data);
    		$this->load->view('admin/template/footer2');
        }
        else
        {

            if( $this->customer_model->password_update($custmerid) )
                {
                       $this->session->set_flashdata('flashSuccess5',"Password change successfully!");
                       redirect(base_url('customer/changepassword/'.$usertype.'/'.$custmerid),'refresh');
                }
                else
                {
                        $this->session->set_flashdata('flashSuccess5',"Sorry some technical problem!");
                        redirect(base_url('customer/changepassword/'.$usertype.'/'.$custmerid),'refresh');
                }
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/customer/changepassword',$data);
            $this->load->view('admin/template/footer2');
        }	
    }


    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <edit>
    * @Description:     <this function will edit user details view to admin panel>
    * @Parameters:      <$usertype, $custmerid>
    * @Method:          <GET>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

   public function edit($usertype, $custmerid)
    {
            $data = '';
            $data['PageTitle'] = "Zoddl | Edit User Details";
            $data['customerid'] = $custmerid;
            $data['usertypeid'] = $usertype;

            if($usertype == 1)
            {

                $data['usertype'] = "staffcustomer";
            }
            elseif($usertype == 2)
            {
                $data['usertype'] = "appcustomer";

            }else
            {
                $data['usertype'] = "companycustomer";

            }

        $data['customer'] = $customer = $this->customer_model->get_customer_data($custmerid);
        
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/customer/edit_view',$data);
        $this->load->view('admin/template/footer2');

     }


     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <edit_process>
    * @Description:     <this function will edit user details and update to admin panel>
    * @Parameters:      <Customer_Name, Email_Id, Phone_No, Alt_Phone_No.......>
    * @Method:          <POST>
    * @Returns:         <YES>
    * @Return Type:     <Boolean>
    */

    public function edit_process($usertype, $custmerid)
     {
         $data = '';
            $data['PageTitle'] = "Zoddl | Edit User Details";
            $data['customerid'] = $custmerid;
            $data['usertypeid'] = $usertype;

            if($usertype == 1)
            {

                $data['usertype'] = "staffcustomer";
            }
            elseif($usertype == 2)
            {
                $data['usertype'] = "appcustomer";

            }else
            {
                $data['usertype'] = "companycustomer";

            }

        if( $updateId = $this->customer_model->update_cutomer($custmerid) )
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success"> Updated Succesfully!</div>'); 
                redirect(base_url('customer/edit/'.$usertype.'/'.$custmerid));               
            }
            else
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success"> Sorry some technical problem!</div>'); 
                redirect(base_url('customer/edit/'.$usertype.'/'.$custmerid)); 
            }  

     }


    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <view>
    * @Description:     <this function will view user details to admin panel>
    * @Parameters:      <$usertype, $custmerid>
    * @Method:          <GET>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

     public function view($usertype, $custmerid)
     {
            $data = '';
            $data['PageTitle'] = "Zoddl | View User Details";
            $data['customerid'] = $custmerid;
            $data['usertypeid'] = $usertype;

            if($usertype == 1)
            {

                $data['usertype'] = "staffcustomer";
            }
            elseif($usertype == 2)
            {
                $data['usertype'] = "appcustomer";

            }else
            {
                $data['usertype'] = "companycustomer";

            }


        $data['customer'] = $customer = $this->customer_model->get_customer_data($custmerid);
        
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/customer/customer_view',$data);
        $this->load->view('admin/template/footer2');

     }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <delete>
    * @Description:     <this function will delete user to admin panel>
    * @Parameters:      <$id>
    * @Method:          <GET>
    * @Returns:         <Yes>
    * @Return Type:     <string>
    */

     public function delete($id)
        {

            if( $this->customer_model->delete($id) )
            {
                echo 'deleted';
            }

        }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <changestatus>
    * @Description:     <this function will change user status to admin panel>
    * @Parameters:      <$id, $status>
    * @Method:          <GET>
    * @Returns:         <Yes>
    * @Return Type:     <string>
    */

    public function changestatus($id,$status)
        {
            
            if( $this->customer_model->changestatus($id,$status) )
            {
                echo 'success';
            }
            
        }
    
    
    
}
