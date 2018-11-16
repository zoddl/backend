<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


/**
* Document_model Class
*
* @author Antaryami
* @created on 28-12-2017
* @version 1.0
* @description document model written all business logics
* @link https://zoddl.com/
*/

class Document_model extends CI_Model {

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

	var $tagtable = 'tbl_doc_tag';
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
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_datatables>
    * @Description:     <this function use for fetch tha data>
    * @Parameters:      <$customerid = ''>
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
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_filtered>
    * @Description:     <this function use for count data>
    * @Parameters:      <$customerid = ''>
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
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <count_all>
    * @Description:     <this function use for count All data>
    * @Parameters:      <$customerid = ''>
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
    * @Last modified:   <28-12-2017>
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

			$numprimary = $this->db->get_where('tbl_doc_primary_tag', array('Id' => $primary));

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
    * @Last modified:   <28-12-2017>
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

			$numsecondry = $this->db->get_where('tbl_doc_secondary_tag', array('Id' => $secondry));

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
    * @Last modified:   <28-12-2017>
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
				$query = $this->db->get_where('tbl_doc_primary_tag', array('Customer_Id' => $custmerid));			   		
	       		return $query->result();

		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
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
				
				$query = $this->db->get_where('tbl_doc_secondary_tag', array('Customer_Id' => $custmerid));

