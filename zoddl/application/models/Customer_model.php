<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Customer_model Class
*
* @author Antaryami
* @created on 28-12-2017
* @version 1.0
* @description customer model written all business logics
* @link https://zoddl.com/
*/

class Customer_model extends CI_Model {


    /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <__construct>
    * @Description:     <this function load database>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->database();		
        }

    /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <password_update>
    * @Description:     <this function use for update the customer password.>
    * @Parameters:      <YES>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Boolean>
    */

	
	public function password_update($custmerid)
    	{
    		return $this->db->update('tbl_customer', array('Password' => md5($this->input->post('password'))), array('Id' => $custmerid));
    	}

    /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_customer_data>
    * @Description:     <this function use for get the customer data.>
    * @Parameters:      <YES>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Object Type Value>
    */

    public function get_customer_data($custmerid)
        {

            return $this->db->get_where('tbl_customer',array('Id'=>$custmerid))->row();
        }

     /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <update_cutomer>
    * @Description:     <this function use for update the customer data.>
    * @Parameters:      <YES>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Boolean>
    */

    public function update_cutomer($custmerid)
        {
                $updateData = array(
                                    'Customer_Name'=> $this->input->post('First_Name').' '.$this->input->post('Last_Name'),
                                    'First_Name'=> $this->input->post('First_Name'),
                                    'Last_Name'=> $this->input->post('Last_Name'),
                                    'Gender'=> $this->input->post('Gender'),
                                    'Email_Id'=> $this->input->post('Email_Id'),
                                    'Paid_Status'=> $this->input->post('Paid_Status'),
                                    'Dob'=> $this->input->post('Dob'),
                                    'Company_Name'=> $this->input->post('Company_Name'),
                                    'Gstn'=> $this->input->post('Gstn'),
                                    'Pan_Number'=> $this->input->post('Pan_Number'),
                                    'Phone_No'=> $this->input->post('Phone_No'),
                                    'Alt_Phone_No'=> $this->input->post('Alt_Phone_No'),
                                    'Aadhar_No'=> $this->input->post('Aadhar_No'),
                                    'Skype_Id'=> $this->input->post('Skype_Id'),
                                    'Facebook_Profile'=> $this->input->post('Facebook_Profile'),
                                    'Linked_In_Profile'=> $this->input->post('Linked_In_Profile'),
                                    'Twitter_Handle'=> $this->input->post('Twitter_Handle')
                                );
                                     
                 $updateRec= $this->db->update('tbl_customer',$updateData,array('Id'=> $custmerid));

                 //echo $this->db->last_query(); die;
                 
                 return $updateRec;

        }

        public function add_cutomer()
        {
                $insertData = array(
                                    'Customer_Name'=> $this->input->post('First_Name').' '.$this->input->post('Last_Name'),
                                    'First_Name'=> $this->input->post('First_Name'),
                                    'Last_Name'=> $this->input->post('Last_Name'),
                                    'Gender'=> $this->input->post('Gender'),
                                    'Email_Id'=> $this->input->post('Email_Id'),
                                    'Paid_Status'=> $this->input->post('Paid_Status'),
                                    'Dob'=> $this->input->post('Dob'),
                                    'Pan_Number'=> $this->input->post('Pan_Number'),
                                    'Company_Name'=> $this->input->post('Company_Name'),
                                    'Gstn'=> $this->input->post('Gstn'),
                                    'Phone_No'=> $this->input->post('Phone_No'),
                                    'Alt_Phone_No'=> $this->input->post('Alt_Phone_No'),
                                    'Aadhar_No'=> $this->input->post('Aadhar_No'),
                                    'Skype_Id'=> $this->input->post('Skype_Id'),
                                    'Facebook_Profile'=> $this->input->post('Facebook_Profile'),
                                    'Linked_In_Profile'=> $this->input->post('Linked_In_Profile'),
                                    'Twitter_Handle'=> $this->input->post('Twitter_Handle'),
                                    'User_Type'=> 2,
                                    'Sign_Up_Date' => date("Y-m-d"),
                                    'Status'=> 1,
                                    'Password'=> md5($this->input->post('Password'))
                                );
                                     
                 $insertRec = $this->db->insert('tbl_customer',$insertData);
                 
                 return $insertRec;

        }

        public function add_user()
        {
                
                $is_email = $this->db->get_where('tbl_users', array('email'=> $this->input->post('email')))->num_rows();

                if($is_email > 0)
                {
                    
                    return false;

                }
                else
                {

                    $admin_id = "USER".time();

                    $insertData = array(
                                        'name'=> $this->input->post('first_name').' '.$this->input->post('last_name'),
                                        'first_name'=> $this->input->post('first_name'),
                                        'last_name'=> $this->input->post('last_name'),
                                        'user_type'=> $this->input->post('user_type'),
                                        'user_role'=> $this->input->post('user_role'),                                   
                                        'email'=> $this->input->post('email'),
                                        'company_name'=> $this->input->post('company_name'),
                                        'gstn'=> $this->input->post('gstn'),
                                        'phone_number'=> $this->input->post('phone_number'),
                                        'alt_phone_number'=> $this->input->post('alt_phone_number'),
                                        'aadhar_no'=> $this->input->post('aadhar_no'),
                                        'skype_id'=> $this->input->post('skype_id'),
                                        'facebook_profile'=> $this->input->post('facebook_profile'),
                                        'linked_in_profile'=> $this->input->post('linked_in_profile'),
                                        'twitter_handle'=> $this->input->post('twitter_handle'),
                                        'country'=> $this->input->post('country'),
                                        'state'=> $this->input->post('state'),
                                        'city'=> $this->input->post('city'),
                                        'admin_id'=> $admin_id,
                                        'status'=> 1,
                                        'password'=> md5($this->input->post('password'))
                                    );
                                         
                     $insertRec = $this->db->insert('tbl_users',$insertData);
                     
                    return true;
                }


        }


    public function update_user($custmerid)
        {
           
                    $updateData = array(
                                        'name'=> $this->input->post('first_name').' '.$this->input->post('last_name'),
                                        'first_name'=> $this->input->post('first_name'),
                                        'last_name'=> $this->input->post('last_name'),
                                        'user_type'=> $this->input->post('user_type'),
                                        'user_role'=> $this->input->post('user_role'),
                                        'company_name'=> $this->input->post('company_name'),
                                        'gstn'=> $this->input->post('gstn'),
                                        'phone_number'=> $this->input->post('phone_number'),
                                        'alt_phone_number'=> $this->input->post('alt_phone_number'),
                                        'aadhar_no'=> $this->input->post('aadhar_no'),
                                        'skype_id'=> $this->input->post('skype_id'),
                                        'facebook_profile'=> $this->input->post('facebook_profile'),
                                        'linked_in_profile'=> $this->input->post('linked_in_profile'),
                                        'twitter_handle'=> $this->input->post('twitter_handle'),
                                        'country'=> $this->input->post('country'),
                                        'state'=> $this->input->post('state'),
                                        'city'=> $this->input->post('city')
                                    );                                        
                     
                    $this->db->update('tbl_users',$updateData,array('id'=> $custmerid));
                     
                    return true;
               


        }

        
    public function update_profile($custmerid)
        {
           
                    $updateData = array(
                                        'name'=> $this->input->post('first_name').' '.$this->input->post('last_name'),
                                        'first_name'=> $this->input->post('first_name'),
                                        'last_name'=> $this->input->post('last_name'),                                       
                                        'company_name'=> $this->input->post('company_name'),
                                        'gstn'=> $this->input->post('gstn'),
                                        'phone_number'=> $this->input->post('phone_number'),
                                        'alt_phone_number'=> $this->input->post('alt_phone_number'),
                                        'aadhar_no'=> $this->input->post('aadhar_no'),
                                        'skype_id'=> $this->input->post('skype_id'),
                                        'facebook_profile'=> $this->input->post('facebook_profile'),
                                        'linked_in_profile'=> $this->input->post('linked_in_profile'),
                                        'twitter_handle'=> $this->input->post('twitter_handle'),
                                        'country'=> $this->input->post('country'),
                                        'state'=> $this->input->post('state'),
                                        'city'=> $this->input->post('city')
                                    );                                        
                     
                    $this->db->update('tbl_users',$updateData,array('id'=> $custmerid));
                     
                    return true;
               


        }

     /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <delete>
    * @Description:     <this function use for delete the customer data.>
    * @Parameters:      <YES>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Boolean>
    */

    public function delete($id)
        {
            if( $this->db->delete('tbl_customer',array('Id' => $id)) )
            {  
                return true;
            }
        }

    /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <changestatus>
    * @Description:     <this function use for change the customer status.>
    * @Parameters:      <YES>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Boolean>
    */

    public function changestatus($id,$status)
        {
            $updateData = array(
                                        'Status' => $status
                );                          
            if($this->db->update('tbl_customer',$updateData,array('Id' => $id)) )
            {  
                return true;
            }
        }

    public function deletemultiple($id)
    {        
        $this->db->where_in('Id', $id);
        $delete = $this->db->delete('tbl_customer');        
        if( $delete == true)
        {  
            return true;
        }
        else
        {
            return false;
        }
    }

    public function get_country()
    {
        return $this->db->get_where('tbl_countries')->result();

    }

    

    public function get_country_by_state($cid)
    {

        return $this->db->get_where('tbl_states',array('country_id' => $cid))->result();
    }

    public function get_state_by_city($sid)
    {

        return $this->db->get_where('tbl_cities',array('state_id' => $sid))->result();
    }

    public function staffmultipledelete($id)
    {        
        $this->db->where_in('id', $id);
        $delete = $this->db->delete('tbl_users');        
        if( $delete == true)
        {  
            return true;
        }
        else
        {
            return false;
        }
    }

    public function staffdelete($id)
        {
            if( $this->db->delete('tbl_users',array('id' => $id)) )
            {  
                return true;
            }
        }

    public function staffchangestatus($id,$status)
        {
            $updateData = array(
                                        'status' => $status
                );                          
            if($this->db->update('tbl_users',$updateData,array('id' => $id)) )
            {  
                return true;
            }
        }

    public function userpassword_update($custmerid)
        {
            return $this->db->update('tbl_users', array('password' => md5($this->input->post('password'))), array('id' => $custmerid));
        }

    public function get_user_data($custmerid)
        {

            return $this->db->get_where('tbl_users',array('id'=>$custmerid))->row();
        }

}
?>
