<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Gallery_model Class
*
* @author Antaryami
* @created on 29-12-2017
* @version 1.0
* @description Gallery model written all business logics
* @link https://zoddl.com/
*/

class Gallery_model extends CI_Model {	

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
    * @Function:        <_get_datatables_query>
    * @Description:     <this function use for filtering>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */    


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

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <$customerid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Object Data>
    */

	public function get_datatables($customerid)
		{
			$this->_get_datatables_query();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$this->db->where('Customer_Id = ', $customerid);
	       	$this->db->group_by('Primary_Tag');		
			$query = $this->db->get();
			return $query->result();		
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered>
    * @Description:     <this function use for count data>
    * @Parameters:      <$customerid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_filtered($customerid)
		{
			$this->_get_datatables_query();
			$this->db->where('Customer_Id = ', $customerid);
	       	$this->db->group_by('Primary_Tag');	
			$query = $this->db->get();
			return $query->num_rows();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all>
    * @Description:     <this function use for count All data>
    * @Parameters:      <$customerid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_all($customerid)
		{
			$this->db->from($this->tagtable);
			$this->db->where('Customer_Id = ', $customerid);
	       	$this->db->group_by('Primary_Tag');			
			return $this->db->count_all_results();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_primary>
    * @Description:     <this function use for get the primary tag name>
    * @Parameters:      <$primary>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <string>
    */		
	
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

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_secondary>
    * @Description:     <this function use for get the secondary tag name>
    * @Parameters:      <$secondry>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <string>
    */	

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

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_gallery_image>
    * @Description:     <this function use for get the all image according to primary tag id or secondary tag id>
    * @Parameters:      <$primaryid, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */		

	public function get_gallery_image($primaryid, $secondarytagid = '')
		{
			if($secondarytagid == '')
			{
				$image = $this->db->get_where('tbl_tag', array('Primary_Tag' => $primaryid));
			}
			else
			{
				$image = $this->db->query("SELECT * FROM tbl_tag WHERE Primary_Tag = $primaryid AND FIND_IN_SET($secondarytagid,Secondry_Tag)");

			}
			$getGalleryimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{
						if(empty($imagedata->Image_Url_Thumb))
	                            {
	                                $getGalleryimageData  []= '<img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32">';
	                            }
	                            else
	                            {
	                                $getGalleryimageData []= '<img src="'.$imagedata->Image_Url_Thumb.'" alt="Gallery Img" width="32" height="32">';

	                            }

				}
				return $getGalleryimageData;

			}
			else
			{

				return false;
			}

		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_secondary_gallery_image>
    * @Description:     <this function use for get the all image according to primary tag id, secondary tag id and customer id>
    * @Parameters:      <$secondryid, $customerid, $primarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */

	public function get_secondary_gallery_image($secondryid, $customerid, $primarytagid)
		{
			$image = $this->db->query("SELECT * FROM tbl_tag WHERE FIND_IN_SET($secondryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid");
			
			$getGalleryimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{
						if(empty($imagedata->Image_Url_Thumb))
	                            {
	                                $getGalleryimageData  []= '<img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32">';
	                            }
	                            else
	                            {
	                                $getGalleryimageData []= '<img src="'.$imagedata->Image_Url_Thumb.'" alt="Gallery Img" width="32" height="32">';

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

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <_get_datatables_query_primary>
    * @Description:     <this function use for filtering>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

	private function _get_datatables_query_primary()
		{
			
			$this->db->from('tbl_secondary_tag');

			$i = 0;
		
			foreach ($this->secondorytagcolumn as $item) 
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
	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables_primary>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <$customerid,$uniqeValue>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Object>
    */

	public function get_datatables_primary($customerid,$uniqeValue)
		{
			$this->_get_datatables_query_primary();
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$this->db->where('Customer_Id = ', $customerid);
			$this->db->where_in('Id', $uniqeValue);
			$query = $this->db->get();
			return $query->result();		
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered_primary>
    * @Description:     <this function use for count data>
    * @Parameters:      <$customerid,$uniqeValue>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_filtered_primary($customerid,$uniqeValue)
		{
			$this->_get_datatables_query_primary();
			$this->db->where('Customer_Id = ', $customerid);
			$this->db->where_in('Id', $uniqeValue);	
			$query = $this->db->get();
			return $query->num_rows();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all>
    * @Description:     <this function use for count All data>
    * @Parameters:      <$customerid,$uniqeValue>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_all_primary($customerid,$uniqeValue)
		{
			$this->db->from('tbl_secondary_tag');
			$this->db->where('Customer_Id = ', $customerid);
			$this->db->where_in('Id', $uniqeValue);						
			return $this->db->count_all_results();
		}	

	/** End search by primary tag **/


	/**   start coding for search by socondary tag **/

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <_get_datatables_query_socondary>
    * @Description:     <this function use for filtering>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

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

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables_secondary>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <$customerid, $secondarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Object>
    */

	public function get_datatables_secondary($customerid, $secondarytagid)
		{
			$this->_get_datatables_query_socondary();

			$where = "Customer_Id = $customerid AND FIND_IN_SET($secondarytagid,Secondry_Tag)";
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);		
			$this->db->where($where);		
	       	$this->db->group_by('Primary_Tag');		
			$query = $this->db->get();		
			return $query->result();		
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered_secondary>
    * @Description:     <this function use for count data>
    * @Parameters:      <$customerid, $secondarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_filtered_secondary($customerid, $secondarytagid)
		{
			$this->_get_datatables_query_socondary();
			$where = "Customer_Id = $customerid AND FIND_IN_SET($secondarytagid,Secondry_Tag)";
			$this->db->where($where);
	       	$this->db->group_by('Primary_Tag');	
			$query = $this->db->get();
			return $query->num_rows();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all_secondary>
    * @Description:     <this function use for count All data>
    * @Parameters:      <$searchkeyword = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_all_secondary($customerid, $secondarytagid)
		{
			$where = "Customer_Id = $customerid AND FIND_IN_SET($secondarytagid,Secondry_Tag)";
			$this->db->from($this->tagtable);
			$this->db->where($where);	
	       	$this->db->group_by('Primary_Tag');			
			return $this->db->count_all_results();
		}	

	/** End search by socondary tag **/


	/**   start coding for primary tag view detail **/

	 /**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <_get_datatables_query_primary_view>
    * @Description:     <this function use for filtering>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

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

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables_primary_view>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <$customerid, $primarytagid, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Object>
    */

	public function get_datatables_primary_view($customerid, $primarytagid, $secondarytagid = '')
		{
			$this->_get_datatables_query_primary_view();
			if($secondarytagid != '')
			{
				$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid AND FIND_IN_SET($secondarytagid,Secondry_Tag)";
			}
			else
			{
				$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid";

			}
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$this->db->where($where);
	       	$this->db->group_by('Tag_Date_int');		
			$query = $this->db->get();			
			return $query->result();		
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered_primary_view>
    * @Description:     <this function use for count data>
    * @Parameters:      <$customerid, $primarytagid, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	
	public function count_filtered_primary_view($customerid, $primarytagid, $secondarytagid = '')
		{
			$this->_get_datatables_query_primary_view();
			if($secondarytagid != '')
			{
				$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid AND FIND_IN_SET($secondarytagid,Secondry_Tag)";
			}
			else
			{
				$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid";

			}
			$this->db->where($where);
	       	$this->db->group_by('Tag_Date_int');	
			$query = $this->db->get();
			return $query->num_rows();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all_primary_view>
    * @Description:     <this function use for count All data>
    * @Parameters:      <$customerid, $primarytagid, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

	public function count_all_primary_view($customerid, $primarytagid, $secondarytagid = '')
		{
			if($secondarytagid != '')
			{
				$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid AND FIND_IN_SET($secondarytagid,Secondry_Tag)";
			}
			else
			{
				$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid";

			}
			$this->db->from($this->tagtable);
			$this->db->where($where);
	       	$this->db->group_by('Tag_Date_int');		
			return $this->db->count_all_results();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_date_by_gallery_image>
    * @Description:     <this function use for get all image according to date by>
    * @Parameters:      <$customerid, $primaryid, $tagdate, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */

	public function get_date_by_gallery_image($customerid, $primaryid, $tagdate, $secondarytagid = '')
		{
			if($secondarytagid != '')
			{
			 $image = $this->db->query("SELECT * FROM tbl_tag WHERE FIND_IN_SET($secondarytagid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");
			}
			else
			{
				$image = $this->db->query("SELECT * FROM tbl_tag WHERE Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");	
			}
			$getGalleryimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{
						if(empty($imagedata->Image_Url_Thumb))
	                            {
	                                $getGalleryimageData  []= '<a href="'.base_url('imagegallery/image_data_entry/'.$imagedata->Id.'/'.$imagedata->Customer_Id).'" target="_blank"><img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32"></a>';
	                            }
	                            else
	                            {
	                                $getGalleryimageData []= '<a href="'.base_url('imagegallery/image_data_entry/'.$imagedata->Id.'/'.$imagedata->Customer_Id).'" target="_blank"><img src="'.$imagedata->Image_Url_Thumb.'" alt="Gallery Img" width="32" height="32"></a>';

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

	 /**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <_get_datatables_query_secondory_view>
    * @Description:     <this function use for filtering>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

	
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
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables_secondory_view>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <$customerid, $secondorytagid, $primarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <Object>
    */

    public function get_datatables_secondory_view($customerid, $secondorytagid, $primarytagid)
		{
			$this->_get_datatables_query_secondory_view();
			$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid AND FIND_IN_SET($secondorytagid,Secondry_Tag)";
			if($_POST['length'] != -1)
			$this->db->limit($_POST['length'], $_POST['start']);
			$this->db->where($where);
	       	$this->db->group_by('Tag_Date_int');		
			$query = $this->db->get();		
			return $query->result();		
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered_secondory_view>
    * @Description:     <this function use for count data>
    * @Parameters:      <$customerid, $secondorytagid, $primarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */	


    public function count_filtered_secondory_view($customerid, $secondorytagid, $primarytagid)
		{
			$this->_get_datatables_query_secondory_view();
			$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid AND FIND_IN_SET($secondorytagid,Secondry_Tag)";
			$this->db->where($where);
	       	$this->db->group_by('Tag_Date_int');	
			$query = $this->db->get();
			return $query->num_rows();
		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all_secondory_view>
    * @Description:     <this function use for count All data>
    * @Parameters:      <$searchkeyword = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <number of rows>
    */

    public function count_all_secondory_view($customerid, $secondorytagid, $primarytagid)
		{
			$where = "Customer_Id = $customerid AND Primary_Tag = $primarytagid AND FIND_IN_SET($secondorytagid,Secondry_Tag)";
			$this->db->from($this->tagtable);		
			$this->db->where($where);
	       	$this->db->group_by('Tag_Date_int');		
			return $this->db->count_all_results();
		}
	
	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_date_by_gallery_image2>
    * @Description:     <this function use for get all image according to date by>
    * @Parameters:      <$customerid, $secondoryid, $tagdate, $primarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */

	public function get_date_by_gallery_image2($customerid, $secondoryid, $tagdate, $primarytagid)
		{		
			
			$image = $this->db->query("SELECT * FROM tbl_tag WHERE FIND_IN_SET($secondoryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid AND Tag_Date_int = $tagdate");
			$getGalleryimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{
						if(empty($imagedata->Image_Url_Thumb))
	                            {
	                                $getGalleryimageData  []= '<a href="'.base_url('imagegallery/image_data_entry/'.$imagedata->Id.'/'.$imagedata->Customer_Id).'" target="_blank"><img src="'.base_url('backend/assets/pages/img/dummy.png').'" alt="Smiley face" width="32" height="32"></a>';
	                            }
	                            else
	                            {
	                                $getGalleryimageData []= '<a href="'.base_url('imagegallery/image_data_entry/'.$imagedata->Id.'/'.$imagedata->Customer_Id).'" target="_blank"><img src="'.$imagedata->Image_Url_Thumb.'" alt="Gallery Img" width="32" height="32"></a>';

	                            }

				}
				return $getGalleryimageData;

			}
			else
			{

				return false;
			}

		}	

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_amount_secondory_tag>
    * @Description:     <this function use for get amount according to secondary tag>
    * @Parameters:      <$customerid, $secondoryid, $tagdate, $primarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */
	

	public function get_amount_secondory_tag($customerid, $secondoryid, $tagdate, $primarytagid)
		{
			
			
			$amount = $this->db->query("SELECT * FROM tbl_tag WHERE FIND_IN_SET($secondoryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid AND Tag_Date_int = $tagdate");
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

		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <29-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_amount_primary_tag>
    * @Description:     <this function use for get amount according to primary tag>
    * @Parameters:      <$customerid, $primaryid, $tagdate, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */
	

	public function get_amount_primary_tag($customerid, $primaryid, $tagdate, $secondarytagid = '')
		{
			
			
			if($secondarytagid != '')
			{
			    $amount = $this->db->query("SELECT * FROM tbl_tag WHERE FIND_IN_SET($secondarytagid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");
			}
			else
			{

				$amount = $this->db->query("SELECT * FROM tbl_tag WHERE Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");
			}

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

		}

	
	public function get_record_by_id($table, $data)
	    {
	        $query = $this->db->get_where($table, $data);
	        return $query->row();   
	    }

    public function get_all_record_by_condition($table, $data)
	    {  
	        $query = $this->db->get_where($table, $data);
	        return $query->result();
	    }

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

    public function get_tag_nxt_id($uri_segment,$customerId = '')
	    {
	    	if($customerId === '')
	    	   {
	    	   	    $querydata = $this->db->query("SELECT * FROM `tbl_tag` WHERE 1 = 1 AND Id > $uri_segment AND (Tag_Type = '' or Tag_Send_Date = '' or Amount IS NULL or Primary_Tag = 0 ) ");

	    	   	    if($querydata->num_rows() > 0 )
	        		{

	        			$query = $this->db->query("SELECT * FROM `tbl_tag` WHERE 1 = 1 AND Id > $uri_segment AND (Tag_Type = '' or Tag_Send_Date = '' or Amount IS NULL or Primary_Tag = 0 ) ");
	        		}
	        		else
	        		{
	        			$query = $this->db->query("SELECT * FROM `tbl_tag` WHERE 1 = 1 AND Id < $uri_segment AND (Tag_Type = '' or Tag_Send_Date = '' or Amount IS NULL or Primary_Tag = 0 ) ");

	        		}

	        	}
	        	else
	        	{

	        		$querydata = $this->db->query("SELECT * FROM `tbl_tag` WHERE 1 = 1 AND Customer_Id = $customerId AND (Id > $uri_segment) LIMIT 1");

	        		if($querydata->num_rows() > 0 )
	        		{

	        			$query = $this->db->query("SELECT * FROM `tbl_tag` WHERE 1 = 1 AND Customer_Id = $customerId AND (Id > $uri_segment) LIMIT 1");

	        		}
	        		else
	        		{
	        			$query = $this->db->query("SELECT * FROM `tbl_tag` WHERE 1 = 1 AND Customer_Id = $customerId AND (Id < $uri_segment) LIMIT 1");

	        		}

	        	}

	        	//echo $this->db->last_query(); die;
	        return $query->result();	
	    }

    public function get_tag_previous_id($uri_segment)
	    {
	    	$query = $this->db->query("Select * from `tbl_tag` where id = (select max(id) from `tbl_tag` where id < $uri_segment)");
	        return $query->result();	
	    }

	  
	  /* date 2 april 2018 */

	 public function get_customer()
	 {
	 		//return $this->db->get_where('tbl_customer')->result();

	 		$resultData = array();

	 		$this->db->select('Email_Id');
            $this->db->from('tbl_customer');
            $this->db->where('Email_Id !=','');                      
            $emailquery  = $this->db->get();
            
            if($emailquery->num_rows() > 0)
            {
                foreach($emailquery->result() as $email)
                {
                    $emailResult[] = $email->Email_Id;
                }
            }


            $this->db->select('Customer_Name');
            $this->db->from('tbl_customer');
            $this->db->where('Customer_Name !=','');                      
            $namequery  = $this->db->get();
            
            if($namequery->num_rows() > 0)
            {
                foreach($namequery->result() as $name)
                {
                    $nameResult[] = $name->Customer_Name;
                }
            }

            $this->db->select('Phone_No');
            $this->db->from('tbl_customer');
            $this->db->where('Phone_No !=','');                      
            $phonequery  = $this->db->get();
            
            if($phonequery->num_rows() > 0)
            {
                foreach($phonequery->result() as $phone)
                {
                    $phoneResult[] = $phone->Phone_No;
                }
            }

            $resultData = array_merge($nameResult, $emailResult, $phoneResult);

            return json_encode($resultData);

            
	 }

	 public function viewallkeyword($keyword)
	 {

	 	$cusResult = array();
	 	$this->db->like('Customer_Name', $keyword);
	 	$this->db->or_like('Email_Id', $keyword);
	 	$this->db->or_like('Phone_No', $keyword);
	 	$query = $this->db->select('Customer_Name, Email_Id, Phone_No, Id')->get_where('tbl_customer');

	 	if($query->num_rows() > 0)
            {
            		$result = $query->row();                
                    $cusResult['id'] = $result->Id;
                    $cusResult['name'] = $result->Customer_Name;
                    $cusResult['email'] = $result->Email_Id;
                    $cusResult['phone'] = $result->Phone_No;

               
            }

            return json_encode($cusResult);



	 }

    
}
 
?>