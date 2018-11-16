<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gallery_model extends CI_Model {	
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

	function get_datatables($customerid)
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('Customer_Id = ', $customerid);
       	$this->db->group_by('Primary_Tag');		
		$query = $this->db->get();
		return $query->result();		
	}

	function count_filtered($customerid)
	{
		$this->_get_datatables_query();
		$this->db->where('Customer_Id = ', $customerid);
       	$this->db->group_by('Primary_Tag');	
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all($customerid)
	{
		$this->db->from($this->tagtable);
		$this->db->where('Customer_Id = ', $customerid);
       	$this->db->group_by('Primary_Tag');			
		return $this->db->count_all_results();
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

	public function get_secondary($secondry)
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

	
	public function get_customerprimarytag($custmerid)
	{
			$this->db->select('pt.*,t.*');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $custmerid);
       		$this->db->group_by('t.Primary_Tag');       		
       		$query = $this->db->get();       		
       		return $query->result();

	}	

	public function get_customersecondarytag($custmerid)
	{
			$this->db->select('st.*,t.*');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_secondary_tag st','t.Secondry_Tag	= st.Id','left');
       		$this->db->where('t.Customer_Id = ', $custmerid);
       		$this->db->group_by('t.Secondry_Tag');       		
       		$query = $this->db->get();
       		//echo $this->db->last_query(); die;
       		return $query->result();

	}	

	public function get_gallery_image($primaryid)
	{
		$image = $this->db->get_where('tbl_tag', array('Primary_Tag' => $primaryid));
		$getGalleryimageData = array();
		if($image->num_rows() > 0)
		{

			$imagedata = $image->result();

			foreach($imagedata as $imagedata)
			{
					if(empty($imagedata->Image_Url))
                            {
                                $getGalleryimageData  []= '<img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32">';
                            }
                            else
                            {
                                $getGalleryimageData []= '<img src="'.$imagedata->Image_Url.'" alt="Gallery Img" width="32" height="32">';

                            }

			}
			return $getGalleryimageData;

		}
		else
		{

			return false;
		}

	}

	public function get_secondary_gallery_image($secondryid)
	{
		$image = $this->db->get_where('tbl_tag', array('Secondry_Tag' => $secondryid));
		$getGalleryimageData = array();
		if($image->num_rows() > 0)
		{

			$imagedata = $image->result();

			foreach($imagedata as $imagedata)
			{
					if(empty($imagedata->Image_Url))
                            {
                                $getGalleryimageData  []= '<img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32">';
                            }
                            else
                            {
                                $getGalleryimageData []= '<img src="'.$imagedata->Image_Url.'" alt="Gallery Img" width="32" height="32">';

                            }

			}
			return $getGalleryimageData;

		}
		else
		{

			return false;
		}

	}


	/**   start coding for search by primary tag **/

	private function _get_datatables_query_primary()
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

	function get_datatables_primary($customerid, $primarytagid)
	{
		$this->_get_datatables_query_primary();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Primary_Tag = ', $primarytagid);
       	$this->db->group_by('Secondry_Tag');		
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		return $query->result();		
	}

	function count_filtered_primary($customerid, $primarytagid)
	{
		$this->_get_datatables_query_primary();
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Primary_Tag = ', $primarytagid);
       	$this->db->group_by('Secondry_Tag');	
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_primary($customerid, $primarytagid)
	{
		$this->db->from($this->tagtable);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Primary_Tag = ', $primarytagid);
       	$this->db->group_by('Secondry_Tag');			
		return $this->db->count_all_results();
	}	

	/** End search by primary tag **/


	/**   start coding for search by socondary tag **/

	private function _get_datatables_query_socondary()
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

	function get_datatables_secondary($customerid, $secondarytagid)
	{
		$this->_get_datatables_query_socondary();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Secondry_Tag = ', $secondarytagid);
       	$this->db->group_by('Primary_Tag');		
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		return $query->result();		
	}

	function count_filtered_secondary($customerid, $secondarytagid)
	{
		$this->_get_datatables_query_socondary();
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Secondry_Tag = ', $secondarytagid);
       	$this->db->group_by('Primary_Tag');	
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_secondary($customerid, $secondarytagid)
	{
		$this->db->from($this->tagtable);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Secondry_Tag = ', $secondarytagid);
       	$this->db->group_by('Primary_Tag');			
		return $this->db->count_all_results();
	}	

	/** End search by socondary tag **/


	/**   start coding for primary tag view detail **/

	private function _get_datatables_query_primary_view()
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

	function get_datatables_primary_view($customerid, $primarytagid)
	{
		$this->_get_datatables_query_primary_view();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Primary_Tag = ', $primarytagid);
       	$this->db->group_by('Tag_Date_int');		
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		return $query->result();		
	}

	
	function count_filtered_primary_view($customerid, $primarytagid)
	{
		$this->_get_datatables_query_primary_view();
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Primary_Tag = ', $primarytagid);
       	$this->db->group_by('Tag_Date_int');	
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_primary_view($customerid, $primarytagid)
	{
		$this->db->from($this->tagtable);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Primary_Tag = ', $primarytagid);
       	$this->db->group_by('Tag_Date_int');		
		return $this->db->count_all_results();
	}

	public function get_date_by_gallery_image($customerid, $primaryid, $tagdate)
	{
		
		$image = $this->db->get_where('tbl_tag', array('Customer_Id' => $customerid, 'Primary_Tag' => $primaryid, 'Tag_Date_int' => $tagdate));
		
		$getGalleryimageData = array();
		if($image->num_rows() > 0)
		{

			$imagedata = $image->result();

			foreach($imagedata as $imagedata)
			{
					if(empty($imagedata->Image_Url))
                            {
                                $getGalleryimageData  []= '<a href="'.base_url('imagegallery/image_data_entry/'.$imagedata->Id).'" target="_blank"><img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32"></a>';
                            }
                            else
                            {
                                $getGalleryimageData []= '<a href="'.base_url('imagegallery/image_data_entry/'.$imagedata->Id).'" target="_blank"><img src="'.$imagedata->Image_Url.'" alt="Gallery Img" width="32" height="32"></a>';

                            }

			}
			return $getGalleryimageData;

		}
		else
		{

			return false;
		}

	}	

	/** End  primary tag view detail **/

	//abhinav function for secondory view
		private function _get_datatables_query_secondory_view()
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
	}//ends abhinav function

	//abhinav function count secondory view

	public function count_all_secondory_view($customerid, $secondorytagid)
	{
		$this->db->from($this->tagtable);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Secondry_Tag = ', $secondorytagid);
       	$this->db->group_by('Tag_Date_int');		
		return $this->db->count_all_results();
	}//ends abhinav

	//abhinav function secondory view data

	function get_datatables_secondory_view($customerid, $secondorytagid)
	{
		$this->_get_datatables_query_secondory_view();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Secondry_Tag = ', $secondorytagid);
       	$this->db->group_by('Tag_Date_int');		
		$query = $this->db->get();
		//echo $this->db->last_query(); die;
		return $query->result();		
	}//ends abhinav

	//abhinav function count filtered secondory 
	function count_filtered_secondory_view($customerid, $secondorytagid)
	{
		$this->_get_datatables_query_secondory_view();
		$this->db->where('Customer_Id = ', $customerid);
		$this->db->where('Secondry_Tag = ', $secondorytagid);
       	$this->db->group_by('Tag_Date_int');	
		$query = $this->db->get();
		return $query->num_rows();
	}//ends abhinav function

	//abhinav function getting gallary image

	public function get_date_by_gallery_image2($customerid, $secondoryid, $tagdate)
	{
		
		$image = $this->db->get_where('tbl_tag', array('Customer_Id' => $customerid, 'Secondry_Tag' => $secondoryid, 'Tag_Date_int' => $tagdate));
		
		$getGalleryimageData = array();
		if($image->num_rows() > 0)
		{

			$imagedata = $image->result();

			foreach($imagedata as $imagedata)
			{
					if(empty($imagedata->Image_Url))
                            {
                                $getGalleryimageData  []= '<a href="'.base_url('Imagegallery/image_data_entry/'.$imagedata->Id).'" target="_blank"><img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32"></a>';
                            }
                            else
                            {
                                $getGalleryimageData []= '<a href="'.base_url('Imagegallery/image_data_entry/'.$imagedata->Id).'" target="_blank"><img src="'.$imagedata->Image_Url.'" alt="Gallery Img" width="32" height="32"></a>';

                            }

			}
			return $getGalleryimageData;

		}
		else
		{

			return false;
		}

	}//ends abhinav function	

	//abhinav function getting amount secondory tag

		public function get_amount_secondory_tag($customerid, $secondoryid, $tagdate)
	{
		
		$amount = $this->db->get_where('tbl_tag', array('Customer_Id' => $customerid, 'Secondry_Tag' => $secondoryid, 'Tag_Date_int' => $tagdate));
		
		$getamountData = array();
		if($amount->num_rows() > 0)
		{

			$amountdata = $amount->result();

			foreach($amountdata as $amountdataa)
			{
					if(empty($amountdataa->Amount))
                            {
                                $gettingAmountimageData  []= '';
                            }
                            else
                            {
                                $gettingAmountimageData []= $amountdataa->Amount;

                            }

			}
			return $gettingAmountimageData;

		}
		else
		{

			return false;
		}

	}//ends abhinav function

	//abhinav function getting amount primary tag

		public function get_amount_primary_tag($customerid, $secondoryid, $tagdate)
	{
		
		$amount = $this->db->get_where('tbl_tag', array('Customer_Id' => $customerid, 'Primary_Tag' => $secondoryid, 'Tag_Date_int' => $tagdate));
		
		$getamountData = array();
		if($amount->num_rows() > 0)
		{

			$amountdata = $amount->result();

			foreach($amountdata as $amountdataa)
			{
					if(empty($amountdataa->Amount))
                            {
                                $gettingAmountimageData  []= '';
                            }
                            else
                            {
                                $gettingAmountimageData []= $amountdataa->Amount;

                            }

			}
			return $gettingAmountimageData;

		}
		else
		{

			return false;
		}

	}//ends abhinav function

	//abhinav function get record by id
	 public function get_record_by_id($table, $data)
    {
        $query = $this->db->get_where($table, $data);
        return $query->row();   
    }//ends abhinav function

    //abhinav function get all records

    public function get_all_record_by_condition($table, $data)
    {  
        $query = $this->db->get_where($table, $data);
        return $query->result();
    }

    //abhinav function insert records

    public function insert_one_row($table, $data)
    {
        $query = $this->db->insert($table, $data);
        return $this->db->insert_id();
    }

    public function delete_record_by_id($table, $where)
    {
        $query = $this->db->delete($table,$where);
        return $this->db->affected_rows();
    }

    
}
 
?>