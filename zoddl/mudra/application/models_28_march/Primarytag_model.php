<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Primarytag_model Class
*
* @author Antaryami
* @created on 29-12-2017
* @version 1.0
* @description Primary tag model written all business logics
* @link https://zoddl.com/
*/

class Primarytag_model extends CI_Model {


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

	var $primarytagtable = 'tbl_primary_tag';
	var $primarytagcolumn = array('Customer_Id','Prime_Name');
	var $primarytagorder = array('Id' => 'desc');	
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
    * @Function:        <_get_datatables_query>
    * @Description:     <this function use for filtering>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */    


	private function _get_datatables_query()
		{
			
			$this->db->from($this->primarytagtable,'tbl_customer');

			$i = 0;
		
			foreach ($this->primarytagcolumn as $item) 
			{
				if($_POST['search']['value'])
					($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
				$column[$i] = $item;
				$i++;

			}
			
			if(isset($_POST['order']))
			{
				$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
			} 
			else if(isset($this->primarytagorder))
			{
				$order = $this->primarytagorder;
				$this->db->order_by(key($order), $order[key($order)]);
			}

		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Object>
    */

	public function get_datatables()
		{
			$this->_get_datatables_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);		
			$query = $this->db->get();						
			return $query->result();		
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered>
    * @Description:     <this function use for count data>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_filtered()
		{
			$this->_get_datatables_query();		
			$query = $this->db->get();
			return $query->num_rows();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all>
    * @Description:     <this function use for count All data>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_all()
		{
			$this->db->from($this->primarytagtable);		
			return $this->db->count_all_results();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_customer>
    * @Description:     <this function use for get customer email>
    * @Parameters:      <$customerid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <String>
    */

	public function get_customer($customerid)
		{

			$numCustomer = $this->db->get_where('tbl_customer', array('Id' => $customerid));
			if($numCustomer->num_rows() > 0)
			{

				$customerdata = $numCustomer->row();
				return $customerdata->Email_Id;
			}
			else
			{
				return false;
			}
		}

}
 
?>