<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		CodeIgniter
 * @category	Model
 */
class Customer_Api_Model extends CI_Model {
    
    
    /**
	 * @Description: Fetching user details for Logging user
	 * @Author: Antaryami
	 * @Created: 27 Nov 2017
	 */
    
    public function get_login_data($table, $Email_Id, $Password)
    {

        $getUserData = $this->db->query("SELECT * FROM $table  WHERE Email_Id ='" . $Email_Id . "' AND Password='" . md5($Password) . "'");
	if($getUserData->num_rows() > 0)
	{	 
		return $getUserData->row();
	}
	else
	{
          return false;
	}
    }
    
     
    /**
     * Insert Device data
     *
     * @param string $table
     * @param array  $where
     *  @Author: Antaryami
     * @return array
     */
	public function update_device_entry($device_type,$customer_id,$device_token)	
    {
     	$data=array("Customer_Id" => $customer_id,"Device_Token" => $device_token,"Device_Type" => $device_type); 
     	$this->db->select('*');
		$this->db->where('Device_Type',$device_type);
		$this->db->where('Device_Token',$device_token);
		$result = $this->db->get('tbl_customer_device');
		
		if($result->num_rows() > 0)
		{
		   
           $this->db->where('Device_Type',$device_type);
		   $this->db->where('Device_Token',$device_token);
		   if($this->db->update('tbl_customer_device',$data))
		     return true;
		      else
		     return false;
	         

		}
		else
		{
           if($this->db->insert('tbl_customer_device',$data))
		   return $this->db->insert_id();
		   else
		   return false;

		}
		
	}
	
	
	 /**
     * Delete Device data
     *  @Author: Antaryami
     */
	public function delete_data($table,$condition){
		$this->db->where($condition);
		$this->db->delete($table);
		$result = $this->db->affected_rows();
		if($result){
			return true;
		}else{
			return false;
		}
	}
	

	 /**
     * check Social login Details
     *  @Author: Antaryami
     */
	
	public function check_social_login($tableName,$Email_Id,$Login_Type,$Social_Id)
		{
		 if($this->is_customer_exit($tableName,$Email_Id) == 0)
			{
		if($Login_Type == 'f')
		{
 	   $Checksociallogin = $this->db->query("SELECT * FROM $tableName  WHERE Email_Id ='" . $Email_Id . "' AND Login_Type='" . $Login_Type . "' AND Social_F_Id ='" . $Social_Id . "'");	
}
		else
		{
   $Checksociallogin = $this->db->query("SELECT * FROM $tableName  WHERE Email_Id ='" . $Email_Id . "' AND Login_Type='" . $Login_Type . "' AND Social_G_Id ='" . $Social_Id . "'");
		}
	if($Checksociallogin->num_rows() > 0)
		{
			return $Checksociallogin->row();
		}
		else
		{	
			if($Login_Type == 'f')
			{			
				$data = array(
					'Email_Id'=>$this->input->post('Email_Id'),
					'Login_Type'=>$this->input->post('Login_Type'),
					'User_Type'=>2,
					'Status' => 1,
					'Social_F_Id'=>$this->input->post('Social_Id')
					  );
			}
			else
			{
			   $data = array(
					'Email_Id'=>$this->input->post('Email_Id'),
					'Login_Type'=>$this->input->post('Login_Type'),
					'User_Type'=>2,
					'Status' => 1,
					'Social_G_Id'=>$this->input->post('Social_Id')
					  );
			}
			$this->db->insert($tableName,$data);
			$last_insert_id = $this->db->insert_id();
			if($last_insert_id)
			{
			  $first_social_login = $this->db->query("SELECT * FROM $tableName  WHERE Id ='" . $last_insert_id . "'");
			  return $first_social_login->row();	
			}

		}
}else
{
                if($Login_Type == 'f')
		{
			$data = array(
    				'Social_F_Id' => $Social_Id
			   );
		}
		else
		{
			$data = array(
    				'Social_G_Id' => $Social_Id
			   );
		}
		$this->db->where('Email_Id', $Email_Id);
                $this->db->update($tableName, $data);

       		$getCustomerid = $this->db->get_where($tableName, array('Email_Id' => $Email_Id));		
		return $getCustomerid->row();
}
	
	}

	public function is_customer_exit($tableName,$Email_Id)
	{
		$check = $this->db->get_where($tableName, array('Email_Id' => $Email_Id))->num_rows();
		return $check;

	}
	
