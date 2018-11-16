<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');



class Entry_model extends CI_Model {	

	
    function __construct()
    {
        parent::__construct();
        $this->load->helper('language');
		$this->load->database();
    } 


    function get_tag_information($id)
    {

    	$this->db->select('t.*, c.Customer_Name, c.Email_Id, c.Phone_No, p.Prime_Name,s.Secondary_Name');
		$this->db->from('tbl_tag t');
		$this->db->join('tbl_customer c','t.Customer_Id	= c.Id','left');
		$this->db->join('tbl_primary_tag p','t.Primary_Tag	= p.Id','left');
		$this->db->join('tbl_secondary_tag s','t.Secondry_Tag = s.Id','left');
		$this->db->where('t.Id', $id);
		return $this->db->get()->row();

    }

    function get_count_data($customerid = '', $id = '',  $date = '', $tag = '', $primary = '', $secondary = '', $status = '', $amount = '', $description = '')
    {

    	  
			$this->db->select('t.*, p.Prime_Name, s.Secondary_Name');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag p','t.Primary_Tag	= p.Id','left');
       		$this->db->join('tbl_secondary_tag s','t.Secondry_Tag	= s.Id','left');       		
       		$this->db->where('t.Customer_Id', $customerid);
			
			if($tag != '')
			{
				$this->db->like('t.Tag_Type', $tag);
			}
			if($status != '')
			{
				$this->db->like('t.Tag_Status', $status);
			}
			if($primary != '')
			{
				$this->db->like('p.Prime_Name', $primary);
			}
			if($secondary != '')
			{
				$this->db->like('s.Secondary_Name', $secondary);
			}
			if($date != '')
			{
				$this->db->like('t.Tag_Send_Date', $date);
			}
			if($amount != '')
			{
				$this->db->like('t.Amount', $amount);
			}
			if($description != '')
			{
				$this->db->like('t.Description', $description);
			}
			
			$countdata = $this->db->get();			

			return $countdata->num_rows();


    }

    function get_result_data($customerid = '', $id = '',  $date = '', $tag = '', $primary = '', $secondary = '', $status = '', $amount = '', $description = '', $iDisplayLength = '', $iDisplayStart = '')
    {

    		$this->db->select('t.*, p.Prime_Name, s.Secondary_Name');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag p','t.Primary_Tag	= p.Id','left');
       		$this->db->join('tbl_secondary_tag s','t.Secondry_Tag	= s.Id','left');       		
       		$this->db->where('t.Customer_Id', $customerid);

			if($tag != '')
			{
				$this->db->like('t.Tag_Type', $tag);
			}
			if($status != '')
			{
				$this->db->like('t.Tag_Status', $status);
			}
			if($primary != '')
			{
				$this->db->like('p.Prime_Name', $primary);
			}
			if($secondary != '')
			{
				$this->db->like('s.Secondary_Name', $secondary);
			}
			if($date != '')
			{
				$this->db->like('t.Tag_Send_Date', $date);
			}
			if($amount != '')
			{
				$this->db->like('t.Amount', $amount);
			}
			if($description != '')
			{
				$this->db->like('t.Description', $description);
			}
			 
			$this->db->order_by("t.Id", "DESC");       		
       		$this->db->limit($iDisplayLength, $iDisplayStart);       		
       		$query = $this->db->get();
       		return $query->result();
    }

    public function insert_tag($tabletag,$data)
	{
	   	$this->db->insert($tabletag,$data);
		return true;	
	}

	public function update_tag($tabletag, $condition, $data)
	{

		$this->db->where('Id', $condition);
		$update_tag = $this->db->update($tabletag,$data);

	}

	public function get_customer_information($customerid)
	{

		return $this->db->get_where('tbl_customer', array('Id' => $customerid))->row();
	}    

    
	
    
}
 
?>