<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class User_model extends CI_Model {

    function __construct()
    {
        parent::__construct();
        $this->load->helper('language');		
    }

    function exists_email( $email )
    {		
		$email_count = $this->db->get_where('tbl_users',array('email' => $email))->num_rows();
		return $email_count;
        
    }    
    function user_data( $username )
    {
        return $this->db->get_where('tbl_users',array('email' => $username))->row();
    }
    
    function check_user_detail()
    {
        $username = $this->input->post('email');
        $password = $this->input->post('password');
        
        $userdata = $this->user_data( $username );
        
        if( $userdata->email == $username && $userdata->password == md5($password) && $userdata->status == 1)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
	
		
	function get_user( $user_id )
	{
		return $this->db->get_where('tbl_users',array('id' => $user_id))->row();
	}
	
	 function check_password()
	{
		return $this->db->get_where('tbl_users',array('email' => userdata('email'), 'password' => md5( $this->input->post('opassword') ) ))->num_rows();
	}
	
	function password_update()
	{
		return $this->db->update('tbl_users', array('password' => md5($this->input->post('password'))), array('email' => userdata('email')));
	}
    
    function create_lostpw_code()
    {
        $pass = time();        
        return $pass;
    } 
    function update_user_password($email, $pass)
    {
        $lostpw_code = md5($pass);
        $this->db->update('tbl_users',array('password' => $lostpw_code),array('email' => $this->input->post('email'))); 
        return true;
    }  
  
   

}
?>