	public function add_customer($tableName,$Email_Id,$Password,$Login_Type)
	{
           $checkuserdata = $this->is_customer_exit($tableName,$Email_Id);
	   if($checkuserdata>0)	
	     {
	     	$getCustomer = $this->db->get_where($tableName, array('Email_Id' => $Email_Id))->row();

	     	if($getCustomer->Login_Type == 'm')
	     	{
				return "You have already registered this email addresses.";
			}
			else
			{
				$data = array(
						        'Password' => md5($this->input->post('id')),
						        'User_Type'=>2,
						        'Status' => 1,
						        'Login_Type' => 'm'
						     );
				$this->db->where('Email_Id', $Email_Id);
    			$this->db->update($tableName, $data);
    			return "You have successfully registered.";

			}
		}
	   else
	       {
	       	$verificationCode = time();
			$data = array(
					'Email_Id'=>$this->input->post('Email_Id'),
					'Password'=>md5($this->input->post('Password')),
					'User_Type'=>2,
					'Status' => 1,
					'Varification_Key'=>$verificationCode,
					'Login_Type'=>$this->input->post('Login_Type')
					  );
			$this->db->insert($tableName,$data);			

			return "You have successfully registered.";
		}	
	}

     public function generateToken($tableName, $customerid)
	{
		$static_str='AT';
		$currenttimeseconds = date("mdY_His");
		$authtoken = $static_str.$customerid.$currenttimeseconds;		
		$authdata = array('Auth_Token' => md5($authtoken));    
		$this->db->where('id', $customerid);
		$this->db->update($tableName, $authdata); 
		return md5($authtoken);
			
	}

     public function get_customer_id($tablecustomer,$Authtoken)
	{

 		 $query = $this->db->get_where($tablecustomer, array('Auth_Token' => "$Authtoken")); 		 

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
		     //echo $this->db->last_query(); die;	
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
public function update_user_token($email, $token)
    {
        //$lostpw_code = md5($pass);
        $this->db->update('tbl_customer',array('Password' => md5($token)),array('Email_Id' => $email)); 
        return true;
    } 

public function gallery_primary_tag($customerid)
 {
    		/*$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;      		
       		return $query->result(); */

       		$query = $this->db->query("SELECT `pt`.`Prime_Name`, `t`.`Tag_Date_int`, `t`.`Primary_Tag`, SUM(t.Amount) as Total FROM `tbl_tag` `t` LEFT JOIN `tbl_primary_tag` `pt` ON `t`.`Primary_Tag`	= `pt`.`Id` WHERE `t`.`Customer_Id` = $customerid GROUP BY `t`.`Primary_Tag` ORDER BY FIELD(pt.Prime_Name, 'Untagged') DESC, pt.Prime_Name");
       		return $query->result();

 }

 public function get_lasttimestamp_data($pid)
 {
 		return $this->db->order_by('Id', 'DESC')->get_where('tbl_tag',array('Primary_Tag' => $pid))->row()->Tag_Date_int;

 }

 public function get_imagesecondarytag($imageId)
 {
 		$getSecondaryTag = $this->db->get_where('tbl_tag',array('Id' => $imageId))->row()->Secondry_Tag;
 		
 		$secData = $this->db->query("select Id, Secondary_Name  from tbl_secondary_tag where Id IN ($getSecondaryTag)")->result();

 		return $secData;



 }

 public function get_imagesecondarytagdocument($imageId)
 {
 		$getSecondaryTag = $this->db->get_where('tbl_doc_tag',array('Id' => $imageId))->row()->Secondry_Tag;
 		
 		$secData = $this->db->query("select Id, Secondary_Name  from tbl_doc_secondary_tag where Id IN ($getSecondaryTag)")->result();

 		return $secData;
 }

public function all_image_data($primaryid)
 {
 		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description');
   		$this->db->from('tbl_tag t');
   		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');   		
   		$this->db->where('t.Primary_Tag = ', $primaryid);   		
   		$query = $this->db->get();
   		//echo $this->db->last_query(); die;   		     		
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
            array_push($returndata,$data);

   		}
   		return  $returndata;

 }

