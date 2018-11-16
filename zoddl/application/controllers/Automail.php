<?php
defined('BASEPATH') OR exit('No direct script access allowed');



class Automail extends CI_Controller
{
    

    function Automail()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('form_validation');
    }   
    

  public function dashboard()
  {

      $data = '';
      $to = array('veerender@apptology.in','antaryami@apptology.in');             
      $subject = 'Dashboard Report Mail';
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
      $body = $this->load->view('mailers/dashboard-mail', $data, true); 
      $send = Send_Mail($to, $subject, $body);
      if($send)
      {
        echo "Send";
      }
      else
      {
        echo "Problem";

      }
      
  }
    
    

 
  
      
    
}
