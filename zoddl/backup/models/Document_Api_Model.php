<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		CodeIgniter
 * @category	Model
 */
class Document_Api_Model extends CI_Model {
  
   public function get_customer_id($tablecustomer,$Authtoken)
	{

 		 $query = $this->db->get_where($tablecustomer, array('Auth_Token' => $Authtoken));

 		 if($query->num_rows() > 0)
 		 {
 		 	return $query->row()->Id;
 		 	
 		 }
 		 else
 		 {
 		 	return false;
 		 }
 		 

	}

    public function get_customer_prime_tag($tableprimetag, $customerid)
	{
	  	return $this->db->get_where($tableprimetag, array('Customer_Id' => $customerid))->result();	
	}

     public function get_customer_second_tag($tablesecondtag, $customerid)
	{
	  	return $this->db->get_where($tablesecondtag, array('Customer_Id' => $customerid))->result();	
	}
     
     public function check_primary_tag($tableprimary,$customerid,$Primary_Tag)
	{
	  	
          if($Primary_Tag == '')
			{
				$is_primary = $this->db->get_where($tableprimary, array('Customer_Id' => $customerid,'Prime_Name' => 'Untagged'));
			}
			else
			{

				$is_primary = $this->db->get_where($tableprimary, array('Customer_Id' => $customerid,'Prime_Name' => $Primary_Tag));
			}

		if($is_primary->num_rows() > 0)
		{

		     $primeryid = $is_primary->row();		     	
		     return $primeryid->Id;		
		}
		else
		{
			
			if($Primary_Tag == '')
			{

				$is_primary = $this->db->get_where($tableprimary, array('Customer_Id' => $customerid,'Prime_Name' => "Untagged"));
				if($is_primary->num_rows() > 0)
				{
					$primeryid = $is_primary->row();		     
		            return $primeryid->Id;	

				}
				else
				{
					$data = array(
       				       'Customer_Id'=>$customerid,
				       'Prime_Name'=>"Untagged"
    				   );

		    			$this->db->insert($tableprimary,$data);
					$primary_id = $this->db->insert_id();

		   			return  $primary_id;

				}
			}		
			else
			{
				
				$data = array(
	       				       'Customer_Id'=>$customerid,
					       'Prime_Name'=>$Primary_Tag
	    				   );

	    			$this->db->insert($tableprimary,$data);
				$primary_id = $this->db->insert_id();

	   			return  $primary_id;
	   		}
   			
		}
	}
     
