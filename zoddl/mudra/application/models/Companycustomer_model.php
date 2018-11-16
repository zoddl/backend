<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* Companycustomer_model Class
*
* @author Antaryami
* @created on 28-12-2017
* @version 1.0
* @description Company customer model written all business logics
* @link https://zoddl.com/
*/

class Companycustomer_model extends CI_Model {	


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


	var $customertable = 'tbl_customer';
	var $customercolumn = array('Id','Email_Id');
	var $customerorder = array('Id' => 'desc');	
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
    * @Function:        <_get_datatables_query>
    * @Description:     <this function use for filtering>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */     


	private function _get_datatables_query()
		{
			
			$this->db->from($this->customertable);

			$i = 0;
		
			foreach ($this->customercolumn as $item) 
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
			else if(isset($this->customerorder))
			{
				$order = $this->customerorder;
				$this->db->order_by(key($order), $order[key($order)]);
			}
		}


	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <$searchkeyword = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Query Data>
    */


	public function get_datatables($searchkeyword = '')
		{
			$this->_get_datatables_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$this->db->where('User_Type', '3');	
			if(!is_numeric($searchkeyword))
			{	
				$where = "(Customer_Name LIKE '%".$searchkeyword."%' OR Email_Id LIKE '%".$searchkeyword."%' OR Phone_No LIKE '%".$searchkeyword."%')";
				$this->db->where($where);
			}
			elseif(is_numeric($searchkeyword))
			{
				$this->db->where('Status',$searchkeyword);

			}	
			$query = $this->db->get();				
			return $query->result();		
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered>
    * @Description:     <this function use for count data>
    * @Parameters:      <$searchkeyword = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_filtered($searchkeyword = '')
		{
			$this->_get_datatables_query();
			$this->db->where('User_Type', '3');
			if(!is_numeric($searchkeyword))
			{	
				$where = "(Customer_Name LIKE '%".$searchkeyword."%' OR Email_Id LIKE '%".$searchkeyword."%' OR Phone_No LIKE '%".$searchkeyword."%')";
				$this->db->where($where);
			}
			elseif(is_numeric($searchkeyword))
			{
				$this->db->where('Status',$searchkeyword);

			}
			$query = $this->db->get();
			return $query->num_rows();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all>
    * @Description:     <this function use for count All data>
    * @Parameters:      <$searchkeyword = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_all($searchkeyword = '')
		{
			$this->db->from($this->customertable);
			$this->db->where('User_Type', '3');	
			if(!is_numeric($searchkeyword))
			{	
				$where = "(Customer_Name LIKE '%".$searchkeyword."%' OR Email_Id LIKE '%".$searchkeyword."%' OR Phone_No LIKE '%".$searchkeyword."%')";
				$this->db->where($where);
			}
			elseif(is_numeric($searchkeyword))
			{
				$this->db->where('Status',$searchkeyword);

			}	
			return $this->db->count_all_results();
		}

	
}
 
?>