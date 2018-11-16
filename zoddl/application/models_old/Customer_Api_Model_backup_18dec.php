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
					'Social_F_Id'=>$this->input->post('Social_Id')
					  );
			}
			else
			{
			   $data = array(
					'Email_Id'=>$this->input->post('Email_Id'),
					'Login_Type'=>$this->input->post('Login_Type'),
					'User_Type'=>2,
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
						        'Login_Type' => 'm'
						     );
				$this->db->where('Email_Id', $Email_Id);
    			$this->db->update($tableName, $data);
    			return "You have successfully registered.";

			}
		}
	   else
	       {
			$data = array(
					'Email_Id'=>$this->input->post('Email_Id'),
					'Password'=>md5($this->input->post('Password')),
					'User_Type'=>2,
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
	  	
		if($Secondry_Tag == '')
		{
			
			$is_secondry = $this->db->get_where($tablesecondry, array('Customer_Id' => $customerid,'Secondary_Name' => 'Untagged'));
		}
		else
		{
			
			$is_secondry = $this->db->get_where($tablesecondry, array('Customer_Id' => $customerid,'Secondary_Name' => $Secondry_Tag));
		}
		
		
		if($is_secondry->num_rows() > 0)
		{
		     $secondryid = $is_secondry->row();	
		     return $secondryid->Id;		
		}
		else
		{

			if($Secondry_Tag == '')
			{
				$is_secondry = $this->db->get_where($tablesecondry, array('Customer_Id' => $customerid,'Secondary_Name' => "Untagged"));
				if($is_secondry->num_rows() > 0)
					{
					     $secondryid = $is_secondry->row();	
					     return $secondryid->Id;		
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
			

				$data = array(
	       				       'Customer_Id'=>$customerid,
					       'Secondary_Name'=>$Secondry_Tag
	    				   );

	    				$this->db->insert($tablesecondry,$data);
						$secondry_id = $this->db->insert_id();
	   					return  $secondry_id;
   			}
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
    		$this->db->select('pt.Prime_Name,t.Primary_Tag, SUM(t.Amount) as Total');
       		$this->db->from('tbl_tag t');
       		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
       		$this->db->where('t.Customer_Id = ', $customerid);
       		$this->db->group_by('t.Primary_Tag');       		
       		$query = $this->db->get(); 
       		//echo $this->db->last_query(); die;      		
       		return $query->result();

 }

public function all_image_data($primaryid)
 {
 		$this->db->select('pt.*,st.Secondary_Name,t.*');
   		$this->db->from('tbl_tag t');
   		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
   		$this->db->join('tbl_secondary_tag st','t.Secondry_Tag = st.Id','left');
   		$this->db->where('t.Primary_Tag = ', $primaryid);   		
   		$query = $this->db->get();       		
   		$data = $query->result();   		
   		$returndata = array();
   		foreach($data as $data)
   		{
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }
            if($data->Invoice_Check_No == null)
            {
            	$data->Invoice_Check_No = "";
            }
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
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

public function get_gallery_data_by_primarytag($customerid, $tagtype = '')
 {

 		$this->db->select('pt.Prime_Name,st.Secondary_Name,t.*');
   		$this->db->from('tbl_tag t');
   		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
   		$this->db->join('tbl_secondary_tag st','t.Secondry_Tag = st.Id','left');
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
            if($data->Prime_Name == null)
            {
            	$data->Prime_Name = "";
            }
            if($data->Invoice_Check_No == null)
            {
            	$data->Invoice_Check_No = "";
            }
            if($data->Image_Url == null)
            {
            	$data->Image_Url = "";
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

/*public function get_tag_details($customerid, $tagid)
 {
 		$this->db->select('pt.Prime_Name,t.*,c.Customer_Name');
   		$this->db->from('tbl_tag t');
   		$this->db->join('tbl_primary_tag pt','t.Primary_Tag	= pt.Id','left');
   		$this->db->join('tbl_customer c','t.Customer_Id = c.Id','left');
   		$this->db->where('t.Customer_Id = ', $customerid);
   		$this->db->where('t.Id = ', $tagid);
   		return $this->db->get()->row();		

 } */

 public function get_imagesecondarytag($imageId)
 {
 		$getSecondaryTag = $this->db->get_where('tbl_tag',array('Id' => $imageId))->row()->Secondry_Tag;
 		
 		$secData = $this->db->query("select Id, Secondary_Name  from tbl_secondary_tag where Id IN ($getSecondaryTag)")->result();

 		return $secData;



 }

 public function get_useritem_tag_details($customerid, $tagid)
 {
 		
   		$this->db->select('pt.Prime_Name,t.Id, t.Customer_Id, t.Primary_Tag,t.Amount,t.Image_Url, t.Description, t.Tag_Type, t.Tag_Send_Date, t.Tag_Status, t.Sub_Description');
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
            /*if($data->Image_Url_Thumb == null)
            {
            	$data->Image_Url_Thumb = "";
            } */
             if($data->Description == null)
            {
            	$data->Description = "";
            }
             if($data->Sub_Description == null)
            {
            	$data->Sub_Description = "";
            }
           //array_push($returndata,$data);
           return  $data;

 }

 public function check_secondry_tag_new($tablesecondry,$customerid,$Secondry_Tag)
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

 public function get_useritem_edit_tag_details($tabletag, $tagId, $data)
   {

   		 $this->db->where('Id', $tagId);
    	 return $this->db->update($tabletag, $data);

   }

}