     public function check_secondry_tag($tablesecondry,$customerid,$Secondry_Tag)
	{

         $arraySecondaryValue = json_decode($Secondry_Tag);		
	  	
		if(empty($arraySecondaryValue))
		{
			
			$is_secondry = $this->db->get_where($tablesecondry, array('Customer_Id' => $customerid,'Secondary_Name' => 'Untagged'));
			if($is_secondry->num_rows() > 0)
				{
				     $secondryid = $is_secondry->row();	
				     $secondry_id = $secondryid->Id;	
				     return $secondry_id;	
				}
				else
				{
						   $data = array(
	       				       'Customer_Id'=>$customerid,
					       	   'Secondary_Name'=>"Untagged"
	    				   );

		    			$this->db->insert($tablesecondry,$data);
						$secondry_id = $this->db->insert_id();
		   				return  $secondry_id;

				}
				
		}
		else
		{
			$storeSecondaryArray = array();
			foreach( $arraySecondaryValue as $sct)
			{
			  	$is_secondry = $this->db->get_where($tablesecondry, array('Customer_Id' => $customerid,'Secondary_Name' => $sct));


			  	if($is_secondry->num_rows() > 0)
				{
				     $secondryid = $is_secondry->row();	
				     array_push($storeSecondaryArray, $secondryid->Id);		
				}
				else
				{
						   $data = array(
	       				       'Customer_Id'=>$customerid,
					       	   'Secondary_Name'=>$sct
	    				   );

		    			$this->db->insert($tablesecondry,$data);
						$secondry_id = $this->db->insert_id();
		   			array_push($storeSecondaryArray, $secondry_id);	

				}

			}

			$implodeComma = implode(',',$storeSecondaryArray);

			$returnValue = trim($implodeComma,','); 

			return $returnValue;
			
		}
		
		
		
	}


public function insert_tag($tabletag,$data)
	{
	   	$this->db->insert($tabletag,$data);
		return true;	
	}


public function get_primarytag($tableprimary, $Primary_Tag)
	{
	  	
		$is_primary = $this->db->get_where($tableprimary, array('Id' => $Primary_Tag));

		if($is_primary->num_rows() > 0)
		{
		     $primeryid = $is_primary->row();	
		     return $primeryid->Prime_Name;		
		}
		else
		{
			
   			return  false;
		}
	}

     
public function get_secondrytag($tablesecondry, $Secondry_Tag)
	{
	  	
		$is_secondry = $this->db->get_where($tablesecondry, array('Id' => $Secondry_Tag));
		
		if($is_secondry->num_rows() > 0)
		{
		     $secondryid = $is_secondry->row();	
		     return $secondryid->Secondary_Name;		
		}
		else
		{
			
   			return false;
		}
	}


public function get_customer_tag($tabletag, $customerid)
	{
	  	$tag_list = $this->db->get_where($tabletag, array('Customer_Id' => $customerid));

	  	if($tag_list->num_rows() > 0)
		{
		     $tags = $tag_list->result();
		     return $tags;
		}
		else
		{
			
   			return false;
		}
	}

	
public function exists_email( $email )
    {		
		$email_count = $this->db->get_where('tbl_customer',array('Email_Id' => $email))->num_rows();
		return $email_count;
        
    } 



public function create_lostpw_code()
    {
        $pass = time();        
        return $pass;
    }


public function update_user_password($email, $pass)
    {
        $lostpw_code = md5($pass);
        $this->db->update('tbl_customer',array('Password' => $lostpw_code),array('Email_Id' => $email)); 
        return true;
    } 

public function gallery_primary_tag($customerid)
 {
    		/*$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total');
       		$this->db->from('tbl_doc_tag t');
       		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');       		
       		$query = $this->db->get(); */
			
			$query = $this->db->query("SELECT `pt`.`Prime_Name`, `t`.`Tag_Date_int`, `t`.`Primary_Tag`, SUM(t.Amount) as Total, 'document' as Source_Type FROM `tbl_doc_tag` `t` LEFT JOIN `tbl_doc_primary_tag` `pt` ON `t`.`Primary_Tag`	= `pt`.`Id` WHERE `t`.`Customer_Id` = $customerid GROUP BY `t`.`Primary_Tag` ORDER BY FIELD(pt.Prime_Name, 'Untagged') DESC, pt.Prime_Name");
			
       		return $query->result();

 }

 public function get_lasttimestamp_data($pid)
 {
    return $this->db->order_by('Id', 'DESC')->get_where('tbl_doc_tag',array('Primary_Tag' => $pid))->row()->Tag_Date_int;

 }

 public function get_imagesecondarytag($imageId)
 {
 		$getSecondaryTag = $this->db->get_where('tbl_doc_tag',array('Id' => $imageId))->row()->Secondry_Tag;
 		
 		$secData = $this->db->query("select Id, Secondary_Name  from tbl_doc_secondary_tag where Id IN ($getSecondaryTag)")->result();

 		return $secData;



 }

public function all_image_data($primaryid)
 {
 		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Primary_Tag,t.Doc_Url,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description');
   		$this->db->from('tbl_doc_tag t');
   		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');   		
   		$this->db->where('t.Primary_Tag = ', $primaryid);
      $this->db->order_by('t.Id', 'DESC');   		
   		$query = $this->db->get();
   		$data = $query->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{
            
   			$data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }           
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
             if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }

            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;

 }

 public function all_image_data_count($primaryid)
 {

  $this->db->select('Id');
  $this->db->from('tbl_doc_tag');
  $this->db->where('Primary_Tag =', $primaryid);
  $num_results = $this->db->count_all_results();
  return $num_results;

 }

public function get_gallery_data_by_primarytag($customerid, $tagtype = '')
 {

 		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Primary_Tag, t.Doc_Url, t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description');
   		$this->db->from('tbl_doc_tag t');
   		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');   		
   		$this->db->where('t.Customer_Id = ', $customerid);
   		if($tagtype != '')
   		{
   			$this->db->where('t.Tag_Type = ', $tagtype);
   		}
   		$this->db->group_by('t.Primary_Tag');   		
   		$query = $this->db->get();   		 		
   		$data = $query->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{

   			$data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }
            
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
             if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
           array_push($returndata,$data);

   		}
   		return  $returndata;

 }

 public function get_gallery_data_by_primarytag_total($customerid, $tagtype = '')
 {

 		$this->db->select('*');
   		$this->db->from('tbl_doc_tag');   		
   		$this->db->where('Customer_Id = ', $customerid);
   		$arraysum = array();
   		if($tagtype != '')
   		{
   			$this->db->where('Tag_Type = ', $tagtype);
   		}
   		$this->db->group_by('Primary_Tag');   		
   		$query = $this->db->get();

   		$data = $query->result();   		

   		foreach($data as $data)
   		{
           $arraysum[] = $data->Amount;

   		}        
         return $data = array_sum($arraysum); 

 }