 public function all_image_data_count($primaryid)
 {

 	$this->db->select('Id');
	$this->db->from('tbl_tag');
	$this->db->where('Primary_Tag =', $primaryid);
	$num_results = $this->db->count_all_results();
	return $num_results;

 }

public function get_gallery_data_by_primarytag($customerid, $tagtype = '') 
 {

 		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description');
   		$this->db->from('tbl_tag t');
   		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
   		//$this->db->join('tbl_secondary_tag st','t.Secondry_Tag = st.Id','left');
   		$this->db->where('t.Customer_Id = ', $customerid);
   		if($tagtype != '')
   		{
   			$this->db->where('t.Tag_Type = ', $tagtype);
   		}
   		$this->db->group_by('t.Primary_Tag');   		
   		$query = $this->db->get(); 
   		//echo $this->db->last_query(); die;      		
   		$data = $query->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{
   			$data->Type = "gallery";
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
           array_push($returndata,$data);

   		}
   		return  $returndata;

 }

 public function get_gallery_data_by_primarytag_doc($customerid, $tagtype = '') 
 {

 		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Doc_Url, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description');
   		$this->db->from('tbl_doc_tag t');
   		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
   		//$this->db->join('tbl_secondary_tag st','t.Secondry_Tag = st.Id','left');
   		$this->db->where('t.Customer_Id = ', $customerid);
   		if($tagtype != '')
   		{
   			$this->db->where('t.Tag_Type = ', $tagtype);
   		}
   		$this->db->group_by('t.Primary_Tag');   		
   		$query = $this->db->get(); 
   		//echo $this->db->last_query(); die;      		
   		$data = $query->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{
   			$data->Type = "document";
   			$data->Secondary_Tag = $this->get_imagesecondarytagdocument($data->Id);
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
            if($data->Doc_Url == null)
            {
            	$data->Doc_Url = "";
            }
             if($data->Description == null)
            {
            	$data->Description = "";
            }
             if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
           array_push($returndata,$data);

   		}
   		return  $returndata;

 }

 public function get_gallery_data_by_primarytag_total($customerid, $tagtype = '')
 {

 		$this->db->select('*');
   		$this->db->from('tbl_tag');   		
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

 public function get_gallery_data_by_primarytag_total_doc($customerid, $tagtype = '')
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

 public function get_document_data_by_primarytag($customerid, $tagtype = '') 
 {

 		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description');
   		$this->db->from('tbl_doc_tag t');
   		$this->db->join('tbl_doc_primary_tag pt','t.Primary_Tag	= pt.Id','left');
   		//$this->db->join('tbl_secondary_tag st','t.Secondry_Tag = st.Id','left');
   		$this->db->where('t.Customer_Id = ', $customerid);
   		if($tagtype != '')
   		{
   			$this->db->where('t.Tag_Type = ', $tagtype);
   		}
   		$this->db->group_by('t.Primary_Tag');   		
   		$query = $this->db->get(); 
   		//echo $this->db->last_query(); die;      		
   		$data = $query->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{

   			$data->Secondary_Tag = $this->get_imagesecondarytagdocument($data->Id);
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
           array_push($returndata,$data);

   		}
   		return  $returndata;

 }

 public function get_document_data_by_primarytag_total($customerid, $tagtype = '')
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
 		
   		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description, t.CGST, t.SGST, t.IGST');
   		$this->db->from('tbl_tag t');
   		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
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
   		$this->db->from('tbl_secondary_tag');
   		$this->db->where('Customer_Id = ', $customerid);
   		$this->db->where_in('Id', $uniqeValue);
   		$query = $this->db->get();   		
   		$data = $query->result();
   		return $data;

 }

public function all_secondarytag_image_data($secondaryid, $customerid, $primarytagid)
 {
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.isUploaded, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE FIND_IN_SET($secondaryid,t.Secondry_Tag) AND t.Customer_Id = $customerid AND t.Primary_Tag = $primarytagid");
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
            array_push($returndata,$data);

   		}
   		return  $returndata;
 		
 }

 public function get_all_secondarytag_total($secondaryid, $customerid, $primarytagid)
 {
 		
 		$arraysum = array();
        $totalamount = $this->db->query("SELECT * FROM tbl_tag WHERE FIND_IN_SET($secondaryid,Secondry_Tag) AND Customer_Id = $customerid AND Primary_Tag = $primarytagid");
       
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
   		$this->db->from('tbl_tag t');
   		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
 		$this->db->where($where);
 		$this->db->group_by('Primary_Tag');		
		$query = $this->db->get();		
		return $query->result();

 }

 public function all_primarytag_image_data($primaryid, $customerid, $secondarytagid)
 {
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.Customer_Id, t.isUploaded, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primaryid AND FIND_IN_SET($secondarytagid,t.Secondry_Tag) AND t.Customer_Id = $customerid");
 		//echo $this->db->last_query(); die;
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
            array_push($returndata,$data);

   		}
   		return  $returndata;
 		
 }

 public function get_user_primarysecondarytag_detail($customerid,$primary_tag,$secondarytag)
 {
 	$where = "Customer_Id = $customerid AND Primary_Tag = $primary_tag AND FIND_IN_SET($secondarytag,Secondry_Tag)";
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	$this->db->from('tbl_tag');
 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Tag_Date_int','desc');
    $query = $this->db->get();
    return $query->result();
 	
 }

 public function get_user_primarysecondarytag_detail_image($customerid,$primarytag,$secondarytag,$date,$page,$limit)
 { 
         
         $Period = explode('-',$date);

        $month = $Period[1];
        if($page === '' || $limit === '')
        {

 			$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primarytag AND FIND_IN_SET($secondarytag,t.Secondry_Tag) AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month");
 		}
 		else
 		{
 			$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primarytag AND FIND_IN_SET($secondarytag,t.Secondry_Tag) AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month LIMIT $page, $limit");

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
            array_push($returndata,$data);

   		}
   		return  $returndata;
 }

 public function get_user_gelleryprimarytag_detail($customerid,$primary_tag)
 {
 	
 	$where = "Customer_Id = $customerid AND Primary_Tag = $primary_tag";
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	$this->db->from('tbl_tag');
 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Tag_Date_int','desc');
    $query = $this->db->get();


    return $query->result(); 	
 }

