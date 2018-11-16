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


        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[8]|max_length[20]');
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


    public function add($usertype)
    {
            $data = '';
            $data['PageTitle'] = "Zoddl | Add User Details";            
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
        
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/customer/add_view',$data);
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

     public function add_process($usertype)
     {
         $data = '';
            $data['PageTitle'] = "Zoddl | Add User Details";           
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

        if( $updateId = $this->customer_model->add_cutomer() )
            {
                
                $data['firstname'] = $firstname = $this->input->post('First_Name');

                $data['email'] = $email = $this->input->post('Email_Id');

                $data['password'] = $password = $this->input->post('Password');

                $to = $email;
                
                $subject = 'Registration Mail';                

                $body = $this->load->view('mailers/app-user-mail', $data, true);
                
                $send = Send_Mail($to, $subject, $body);                

                $this->session->set_flashdata('message', '<div class="alert alert-success"> Add Succesfully!</div>'); 
                redirect(base_url('customer/add/'.$usertype));               
            }
            else
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success"> Sorry some technical problem!</div>'); 
                redirect(base_url('customer/add/'.$usertype)); 
            }  

     }

    public function adduser($usertype)
        {
                $data = '';
                $data['PageTitle'] = "Zoddl | Add User Details";            
                $data['usertypeid'] = $usertype;
                $data['country'] = $this->customer_model->get_country();

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
            
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/customer/add_user_view',$data);
            $this->load->view('admin/template/footer2');

         }
    public function add_user_process($usertype)
         {
             $data = '';
                $data['PageTitle'] = "Zoddl | Add User Details";           
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

                $is_status = $this->customer_model->add_user();

            if( $is_status == true )
                {
                    
                    $data['firstname'] = $firstname = $this->input->post('first_name');

                    $data['email'] = $email = $this->input->post('email');

                    $data['password'] = $password = $this->input->post('password');

                    $to = $email;
                    
                    $subject = 'Registration Mail';                

                    $body = $this->load->view('mailers/staff-company-user-mail', $data, true);
                    
                    $send = Send_Mail($to, $subject, $body);                

                    $this->session->set_flashdata('message', '<div class="alert alert-success"> Add Succesfully!</div>'); 
                    redirect(base_url('customer/adduser/'.$usertype));               
                }
                else
                {
                    $this->session->set_flashdata('message', '<div class="alert alert-success">Sorry your Emai Id is already registered. Please use another Email Id.</div>'); 
                    redirect(base_url('customer/adduser/'.$usertype)); 
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

    public function multipledelete()
    {
                $value = $this->input->post('checkboxvalue');
                if( $this->customer_model->deletemultiple($value) )
                {
                    echo 'deleted';
                }
    }

    public function get_state()
    {
        $data = '';
        $countryid = $this->input->post('countryid');
        $data['state'] = $state = $this->customer_model->get_country_by_state($countryid);
        $view = $this->load->view('admin/customer/state_view',$data,true);
        echo $view; 
    }

    public function get_city()
    {

        $data = '';
        $stateid = $this->input->post('stateid');
        $data['city'] = $state = $this->customer_model->get_state_by_city($stateid);
        $view = $this->load->view('admin/customer/city_view',$data,true);
        echo $view;  
    }

    public function is_unique_user()
    {

        $email = $this->input->post('email');

        $is_aval = $this->db->get_where('tbl_users',array('email'=>$email))->num_rows();

        if($is_aval > 0)
        {
            echo 0;

        }
        else
        {
            echo 1;

        }


    }

    public function staffmultipledelete()
    {
                $value = $this->input->post('checkboxvalue');
                if( $this->customer_model->staffmultipledelete($value) )
                {
                    echo 'deleted';
                }
    }

    public function staffdelete($id)
        {

            if( $this->customer_model->staffdelete($id) )
            {
                echo 'deleted';
            }

        }

    public function staffchangestatus($id,$status)
    {
        
        if( $this->customer_model->staffchangestatus($id,$status) )
        {
            echo 'success';
        }
        
    }

    public function userchangepassword($usertype, $custmerid)
    {
        $data = '';
        $data['PageTitle'] = "Zoddl | User Change Password";
        $data['customerid'] = $custmerid;
        $data['usertypeid'] = $usertype;

        if($usertype == 2)
        {
            $data['usertype'] = "staffcustomer";

        }else
        {
            $data['usertype'] = "companycustomer";

        }


        $this->form_validation->set_rules('password', 'New Password', 'required|min_length[8]|max_length[20]');
        $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
        if($this->form_validation->run() == FALSE)      
        {
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/customer/userchangepassword',$data);
            $this->load->view('admin/template/footer2');
        }
        else
        {

            if( $this->customer_model->userpassword_update($custmerid) )
                {
                       $this->session->set_flashdata('flashSuccess5',"Password change successfully!");
                       redirect(base_url('customer/userchangepassword/'.$usertype.'/'.$custmerid),'refresh');
                }
                else
                {
                        $this->session->set_flashdata('flashSuccess5',"Sorry some technical problem!");
                        redirect(base_url('customer/userchangepassword/'.$usertype.'/'.$custmerid),'refresh');
                }
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/customer/userchangepassword',$data);
            $this->load->view('admin/template/footer2');
        }   
    }


   public function useredit($usertype, $custmerid)
    {
            $data = '';
            $data['PageTitle'] = "Zoddl | Edit User Details";
            $data['customerid'] = $custmerid;
            $data['usertypeid'] = $usertype;

            if($usertype == 2)
            {
                $data['usertype'] = "staffcustomer";

            }else
            {
                $data['usertype'] = "companycustomer";

            }

        $data['country'] = $this->customer_model->get_country();


        $data['customer'] = $customer = $this->customer_model->get_user_data($custmerid);
        $data['state'] = $this->customer_model->get_country_by_state($customer->country);
        $data['city'] = $this->customer_model->get_state_by_city($customer->state);


        
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/customer/useredit_view',$data);
        $this->load->view('admin/template/footer2');

     }


    public function useredit_process($usertype, $custmerid)
     {
         $data = '';
            $data['PageTitle'] = "Zoddl | Edit User Details";
            $data['customerid'] = $custmerid;
            $data['usertypeid'] = $usertype;

            if($usertype == 2)
            {
                $data['usertype'] = "staffcustomer";

            }else
            {
                $data['usertype'] = "companycustomer";

            }

        if( $updateId = $this->customer_model->update_user($custmerid) )
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success"> Updated Succesfully!</div>'); 
                redirect(base_url('customer/useredit/'.$usertype.'/'.$custmerid));               
            }
            else
            {
                $this->session->set_flashdata('message', '<div class="alert alert-success"> Sorry some technical problem!</div>'); 
                redirect(base_url('customer/useredit/'.$usertype.'/'.$custmerid)); 
            }  

     }

     public function userview($usertype, $custmerid)
     {
            $data = '';
            $data['PageTitle'] = "Zoddl | View User Details";
            $data['customerid'] = $custmerid;
            $data['usertypeid'] = $usertype;

            if($usertype == 2)
            {
                $data['usertype'] = "staffcustomer";

            }else
            {
                $data['usertype'] = "companycustomer";

            }


        $data['customer'] = $customer = $this->customer_model->get_user_data($custmerid);
        
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/customer/user_view',$data);
        $this->load->view('admin/template/footer2');

     }
    
    
    
}