public function get_useritem_tag_details($customerid, $tagid)
 {
 		
   		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.Primary_Tag, t.Doc_Url, t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description, t.CGST, t.SGST, t.IGST');
   		$this->db->from('tbl_doc_tag t');
   		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
   		$this->db->where('t.Customer_Id = ', $customerid);
   		$this->db->where('t.Id = ', $tagid);
   		$query = $this->db->get();       		
   		$data = $query->row();   		
   		$returndata = array();



   		   $data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }
            
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
             if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
           array_push($returndata,$data);
           return  $returndata;

 }
  

 public function get_useritem_edit_tag_details($tabletag, $tagId, $data)
   {

   		 $this->db->where('Id', $tagId);
    	 return $this->db->update($tabletag, $data);

   }


public function get_user_primarytag_list($customerid, $uniqeValue)
 {
 		$this->db->select('Id as Secondary_Tag, Secondary_Name');
   		$this->db->from('tbl_doc_secondary_tag');
   		$this->db->where('Customer_Id = ', $customerid);
   		$this->db->where_in('Id', $uniqeValue);
   		$query = $this->db->get();   		
   		$data = $query->result();
   		return $data;

 }

public function all_secondarytag_image_data($secondaryid, $customerid, $primarytagid)
 {
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Primary_Tag, t.Doc_Url, t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE FIND_IN_SET($secondaryid,t.Secondry_Tag) AND t.Customer_Id = $customerid AND t.Primary_Tag = $primarytagid ORDER BY t.Id DESC");
 		$data = $image->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{
            
   			    $data->Type = "document";
            $data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }           
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
            if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;
 		
 }

 public function get_all_secondarytag_total($secondaryid, $customerid, $primarytagid)
 {
 		
 		$arraysum = array();
        $totalamount = $this->db->query("SELECT * FROM tbl_doc_tag WHERE FIND_IN_SET($secondaryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid");
       
        $data = $totalamount->result();

        foreach($data as $data)
   		{
           $arraysum[] = $data->Amount;

   		}        
        return $data = array_sum($arraysum);
 }

 public function get_user_secondarytag_list($customerid,$secondarytagid)
 {
 		$where = "t.Customer_Id = $customerid AND FIND_IN_SET($secondarytagid,t.Secondry_Tag)";
 		$this->db->select('pt.Prime_Name, t.Primary_Tag, SUM(t.Amount) as Total');
   		$this->db->from('tbl_doc_tag t');
   		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
 		$this->db->where($where);
 		$this->db->group_by('Primary_Tag');		
		$query = $this->db->get();		
		return $query->result();

 }

 public function all_primarytag_image_data($primaryid, $customerid, $secondarytagid)
 {
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.Customer_Id, t.Doc_Url, t.Primary_Tag,t.Amount,t.Image_Url, t.isUploaded, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primaryid AND FIND_IN_SET($secondarytagid,t.Secondry_Tag) AND t.Customer_Id = $customerid ORDER BY t.Id DESC");
 		$data = $image->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{    
   			    
            $data->Type = "document";
            $data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }           
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
            if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;
 		
 }

 public function get_user_primarysecondarytag_detail($customerid,$primary_tag,$secondarytag)
 {
 	$where = "Customer_Id = $customerid AND Primary_Tag = $primary_tag AND FIND_IN_SET($secondarytag,Secondry_Tag)";
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	$this->db->from('tbl_doc_tag');
 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Id','desc');
    $query = $this->db->get();
    return $query->result();
 	
 }

 public function get_user_primarysecondarytag_detail_image($customerid,$primarytag,$secondarytag,$date,$page,$limit)
 {
         
         $Period = explode('-',$date);

        $month = $Period[1];

        if($page === '' || $limit === '')
        {

 		       $image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Customer_Id, t.Doc_Url, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primarytag AND FIND_IN_SET($secondarytag,t.Secondry_Tag) AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month");
 		    
        }
        else
        {
            $image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Customer_Id, t.Doc_Url, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Primary_Tag = $primarytag AND FIND_IN_SET($secondarytag,t.Secondry_Tag) AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month LIMIT $page, $limit");

        }

       $data = $image->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{    
   			$data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }           
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
            if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;
 }

 public function get_user_gelleryprimarytag_detail($customerid,$primary_tag)
 {
 	$where = "Customer_Id = $customerid AND Primary_Tag = $primary_tag";
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	$this->db->from('tbl_doc_tag');
 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Id','desc');
    $query = $this->db->get();
    return $query->result(); 	
 }

 public function get_user_gelleryprimarytag_detail_by_month($customerid,$primary_tag,$month)
 {
  
  $where = "Customer_Id = $customerid AND Primary_Tag = $primary_tag AND Month(Tag_Send_Date) = $month";
  $this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
  $this->db->from('tbl_doc_tag');
  $this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Id','desc');
    $query = $this->db->get();
    return $query->row();   
 }