public function get_user_gelleryprimarytag_detail_image($customerid,$primarytag,$date,$page,$limit)
 {
         
         $Period = explode('-',$date);

        $month = $Period[1];
        
        if($page === '' || $limit === '')
        {

 			$image = $this->db->query("SELECT pt.Prime_Name, t.Id, t.isUploaded, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primarytag AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month");
 		}
 		else
 		{
 			$image = $this->db->query("SELECT pt.Prime_Name, t.Id, t.isUploaded, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE t.Primary_Tag = $primarytag AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) =  $month LIMIT $page, $limit");

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
            array_push($returndata,$data);

   		}
   		return  $returndata;
 }

 public function get_user_homedatatagtype_detail($customerid,$tagtype)
 {
 	if($tagtype == 'gallery' || $tagtype == 'document')
 	{
 		$where = "Customer_Id = $customerid"; 	
 	}
 	else
 	{
 		$where = "Customer_Id = $customerid AND Tag_Type = '$tagtype'"; 
 	}
 	
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	
 	if($tagtype == 'document')
 	{
 		$this->db->from('tbl_doc_tag');
 	}
 	else
 	{
 		$this->db->from('tbl_tag');

 	}


 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Tag_Date_int','desc');
    $query = $this->db->get();
    
    return $query->result();	
 }

 public function get_user_homedatatagtype_detail_doc($customerid,$tagtype)
 {
 	
 	$where = "Customer_Id = $customerid AND Tag_Type = '$tagtype'"; 
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	$this->db->from('tbl_doc_tag');
 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Tag_Date_int','desc');
    $query = $this->db->get();    
    return $query->result();	
 }

 public function get_user_homedatatagtype_total($customerid,$tagtype,$table,$value)
 {
 	
 	$where = "Customer_Id = $customerid AND Tag_Type = '$tagtype' AND MONTH(Tag_Send_Date) = $value"; 
 	$this->db->select('Tag_Send_Date as Date, COUNT(Id) as Count, SUM(Amount) as Total');
 	$this->db->from($table);
 	$this->db->where($where);
    $this->db->group_by('MONTH(Tag_Send_Date)');
    $this->db->order_by('Tag_Date_int','desc');
    $query = $this->db->get();    
    return $query->row();	
 }

 public function get_user_homedatatagtype_total_image_gallery($customerid, $tagtype, $value)
 {
      
        $month = $value;
       
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Tag_Type = '$tagtype' AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) = '$month'");

 		$data = $image->result(); 			
   		$returndata = array();
   		foreach($data as $data)
   		{  

   			if($tagtype == 'document')
   			{
   				$data->Type = "document";
   				$data->Secondary_Tag = $this->get_imagesecondarytagdocument($data->Id);

   			} 
   			else{
   				$data->Type = "gallery";
   				$data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
   			} 
   			
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
            array_push($returndata,$data);

   		}
   		return  $returndata;
 }

 public function get_user_homedatatagtype_total_image_document($customerid, $tagtype, $value)
 {
         
        $month = $value;
       
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Doc_Url, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Tag_Type = '$tagtype' AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) = '$month'");
	
		//echo $this->db->last_query(); die;
 		
 		$data = $image->result(); 			
   		$returndata = array();
   		foreach($data as $data)
   		{    
   			$data->Type = "document";
   			$data->Secondary_Tag = $this->get_imagesecondarytagdocument($data->Id);
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

 public function get_user_homedatatagtype_detail_image($customerid, $tagtype, $date)
 {
         
         $Period = explode('-',$date);

        $month = $Period[1];
       
 		if($tagtype == 'gallery')
        {

        	$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) = '$month'");
        }
        else if($tagtype == 'document')
        {
        	$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Doc_Url, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) = '$month'");

        }
        else
        {
        	$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Tag_Type = '$tagtype' AND t.Customer_Id = $customerid AND Month(t.Tag_Send_Date) = '$month'");

        }
	
 		//echo $this->db->last_query(); die;
 		$data = $image->result(); 			
   		$returndata = array();
   		foreach($data as $data)
   		{  

   			if($tagtype == 'document')
   			{
   				$data->Type = "document";
   				$data->Secondary_Tag = $this->get_imagesecondarytagdocument($data->Id);

   			} 
   			else{
   				$data->Type = "gallery";
   				$data->Secondary_Tag = $this->get_imagesecondarytag($data->Id);
   			} 
   			
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
            if($tagtype == 'document')
   			{
	            if($data->Doc_Url == Null)
		    	 {
		    		$data->Doc_Url = "";
		    	 }
		    }
            
            if($data->Description == null)
            {
            	$data->Description = "";
            }
             if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
            array_push($returndata,$data);

   		}
   		return  $returndata;
 }

 public function get_user_homedatatagtype_detail_image_doc($customerid, $tagtype, $date)
 {
         
         $Period = explode('-',$date);

        $month = $Period[1];
       
 			$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.isUploaded, t.Doc_Url, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_doc_tag t LEFT JOIN tbl_doc_primary_tag pt ON t.Primary_Tag = pt.Id WHERE t.Tag_Type = '$tagtype' AND t.Customer_Id = $customerid");
	
 		
 		$data = $image->result(); 			
   		$returndata = array();
   		foreach($data as $data)
   		{    
   			$data->Type = "document";
   			$data->Secondary_Tag = $this->get_imagesecondarytagdocument($data->Id);
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
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
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
       		$this->db->from('tbl_secondary_tag');       		
       		$this->db->where('Customer_Id = ', $customerid);       		
       		$this->db->order_by('Secondary_Name', 'asc');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;
       		return $query->result();

 }

 public function secondary_tag_total($secondaryid)
 {
 		
 		$arraysum = array();
        $totalamount = $this->db->query("SELECT * FROM tbl_tag WHERE FIND_IN_SET($secondaryid,Secondry_Tag)");
       
        $data = $totalamount->result();

        foreach($data as $data)
   		{
           $arraysum[] = $data->Amount;

   		}        
        return $data = array_sum($arraysum);
 }

 public function all_secondary_image_data($secondaryid)
 {
 		$image = $this->db->query("SELECT pt.Prime_Name,t.Id, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Image_Url_Thumb, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description FROM tbl_tag t LEFT JOIN tbl_primary_tag pt ON t.Primary_Tag	= pt.Id WHERE FIND_IN_SET($secondaryid,t.Secondry_Tag)");
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
            array_push($returndata,$data);

   		}
   		return  $returndata;
 		
 }

