<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Customerverification extends CI_Controller
{
    function Customerverification()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
    }   
    


    function verification($customerid = '', $key = '')
    {
        if($key == '' || $customerid == '')
        {

            echo "Sorry your verification key mismatch!";

        }
        else
        {
            $tableName = 'tbl_customer';
            $verKey = $key;
            $customerId = $customerid;
            $getData =  $this->db->get_where($tableName, array('Id' => $customerId, 'Varification_Key' => $verKey))->num_rows();   
            
            if($getData > 0)
            {
                $data = array(                                
                                    'Status' => 1
                                 );
                    $this->db->where('Id', $customerId);
                    $this->db->where('Varification_Key', $verKey);
                  $result = $this->db->update($tableName, $data);

                  if($result == TRUE)
                  {
                    echo "You have verify successfully. Please login to continue";

                  }
                  else
                  {

                    echo "Sorry some technical problem!";
                  }
              }
              else
              {

                echo "Sorry your verification key mismatch!";
              }
        }
    }

    function resetpassword($customerid = '', $token = '')
    {
      $data = '';
      $data['cid'] = $customerid;
      $data['token'] = $token;
        if($token == '' || $customerid == '')
        {

             $data['message'] = "Sorry your token key mismatch!";
             $this->load->view('customer-reset-password',$data);

        }
        else
        {
            $tableName = 'tbl_customer';
            $token = $token;
            $customerId = $customerid;
            $getData =  $this->db->get_where($tableName, array('Id' => $customerId, 'Reset_Token' => $token))->num_rows();   
            
            if($getData > 0)
            {
               $this->form_validation->set_rules('password', 'New Password', 'required|min_length[6]|max_length[12]');
                
                $this->form_validation->set_rules('cpassword', 'Confirm Password', 'required|matches[password]');
                if($this->form_validation->run() == FALSE)      
                {

                  $this->load->view('customer-reset-password',$data);

                }
                else
                {
                     $passworddata = array(
                                            'Password' => md5($this->input->post('password')),
                                            'Reset_Token' => '',
                                          ); 

                     $this->db->where('Id', $customerid);
                     $upPassword = $this->db->update('tbl_customer ', $passworddata);


                    if( $upPassword )
                    {
                           
                            $data['succ'] = "Password reset successfully. Please login to continue!";
                            $this->load->view('customer-reset-password',$data);
                    }
                    else
                    {
                           
                            $data['succ'] = "Sorry some technical problem!";
                            $this->load->view('customer-reset-password',$data);
                    }
                   
                    $this->load->view('customer-reset-password',$data);
                }
              }
              else
              {
                            $data['message'] = "Sorry your token key mismatch!";
                            $this->load->view('customer-reset-password',$data);

              }
        }
    }

    function apihitreport()
    {


              if($this->input->post('email') != '')
              {
                $to = $this->input->post('email');
              }
              else
              {
                $to = "antaryami@apptology.in";

              }
                
                $table = 'tbl_api_report';
                
                $subject = 'Api Hit Report';

                $currntDate = date("Y-m-d");

                $apidetail = $this->db->get_where($table, array('Api_Date' => $currntDate))->result();

                $data['apidetail'] =  $apidetail;
                $data['currntDate'] =  $currntDate;
                

                $body = $this->load->view('mailers/api-hit-report-mail', $data, true);
                
                $send = Send_Mail($to, $subject, $body);

                if($send)
                {
                      $this->session->set_flashdata('success_msg', 'Mail send successfully. Please Check Your Mail.');
                      redirect(base_url('customerverification/apihitreportform'));
                }
                else
                {
                      $this->session->set_flashdata('success_msg', 'Some technical problem.');
                      redirect(base_url('customerverification/apihitreportform'));
                }

               

    }
    function apihitreportform()
    {

       $this->load->view('mailers/api-hit-report-form');
    }
    
    
    
}