public function get_user_gelleryprimarytag_detail_image($customerid,$primarytag,$date,$page,$limit)
 {
         
         $Period = explode('-',$date);

        $month = $Period[1];

         if($page === '' || $limit === '')
        {

 		     $image = $this->db->query("SELECT pt.Prime_Name, t.Id, t.isUploaded, t.Customer_Id, t.Doc_Url, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primarytag AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month ORDER BY t.Id DESC");
 		    }
        else
        {
          $image = $this->db->query("SELECT pt.Prime_Name, t.Id, t.isUploaded, t.Customer_Id, t.Doc_Url, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag  = pt.Id WHERE t.Primary_Tag = $primarytag AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month ORDER BY t.Id DESC LIMIT $page, $limit");

        }

       $data = $image->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{    
   			    $data->Type = "document"; 
            $data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }           
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
            if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;
 }

 public function get_user_homedatatagtype_detail($customerid,$tagtype)
 {
 	$where = "Customer_Id = $customerid AND Tag_Type = '$tagtype'"; 
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	$this->db->from('tbl_doc_tag');
 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Id','desc');
    $query = $this->db->get();
    
    return $query->result(); 	
 }

 public function get_user_homedatatagtype_detail_image($customerid, $tagtype, $date)
 {
         
         $Period = explode('-',$date);

        $month = $Period[1];
       
 			$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Doc_Url, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Tag_Type = '$tagtype' AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) = '$month'");
	
 		
 		$data = $image->result(); 			
   		$returndata = array();
   		foreach($data as $data)
   		{    
   			$data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }           
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
            if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;
 }

  /** Filter Code Start **/

 public function gallery_primary_tag_alpha($customerid)
 {
    		$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total');
       		$this->db->from('tbl_doc_tag t');
       		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');
       		$this->db->order_by('pt.Prime_Name', 'asc');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;      		
       		return $query->result();

 }

 public function gallery_secondary_tag_alpha($customerid)
 {
 			$this->db->select('Id as Secondary_Tag, Secondary_Name');
       		$this->db->from('tbl_doc_secondary_tag');       		
       		$this->db->where('Customer_Id = ', $customerid);       		
       		$this->db->order_by('Secondary_Name', 'asc');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;
       		return $query->result();

 }

 public function secondary_tag_total($secondaryid)
 {
 		
 		$arraysum = array();
        $totalamount = $this->db->query("SELECT * FROM tbl_doc_tag WHERE FIND_IN_SET($secondaryid,Secondry_Tag)");
       
        $data = $totalamount->result();

        foreach($data as $data)
   		{
           $arraysum[] = $data->Amount;

   		}        
        return $data = array_sum($arraysum);
 }

 public function all_secondary_image_data($secondaryid)
 {
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.Customer_Id, t.Primary_Tag, t.Doc_Url, t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE FIND_IN_SET($secondaryid,t.Secondry_Tag)");
 		$data = $image->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{
            
   			$data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }           
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
            }
            if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
            if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            if($data->Doc_Url == null)
            {
              $data->Doc_Url = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;
 		
 }

