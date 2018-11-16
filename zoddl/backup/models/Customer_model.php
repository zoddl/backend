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
                                    'Phone_No'=> $this->input->post('Phone_No'),
                                    'Alt_Phone_No'=> $this->input->post('Alt_Phone_No'),
                                    'Aadhar_No'=> $this->input->post('Aadhar_No'),
                                    'Skype_Id'=> $this->input->post('Skype_Id'),
                                    'Facebook_Profile'=> $this->input->post('Facebook_Profile'),
                                    'Linked_In_Profile'=> $this->input->post('Linked_In_Profile'),
                                    'Twitter_Handle'=> $this->input->post('Twitter_Handle')
                                );
                                     
                 $updateRec= $this->db->update('tbl_customer',$updateData,array('Id'=> $custmerid));
                 
                 return $updateRec;

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

}
?>