	       		return $query->result();

		}


	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_document_image>
    * @Description:     <this function use for get the all image according to primary tag id or secondary tag id>
    * @Parameters:      <$primaryid, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */		

	public function get_document_image($primaryid, $secondarytagid = '')
		{
			if($secondarytagid == '')
			{
				$image = $this->db->get_where('tbl_doc_tag', array('Primary_Tag' => $primaryid));
			}
			else
			{
				$image = $this->db->query("SELECT * FROM tbl_doc_tag WHERE Primary_Tag = $primaryid AND FIND_IN_SET($secondarytagid,Secondry_Tag)");

			}
			$getdocumentimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{
						
						if(empty($imagedata->Doc_Url))
						{

							$docurl = "javascript:void(0);";
							$targetBlank = '';

							$dummyimageurl = base_url('backend/assets/pages/img/dummy.png');
						}
						else
						{
							$docimageUrl = '';
							$docurl = base_url('document/preview/'.$imagedata->Id);
							$targetBlank = 'target="_blank"';
							$extension = pathinfo(parse_url($imagedata->Doc_Url, PHP_URL_PATH), PATHINFO_EXTENSION);
							$se = strtolower($extension);

							if($se == 'jpeg' || $se == 'png' || $se == 'gif' || $se == 'tiff' || $se == 'bmp' || $se == 'jpg')
							{

								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');
							}
							elseif($se == 'doc' || $se == 'docx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/document.png');

							}
							elseif($se == 'xls' || $se == 'xlsx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/excel.png');

							}
							elseif($se == 'ppt' || $se == 'pptx')
							{

								$docimageUrl = base_url('backend/assets/pages/img/powerpoint.png');
							}
							elseif($se == 'pdf')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pdf.png');
							}
							elseif($se == 'zip')
							{

								$docimageUrl = base_url('backend/assets/pages/img/zip.png');
							}
							elseif($se == 'pages')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pages.png');
							}
							else
							{
								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');

							} 

						}

						if(empty($imagedata->Doc_Url))
	                    {
	                        $getdocumentimageData  []= '<a href="'.$docurl.'" '.$targetBlank.'><img src="'.$dummyimageurl.'" alt="Dummy" width="32" height="32"></a>';
	                    }
	                    else
	                    {
	                        $getdocumentimageData []= '<a href="'.$docurl.'" '.$targetBlank.'><img src="'.$docimageUrl.'" alt="Document" width="32" height="32"></a>';

	                    }

				}
				return $getdocumentimageData;

			}
			else
			{

				return false;
			}

		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_secondary_document_image>
    * @Description:     <this function use for get the all image according to primary tag id, secondary tag id and customer id>
    * @Parameters:      <$secondryid, $customerid, $primarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */

	public function get_secondary_document_image($secondryid, $customerid, $primarytagid)
		{
			
			$image = $this->db->query("SELECT * FROM tbl_doc_tag WHERE FIND_IN_SET($secondryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid");
			
			$getdocumentimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{
						
						if(empty($imagedata->Doc_Url))
						{

							$docurl = "javascript:void(0);";
							$targetBlank = '';
							$dummyimageurl = base_url('backend/assets/pages/img/dummy.png');
						}
						else
						{
							$docimageUrl = '';
							$docurl = base_url('document/preview/'.$imagedata->Id);
							$targetBlank = 'target="_blank"';
							$extension = pathinfo(parse_url($imagedata->Doc_Url, PHP_URL_PATH), PATHINFO_EXTENSION);
							$se = strtolower($extension);

							if($se == 'jpeg' || $se == 'png' || $se == 'gif' || $se == 'tiff' || $se == 'bmp' || $se == 'jpg')
							{

								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');
							}
							elseif($se == 'doc' || $se == 'docx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/document.png');

							}
							elseif($se == 'xls' || $se == 'xlsx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/excel.png');

							}
							elseif($se == 'ppt' || $se == 'pptx')
							{

								$docimageUrl = base_url('backend/assets/pages/img/powerpoint.png');
							}
							elseif($se == 'pdf')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pdf.png');
							}
							elseif($se == 'zip')
							{

								$docimageUrl = base_url('backend/assets/pages/img/zip.png');
							}
							elseif($se == 'pages')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pages.png');
							}
							else
							{
								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');

							} 
						}

						if(empty($imagedata->Doc_Url))
	                    {
	                        $getdocumentimageData  []= '<a href="'.$docurl.'" '.$targetBlank.'><img src="'.$dummyimageurl.'" alt="Dummy" width="32" height="32"></a>';
	                    }
	                    else
	                    {
	                        $getdocumentimageData []= '<a href="'.$docurl.'" '.$targetBlank.'><img src="'.$docimageUrl.'" alt="Document" width="32" height="32"></a>';

	                    }

				}
				return $getdocumentimageData;

			}
			else
			{

				return false;
			}

		}


	/**   start coding for search by primary tag **/


	 /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
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
			
			$this->db->from('tbl_doc_secondary_tag');

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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
			$this->db->from('tbl_doc_secondary_tag');
			$this->db->where('Customer_Id = ', $customerid);
			$this->db->where_in('Id', $uniqeValue);						
			return $this->db->count_all_results();
		}	

	/** End search by primary tag **/


	/**   start coding for search by socondary tag **/

	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_date_by_document_image>
    * @Description:     <this function use for get all image according to date by>
    * @Parameters:      <$customerid, $primaryid, $tagdate, $secondarytagid = ''>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */

	public function get_date_by_document_image($customerid, $primaryid, $tagdate, $secondarytagid = '')
		{
			if($secondarytagid != '')
			{
			 $image = $this->db->query("SELECT * FROM tbl_doc_tag WHERE FIND_IN_SET($secondarytagid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");
			}
			else
			{
				$image = $this->db->query("SELECT * FROM tbl_doc_tag WHERE Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");	
			}
			$getdocumentimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{
						$docimageUrl = '';
						if(empty($imagedata->Doc_Url))
						{
							$docimageUrl = base_url('backend/assets/pages/img/dummy.png');

						}
						else
						{
						    $extension = pathinfo(parse_url($imagedata->Doc_Url, PHP_URL_PATH), PATHINFO_EXTENSION);
							$se = strtolower($extension);

							if($se == 'jpeg' || $se == 'png' || $se == 'gif' || $se == 'tiff' || $se == 'bmp' || $se == 'jpg')
							{

								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');
							}
							elseif($se == 'doc' || $se == 'docx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/document.png');

							}
							elseif($se == 'xls' || $se == 'xlsx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/excel.png');

							}
							elseif($se == 'ppt' || $se == 'pptx')
							{

								$docimageUrl = base_url('backend/assets/pages/img/powerpoint.png');
							}
							elseif($se == 'pdf')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pdf.png');
							}
							elseif($se == 'zip')
							{

								$docimageUrl = base_url('backend/assets/pages/img/zip.png');
							}
							elseif($se == 'pages')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pages.png');
							}
							else
							{
								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');

							} 
						}

						
	                   $getdocumentimageData []= '<a href="'.base_url('imagedocument/image_data_entry/'.$imagedata->Id).'" target="_blank"><img src="'.$docimageUrl.'" alt="Document" width="32" height="32"></a>';

	                           

				}
				return $getdocumentimageData;

			}
			else
			{

				return false;
			}

		}	

	/** End  primary tag view detail **/

	 /**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
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
    * @Last modified:   <28-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <get_date_by_document_image2>
    * @Description:     <this function use for get all image according to date by>
    * @Parameters:      <$customerid, $secondoryid, $tagdate, $primarytagid>
    * @Method:          <NO>
    * @Returns:         <YES>
    * @Return Type:     <array>
    */


	public function get_date_by_document_image2($customerid, $secondoryid, $tagdate, $primarytagid)
		{
			
			$image = $this->db->query("SELECT * FROM tbl_doc_tag WHERE FIND_IN_SET($secondoryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid AND Tag_Date_int = $tagdate");
			$getdocumentimageData = array();
			if($image->num_rows() > 0)
			{

				$imagedata = $image->result();

				foreach($imagedata as $imagedata)
				{

						$docimageUrl = '';
						if(empty($imagedata->Doc_Url))
						{
							$docimageUrl = base_url('backend/assets/pages/img/dummy.png');

						}
						else
						{
						    $extension = pathinfo(parse_url($imagedata->Doc_Url, PHP_URL_PATH), PATHINFO_EXTENSION);
							$se = strtolower($extension);

							if($se == 'jpeg' || $se == 'png' || $se == 'gif' || $se == 'tiff' || $se == 'bmp' || $se == 'jpg')
							{

								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');
							}
							elseif($se == 'doc' || $se == 'docx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/document.png');

							}
							elseif($se == 'xls' || $se == 'xlsx')
							{
								$docimageUrl = base_url('backend/assets/pages/img/excel.png');

							}
							elseif($se == 'ppt' || $se == 'pptx')
							{

								$docimageUrl = base_url('backend/assets/pages/img/powerpoint.png');
							}
							elseif($se == 'pdf')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pdf.png');
							}
							elseif($se == 'zip')
							{

								$docimageUrl = base_url('backend/assets/pages/img/zip.png');
							}
							elseif($se == 'pages')
							{

								$docimageUrl = base_url('backend/assets/pages/img/pages.png');
							}
							else
							{
								$docimageUrl = base_url('backend/assets/pages/img/dummy.png');

							} 
						}

						
	                         $getdocumentimageData []= '<a href="'.base_url('imagedocument/image_data_entry/'.$imagedata->Id).'" target="_blank"><img src="'.$docimageUrl.'" alt="document Img" width="32" height="32"></a>';

	                       

				}
				return $getdocumentimageData;

			}
			else
			{

				return false;
			}

		}

	/**
    * @Author:          Antaryami
    * @Last modified:   <28-12-2017>
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
			
			$amount = $this->db->query("SELECT * FROM tbl_doc_tag WHERE FIND_IN_SET($secondoryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid AND Tag_Date_int = $tagdate");
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
    * @Last modified:   <28-12-2017>
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
			    $amount = $this->db->query("SELECT * FROM tbl_doc_tag WHERE FIND_IN_SET($secondarytagid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");
			}
			else
			{

				$amount = $this->db->query("SELECT * FROM tbl_doc_tag WHERE Customer_Id = $customerid AND Primary_Tag = $primaryid AND Tag_Date_int = $tagdate");
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

    public function get_tag_nxt_id($uri_segment)
	    {
	    	$query = $this->db->query("Select * from `tbl_doc_tag` where id = (select min(id) from `tbl_doc_tag` where id > $uri_segment)");
	        return $query->result();	
	    }

    public function get_tag_previous_id($uri_segment)
	    {
	    	$query = $this->db->query("Select * from `tbl_doc_tag` where id = (select max(id) from `tbl_doc_tag` where id < $uri_segment)");
	        return $query->result();	
	    }
}
 
?>