<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Report_model Class
*
* @author Antaryami
* @created on 29-12-2017
* @version 1.0
* @description Report model written all business logics
* @link https://zoddl.com/
*/

class Report_model extends CI_Model {	

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <__construct>
    * @Description:     <this function load database>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */	

	var $tagtable = 'tbl_tag';
	var $tagcolumn = array('Customer_Id','Primary_Tag');
	var $tagorder = array('Id' => 'desc');
	var $secondorytagcolumn = array('Id','Customer_Id','Secondary_Name');	
    function __construct()
    {
        parent::__construct();
        $this->load->helper('language');
		$this->load->database();
    }     

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_customerprimarytag>
    * @Description:     <this function use for get the primary tag according to customer>
    * @Parameters:      <$custmerid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <object>
    */		

	
	public function get_customerprimarytag($custmerid)
		{
				$query = $this->db->get_where('tbl_primary_tag', array('Customer_Id' => $custmerid));			   		
	       		return $query->result();

		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_customersecondarytag>
    * @Description:     <this function use for get the secondary tag according to customer>
    * @Parameters:      <$custmerid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <object>
    */	

	public function get_customersecondarytag($custmerid)
		{				
				$query = $this->db->get_where('tbl_secondary_tag', array('Customer_Id' => $custmerid));
	       		return $query->result();
		}
    
}
 
?>