public function gallery_primary_tag_amount($customerid)
 {
    		$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
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
       		$this->db->from('tbl_secondary_tag');       		
       		$this->db->where('Customer_Id = ', $customerid);
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;
       		return $query->result();

 }

 public function gallery_primary_tag_count($customerid)
 {
    		$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total, COUNT(t.Id) as Count');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
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
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');
       		$this->db->order_by('COUNT(t.Id)', 'DESC');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;      		
       		return $query->result();

 }

 /** Filter Code End **/

 public function delete_tag($customerid, $tagid)
 {
 	
 	$this->db->where('Id', $tagid);
 	$this->db->where('Customer_Id', $customerid);
	$is_delete = $this->db->delete('tbl_tag');

	if($is_delete)
	{

		return true;
	}
	else
	{
		return false;

	}

 }

 public function get_user_profile_details($customerid)
 {
    
	    $query = $this->db->get_where('tbl_customer', array('Id' => $customerid));
	    $returndata = array();

	    if($query->num_rows() > 0)
	    {

	    	  $getUserDetails = $query->row();	    	
                
	            $this->first_name = $getUserDetails->First_Name == null ? '' : $getUserDetails->First_Name;

				$this->last_name = $getUserDetails->Last_Name == null ? '' : $getUserDetails->Last_Name;

				$this->gender = $getUserDetails->Gender == null ? '' : $getUserDetails->Gender;

				$this->dob = $getUserDetails->Dob == null ? '' : $getUserDetails->Dob;

				$this->pan_number = $getUserDetails->Pan_Number == null ? '' : $getUserDetails->Pan_Number;

				$this->company_name = $getUserDetails->Company_Name == null ? '' : $getUserDetails->Company_Name;

				$this->gstn = $getUserDetails->Gstn == null ? '' : $getUserDetails->Gstn;

				$this->city = $getUserDetails->City == null ? '' : $getUserDetails->City;

				$this->aadhar_number = $getUserDetails->Aadhar_No == null ? '' : $getUserDetails->Aadhar_No;

				$this->Email_Id = $getUserDetails->Email_Id == null ? '' : $getUserDetails->Email_Id;

				$this->phone = $getUserDetails->Phone_No == null ? '' : $getUserDetails->Phone_No;

				$this->alt_phone = $getUserDetails->Alt_Phone_No == null ? '' : $getUserDetails->Alt_Phone_No;

				$this->skype_id = $getUserDetails->Skype_Id == null ? '' : $getUserDetails->Skype_Id;

				$this->profile_image_url = $getUserDetails->Profile_Image_Url == null ? '' : $getUserDetails->Profile_Image_Url;

	            array_push($returndata,$this);
	            
	            return  $returndata;
	            
	    }
	    


 }

 public function update_user_profile_details($customerid,$data)
 {
	 	$this->db->where('Id', $customerid);
		$update = $this->db->update('tbl_customer',$data);
		if($update)
		{

			return true;
		}
		else
		{
			return false;

		}
 }

 public function setpermission($customerid, $type, $value)
 {
 	
 	if($type == 'g')
 	{

 		$data = array("permission_g" => $value);
 	}
 	else if($type == 'd')
 	{
 		$data = array("permission_d" => $value);

 	}
 	else if($type == 'r')
 	{
 		$data = array("permission_r" => $value);

 	}
 	else if($type == 'Paid_Status')
 	{
 		$data = array("Paid_Status" => $value);

 	}

 	$this->db->where('Id', $customerid);
 	     
	$is_update = $this->db->update('tbl_customer', $data);

	if($is_update)
	{

		return true;
	}
	else
	{
		return false;

	}

 }

 public function contact_us($tbl, $data)
 {
 	$this->db->insert($tbl,$data);
	return true;
 }

 public function get_zoddl_details()
 {

	 	$this->db->select('email as Email_Id, phone_number as phone, website as Website');
		$this->db->from('tbl_users');
		$this->db->where('id',1);
		$query = $this->db->get();
		return $query->row();
 }

 public function get_contact_details($customerid)
 {
 		$this->db->select('Message, senddate as Date');
		$this->db->from('tbl_contact');
		$this->db->where('Customer_Id',$customerid);
		$this->db->order_by('senddate', 'DESC'); 
		$this->db->limit(10);
		$query = $this->db->get();
		$data = $query->result();

		//echo $this->db->last_query(); die;

		$returndata = array();
   		foreach($data as $data)
   		{            
   			
            if($data->Message == null)
            {
            	$data->Message = "";
            }

            if($data->Date != 0)
            {
            	$data->Date = date("d-m-Y", $data->Date);
            }           
            
            array_push($returndata,$data);

   		}
   		return  $returndata;

 }


}
