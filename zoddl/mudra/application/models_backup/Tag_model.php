<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Tag_model extends CI_Model {	
	var $tagtable = 'tbl_tag';
	var $tagcolumn = array('Customer_Id','Primary_Tag');
	var $tagorder = array('Id' => 'desc');	
    function __construct()
    {
        parent::__construct();
        $this->load->helper('language');
		$this->load->database();
    }     


	private function _get_datatables_query()
	{
		
		$this->db->from($this->tagtable);

		$i = 0;
	
		foreach ($this->tagcolumn as $item) 
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
		else if(isset($this->tagorder))
		{
			$order = $this->tagorder;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);		
		$query = $this->db->get();				
		return $query->result();		
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->db->from($this->tagtable);		
		return $this->db->count_all_results();
	}

	public function changestatus($id,$status)
	{
		$updateData = array(
								'Tag_Status' => $status
			);							
		if($this->db->update($this->tagtable,$updateData,array('Id' => $id)) )
		{  
			return true;
		}
	}	

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

	public function get_primary($primary)
	{

		$numprimary = $this->db->get_where('tbl_primary_tag', array('Id' => $primary));

		if($numprimary->num_rows() > 0)
		{

			$primarydata = $numprimary->row();
			return $primarydata->Prime_Name;

		}
		else
		{

			return false;
		}
	}	

	public function get_secondry($secondry)
	{

		$numsecondry = $this->db->get_where('tbl_secondary_tag', array('Id' => $secondry));

		if($numsecondry->num_rows() > 0)
		{

			$secondrydata = $numsecondry->row();
			return $secondrydata->Secondary_Name;

		}
		else
		{

			return false;
		}
	}	
    
}
 
?>