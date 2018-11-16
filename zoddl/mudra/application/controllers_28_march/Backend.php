<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Backend Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description This is default controller use for check user login details. 
* @link http://zoddl.com/
*/

class Backend extends CI_Controller
{

     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Backend>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */
   
    function Backend()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("user_model");
        $this->load->library('form_validation');
        
        /*cache control*/
        $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
    	$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
    	$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
    	$this->output->set_header('Pragma: no-cache');
    }

     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <index>
    * @Description:     <this function will login view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    public function index()
    {
        redirect(base_url('login'), 'refresh');
    }

     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <login>
    * @Description:     <this function check the login if user login redirect dashboard other wise go to login view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */
    
    public function login()
    {
        if (is_login() == 1) {
            redirect(base_url('dashboard'), 'refresh');
        }
        $this->load->view('admin/user/user-login');
    }


    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <login_process>
    * @Description:     <this function check the login credentials to admin panel>
    * @Parameters:      <email, password>
    * @Method:          <POST>
    * @Returns:         <YES>
    * @Return Type:     <boolean>
    */


    public function login_process()
    {
        
        if ($this->form_validation->run('login_user') == FALSE) {
            
            echo '<div class="alert alert-warning"><ul>' . validation_errors() . '</ul></div>';
            
        } elseif ($this->user_model->exists_email($this->input->post('email')) == 0) {
            echo '<div class="alert alert-danger"><span>You must be create an account!</span></div>';
        } elseif ($this->user_model->check_user_detail() == FALSE) {
            echo '<div class="alert alert-danger"><span>Invalid email or password!</span></div>';
        } else {
            $userdata = $this->user_model->user_data($this->input->post('email'));
            
            $session_data = array(
                "username" => $userdata->email,
                "first_name" => $userdata->name,				
                "userhash" => md5($userdata->password . $this->config->item('password_hash'))
            );
            $this->session->set_userdata($session_data);
			
			$updateloginTimeData = array(
				'last_login' => date("Y-m-d H:i:s"),
				'last_login_int' => strtotime(date("Y-m-d H:i:s"))
			);
			$this->db->update('tbl_users',$updateloginTimeData,array('email'=> $this->input->post('email')));
			
            echo '<script>location.href="' . base_url('dashboard') . '";</script>';
        }
    }


    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <forget_process>
    * @Description:     <this function use the user forgot password process to admin panel>
    * @Parameters:      <email>
    * @Method:          <POST>
    * @Returns:         <YES>
    * @Return Type:     <boolean>
    */

    public function forget_process()
    {
        if ($this->form_validation->run('lostpassword') == FALSE) {
            echo '<div class="alert-danger">' . validation_errors() . '</div>';
        } elseif ($this->user_model->exists_email($this->input->post('email')) == 0) {
            echo '<div class="alert-danger">Please enter valid register email.</div>';
        } else {            
            $lostpw_code = $this->user_model->create_lostpw_code();
            $sendEmail   = $this->input->post('email');
            if ($lostpw_code) {

                
                $to = $sendEmail;
                $subject = 'Forget Password';
                $data['lostCode'] = $lostpw_code;
                $body = $this->load->view('mailers/admin-forget-mail', $data, true);
                
                $send = Send_Mail($sendEmail, $subject, $body);

                if ($send) {

                    $this->user_model->update_user_password($this->input->post('email'), $lostpw_code);

                    echo '<span style="color: green;"> Password has been sent, Please check your email id.</span>';
                } else {                   
                    echo '<span style="color: red;" >Sorry Try again !!</span>';
                }
            }            
            
        }        
        
    }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <logout>
    * @Description:     <this function use the user logout process to admin panel>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */
    
    public function logout()
    {       
       
    	$this->session->unset_userdata('username');
    	$this->session->unset_userdata('first_name');	
    	$this->session->unset_userdata('userhash');       
        $this->output->nocache();        
        redirect(base_url() . "login", "refresh");
    }
    
}
