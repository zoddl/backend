<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* secondarytag_model Class
*
* @author Antaryami
* @created on 29-12-2017
* @version 1.0
* @description Secondary tag model written all business logics
* @link https://zoddl.com/
*/

class secondarytag_model extends CI_Model {	
	var $secondarytagtable = 'tbl_secondary_tag';
	var $secondarytagcolumn = array('Customer_Id','Secondary_Name');
	var $secondarytagorder = array('Id' => 'desc');

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
		
		$this->db->from($this->secondarytagtable);

		$i = 0;
	
		foreach ($this->secondarytagcolumn as $item) 
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
		else if(isset($this->secondarytagorder))
		{
			$order = $this->secondarytagorder;
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

	function get_datatables()
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

	function count_filtered()
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
		$this->db->from($this->secondarytagtable);		
		return $this->db->count_all_results();
	}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_customer>
    * @Description:     <this function use for get the customer data>
    * @Parameters:      <$customerid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <string>
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