public function gallery_primary_tag_amount($customerid)
 {
    		$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total');
       		$this->db->from('tbl_doc_tag t');
       		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');
       		$this->db->order_by('SUM(t.Amount)', 'DESC');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;      		
       		return $query->result();

 }

 public function gallery_secondary_tag_amount($customerid)
 {
 			$this->db->select('Id');
       		$this->db->from('tbl_doc_secondary_tag');       		
       		$this->db->where('Customer_Id = ', $customerid);
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;
       		return $query->result();

 }

 public function gallery_primary_tag_count($customerid)
 {
    		$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total, COUNT(t.Id) as Count');
       		$this->db->from('tbl_doc_tag t');
       		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');
       		$this->db->order_by('COUNT(t.Id)', 'DESC');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;      		
       		return $query->result();

 }

 public function gallery_primary_tag_uses($customerid)
 {
    		$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total, COUNT(t.Id) as Uses');
       		$this->db->from('tbl_doc_tag t');
       		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');
       		$this->db->order_by('COUNT(t.Id)', 'DESC');       		
       		$query = $this->db->get();       			
       		return $query->result();

 }

 /** Filter Code End **/

 public function delete_tag($customerid, $tagid)
 {
  
    $this->db->where('Id', $tagid);
    $this->db->where('Customer_Id', $customerid);
    $is_delete = $this->db->delete('tbl_doc_tag');

    if($is_delete)
    {

      return true;
    }
    else
    {
      return false;

    }

 }

public function get_total_data_by_year($customerid, $tableTag)
 {
        $where = "Customer_Id = $customerid"; 
        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from("$tableTag");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row();  

 }

 public function get_total_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary)
 {
    if(($gellery_type == 'image gallery' || $gellery_type == 'document gallery') && ($type == 'primary tag'))
    {
          $this->db->select('SUM(t.Amount) as Total_Amount, Count(t.Id) as No_Of_Entry');
          $this->db->from("$tableTag t");
          $this->db->join("$tablePrimary pt",'t.Primary_Tag = pt.Id','left');
          $this->db->where('t.Customer_Id = ', $customerid);
          $this->db->where('t.Primary_Tag = ', $value);
          $query = $this->db->get();
          return $query->row();

    }
    else if(($gellery_type == 'image gallery' || $gellery_type == 'document gallery') && ($type == 'secondary tag'))
    {
          $query = $this->db->query("SELECT SUM(Amount) as Total_Amount, Count(Id) as No_Of_Entry FROM $tableTag WHERE Customer_Id = $customerid and FIND_IN_SET($value,Secondry_Tag)");

          //echo $this->db->last_query(); die;
          return $query->row();

    }
    else if(($gellery_type == 'image gallery' || $gellery_type == 'document gallery')  && ($type == 'master'))
    {
          if($value == 'year')
          {
              $where = "Customer_Id = $customerid"; 
              $this->db->select('YEAR(Tag_Send_Date) as Year, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','desc');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;
              $returndata = array();
                       
                foreach($query->result() as $data)
                {
                    $temp = new stdClass();
                    $temp->col1 = $data->Year == null ? '' : $data->Year;
                    $temp->col2 = '';
                    $temp->col3 = '';
                    $temp->col4 = '';
                    $temp->col5 = $data->Amount == null ? '0' : $data->Amount;
                    array_push($returndata,$temp);
                }

              return  $returndata;  

          }
          else if($value == 'month')
          {
            
              $where = "Customer_Id = $customerid"; 
              $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;             
              $returndata = array();
                       
                foreach($query->result() as $data)
                {
                    $temp = new stdClass();
                    $temp->col1 = $data->Month == null ? '' : $data->Month;
                    $temp->col2 = '';
                    $temp->col3 = '';
                    $temp->col4 = '';
                    $temp->col5 = $data->Amount == null ? '0' : $data->Amount;
                    array_push($returndata,$temp);
                }
                
              return  $returndata;  
          }
          else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
          {

            $this->db->select('SUM(Amount) as Total_Amount, Count(Id) as No_Of_Entry');
            $this->db->from("$tableTag");           
            $this->db->where('Customer_Id = ', $customerid);
            $this->db->where('Tag_Type = ', $value);
            $query = $this->db->get();
            //echo $this->db->last_query(); die; 
            return $query->row();
          }

    }

    


 }

 public function get_column_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary)
 {

      if(($gellery_type == 'image gallery' || $gellery_type == 'document gallery') && ($type == 'primary tag'))
      {
            $this->db->select('pt.Prime_Name, SUM(t.Amount) as Total');
            $this->db->from("$tableTag t");
            $this->db->join("$tablePrimary pt",'t.Primary_Tag = pt.Id','left');
            $this->db->where('t.Customer_Id = ', $customerid);
            $this->db->where('t.Primary_Tag = ', $value);
            $query = $this->db->get();
            $returndata = array();
            
            foreach($query->result() as $data)
            { 
                $temp = new stdClass();         
                $temp->col1 = $data->Prime_Name == null ? '' : $data->Prime_Name;
                $temp->col2 = '';
                $temp->col3 = '';
                $temp->col4 = '';
                $temp->col5 = $data->Total == null ? '0' : $data->Total;
                array_push($returndata,$temp);               

            }
          return  $returndata;

      }

     
 }

 public function get_secondary_info($tableSecondary,$value,$total)
 {

    $query = $this->db->get_where($tableSecondary, array("Id" => $value));
      $returndata = array();
      //echo $this->db->last_query(); die;
            
            foreach($query->result() as $data)
            {
                $temp = new stdClass();           
                $temp->col1 = $data->Secondary_Name == null ? '' : $data->Secondary_Name;
                $temp->col2 = '';
                $temp->col3 = '';
                $temp->col4 = '';
                $temp->col5 = $total;
                array_push($returndata,$temp);               

            }
        return  $returndata;


 }

public function get_total_data_columen2($customerid, $tableTag, $tablePrimary, $tableSecondary, $primaryTag, $secondaryTag)
 {
    
      $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag AND FIND_IN_SET($secondaryTag,Secondry_Tag)";
      $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
      $this->db->from($tableTag);
      $this->db->where($where);
      //$this->db->join()
      
      $query = $this->db->get();
      //echo $this->db->last_query(); die;
      return $query->row();
 }

 
public function get_primeryName($tablePrimary,$primaryTag)
{
        $query = $this->db->get_where($tablePrimary,array("Id" =>$primaryTag));

        if($query->num_rows() > 0)
         {
            return $query->row()->Prime_Name;

         }
         else
         {

           return "NA";
         }


}

public function get_secondaryName($tableSecondary,$secondaryTag)
{
     
       $query = $this->db->get_where($tableSecondary,array("Id" =>$secondaryTag));

       if($query->num_rows() > 0)
       {
          return $query->row()->Secondary_Name;

       }
       else
       {

         return "NA";
       }
}

public function get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag)
 {
        if($secondaryTag == '')
        {
          $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag";

        }
        else
        {
          $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag)";
        }
        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from("$tableTag");
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row();  

 }
 public function get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary)
 {
              if($primaryTag == '')
              {
                
                $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag)";
                $Name = $this->get_secondaryName($tableSecondary,$secondaryTag);

              }
              else
              {

                $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag";
                $Name = $this->get_primeryName($tablePrimary,$primaryTag);

              } 
              $this->db->select('YEAR(Tag_Send_Date) as Year, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','desc');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;
              $returndata = array();
                       
                foreach($query->result() as $data)
                {
                    $temp = new stdClass();
                    $temp->col1 = $data->Year == null ? '' : $data->Year;
                    $temp->col2 = $Name == null ? '' : $Name;
                    $temp->col3 = '';
                    $temp->col4 = '';
                    $temp->col5 = $data->Amount == null ? '0' : $data->Amount;
                    array_push($returndata,$temp);
                }

              return  $returndata;  

 }

 public function get_total_data_column2_month($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary)
 {
             
              if($primaryTag == '')
              {
                
                $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag)";
                $Name = $this->get_secondaryName($tableSecondary,$secondaryTag);

              }
              else
              {

                $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag";
                $Name = $this->get_primeryName($tablePrimary,$primaryTag);

              } 
              $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;             
              $returndata = array();
                       
                foreach($query->result() as $data)
                {
                    $temp = new stdClass();
                    $temp->col1 = $data->Month == null ? '' : $data->Month;
                    $temp->col2 = $Name == null ? '' : $Name;
                    $temp->col3 = '';
                    $temp->col4 = '';
                    $temp->col5 = $data->Amount == null ? '0' : $data->Amount;
                    array_push($returndata,$temp);
                }
                
              return  $returndata;   

 }
 public function get_total_data_by_tagtype($customerid, $tableTag, $value, $primaryTag, $secondaryTag)
 {      

        if($primaryTag == '')
        {
          $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag) AND Tag_Type = '$value'";
        }
        else
        {
          $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag AND Tag_Type = '$value'";
        } 
        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from("$tableTag");
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row();  

 }


}
