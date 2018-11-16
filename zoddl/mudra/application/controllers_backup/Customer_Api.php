<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Customer_Api Controller 
 */

require APPPATH . '/libraries/REST_Controller.php';

class Customer_Api extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Customer_Api_Model');

    }
    
	/**
	 * @param: Email_Id(type string)
	 * @param: Password(type string)
	 * @param: Login_Type(type string)
	 * @param: Device_Token(type string)
	 * @param: Device_Type(type string)	
	 * @Description: Logging Customer
	 * @Author: Antaryami
	 * @Created: 27 Nov 2017
	 */
        
	public function customerlogin_post()
	{
	if ($this->post('Login_Type') == 'm' && $this->post('Login_Type') != 'f' && $this->post('Login_Type') != 'g')
		{
		if (empty($this->post('Email_Id')) || empty($this->post('Password')))
			{
			$response_data['ResponseCode'] = "400";
			$response_data['ResponseMessage'] = 'Fields are required.';
			$this->response($response_data);
			}
		  else
			{
			$url = base_url();
			$device_token = $this->post('Device_Token');
			$device_type = $this->post('Device_Type');
			$tableName = "tbl_customer";
			$customer = $this->Customer_Api_Model->get_login_data($tableName, $this->post('Email_Id') , $this->post('Password'));
			$customerdetail = array();
			if ($customer)
				{
				$this->Customer_Api_Model->update_device_entry($device_type, $customer->Id, $device_token);				
				$authtoken = $this->Customer_Api_Model->generateToken($tableName, $customer->Id);
				$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Customer Logged in Successfully';
				$customerdetail['Customerid'] = $customer->Id;
				$customerdetail['Paid_Status'] = $customer->Paid_Status;
				$customerdetail['Authtoken'] = $authtoken;
				$customerdetail['BaseUrl'] = $url;
				$response['CustomerDetail'] = $customerdetail;
				$this->response($response);

				}
			  else
				{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Invalid email or password';
				$this->response($response);
				}
			}
		}
	  else
		{
		if (empty($this->post('Email_Id')) || empty($this->post('Login_Type')) || empty($this->post('Social_Id')))
			{
			$response_data['ResponseCode'] = "400";
			$response_data['ResponseMessage'] = 'Fields are required.';
			$this->response($response_data);
			}
		  else
			{
			$url = base_url();
			$device_token = $this->post('Device_Token');
			$device_type = $this->post('Device_Type');
			$tableName = "tbl_customer";
			$socialcustomer = $this->Customer_Api_Model->check_social_login($tableName, $this->post('Email_Id') , $this->post('Login_Type') , $this->post('Social_Id'));
			$Socialdetail = array();
			if ($socialcustomer)
				{
				$this->Customer_Api_Model->update_device_entry($device_type, $socialcustomer->Id, $device_token);
				$authtoken = $this->Customer_Api_Model->generateToken($tableName, $socialcustomer->Id);
				$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Customer Logged in Successfully';
				$socialcustomerdetail['Customerid'] = $socialcustomer->Id;
				$socialcustomerdetail['Authtoken'] = $authtoken;
				$socialcustomerdetail['BaseUrl'] = $url;
				$socialcustomerdetail['Paid_Status'] = $socialcustomer->Paid_Status;
				$response['CustomerDetail'] = $socialcustomerdetail;
				$this->response($response);
				}
			  else
				{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Invalid login detail.';
				$this->response($response);
				}
			}
		}
	}
	
	
     /**
	 * @param: Email_Id(type string)
	 * @param: Password(type string)
	 * @param: Login_Type(type string)
	 * @param: Device_Token(type string)
	 * @param: Device_Type(type string)	
	 * @Description: Signup Customer
	 * @Author: Antaryami
	 * @Created: 27 Nov 2017
	 */


	public function _send_verificationemai($email = '')
	{
		if($email != '')
		{

			    $to = $email;
				$tablecustomer = 'tbl_customer';
                $subject = 'Verification Mail';

                $cDetail = $this->db->get_where($tablecustomer, array('Email_Id' => $email))->row();

                $data['cid'] = $cDetail->Id;
                $data['vcode'] = $cDetail->Varification_Key;

                $body = $this->load->view('mailers/verification-mail', $data, true);
                
                $send = Send_Mail($to, $subject, $body);

                if($send)
                {
                	return TRUE;
                }
                else
                {
                	return FALSE;

                }

		}
		else
		{
			return FALSE;
		}


	}

    public function customersignup_post()
	{
		 
		 if(empty($this->post('Email_Id')) ||empty($this->post('Password')) || empty($this->post('Login_Type')) || $this->post('Email_Id') == NULL || $this->post('Password') == NULL || $this->post('Login_Type') == NULL){

			    $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
		    }
		else
		   {
			$url = base_url();
			$device_token = $this->post('Device_Token');
			$device_type  = $this->post('Device_Type');
			$tableName = "tbl_customer";
			$addcustomer = $this->Customer_Api_Model->add_customer($tableName,$this->post('Email_Id'),$this->post('Password'),$this->post('Login_Type'));
			$adddetail = array();
				if($addcustomer == "You have successfully registered."){
			   
		    		if($this->_send_verificationemai($this->post('Email_Id')) == TRUE)
					{
						$response['ResponseCode'] = "200";
						$response['ResponseMessage'] ="You have registered successfully. Please login to continue.";						
						$adddetail['BaseUrl'] = $url;			
						$response['AddDetail'] = $adddetail;	      
						$this->response($response);
					}					
					else{

							$response['ResponseCode'] = "400";
							$response['ResponseMessage'] ="Sorry some technical problem.";						
							$adddetail['BaseUrl'] = $url;			
							$response['AddDetail'] = $adddetail;	      
							$this->response($response);

					}  
		    
				}else {
					$response['ResponseCode'] = "400";
					$response['ResponseMessage'] = "You have already registered with this email id. Please login to continue.";
					$this->response($response);     
				     }

		    }
		}

	/**
	 * @param: Customer_Id(type string)
	 * @param: Device_Token(type string)
	 * @Description: Logout Customer	
	 * @Author: Antaryami
	 * @Created: 27 Nov 2017
	 */

      public function logout_post()
    	{
		if(empty($this->post('Customerid')) || empty($this->post('Device_Token')))
			{
	            $response_data['ResponseCode'] = "400";
				$response_data['ResponseMessage'] = 'Fields are required.';
				$this->response($response_data);
			}else{	
			
			$customerid = $this->post('Customerid');
			$Device_Token = $this->post('Device_Token');
			
			$table = 'tbl_customer_device';
			$condition=array("Customer_Id" => $customerid,"Device_Token" => $Device_Token);

			$response['ResponseCode'] = "200";
			$response['ResponseMessage'] = 'Logout successfully.';
			$this->response($response);

			/*$delete_token = $this->Customer_Api_Model->delete_data($table,$condition);
			if($delete_token){
				$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Logout successfully.';
				$this->response($response);
			}else{
				$response['ResponseCode'] = "0";
				$response['ResponseMessage'] = 'Oops something went wrong.';
				$this->response($response);
				} */
	     }
   	 }

   	function forget_post()
	{
		$token = time();
        $sendEmail   = $this->input->post('Email_Id');

        if (empty($this->post('Email_Id')))
			{                
                $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
			}
		elseif ($this->Customer_Api_Model->exists_email($this->input->post('Email_Id')) == 0) {
				
				$response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Please enter your registered email id.';
			    $this->response($response_data);           
        	}
		else
			{
				if ($token) {                

	                $cDetail = $this->db->get_where('tbl_customer', array('Email_Id' => $sendEmail))->row();
	                $data['cid'] = $cDetail->Id;
	                $data['token'] = $token;
	                $to = $sendEmail;
	                $subject = 'Forget Password';

	                $data['resetlink'] = $lostpw_code;
	                $body = $this->load->view('mailers/admin-forget-mail', $data, true);
	                
	                $send = Send_Mail($to, $subject, $body);

                if ($send) {                    
                    $this->Customer_Api_Model->update_user_token($this->input->post('Email_Id'), $token);
                    $response['ResponseCode'] = "200";
					$response['ResponseMessage'] = 'Please check your email id.';			
					$this->response($response);  

                } else {
                    
                    $response['ResponseCode'] = "400";
					$response['ResponseMessage'] = 'Sorry try again.';			
					$this->response($response);
                }
            }

			}

	}

	/**
	 * @param: Authtoken(type string)	 
	 * @Description: Show all Customer Primary Tag 	
	 * @Author: Antaryami
	 * @Created: 28 Nov 2017
	 */

     public function primarytag_post()
	 {
        $customerauthtoken = $this->post('Authtoken');

		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);

		if($customerid){
		
		$tableprimetag = "tbl_primary_tag";
		$getcustomerprimetag = $this->Customer_Api_Model->get_customer_prime_tag($tableprimetag, $customerid);
		$primarytaglist = array();
		
			foreach($getcustomerprimetag as $primetag)
				{
				   $primetags['Id'] = $primetag->Id;
				   $primetags['Prime_Name'] = $primetag->Prime_Name;
				   $primarytaglist[] = $primetags;	

				}
			  	$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Primary Tag fetched successfully.';
				$response['PrimaryTag'] = $primarytaglist;
				$this->response($response);
			}
		     
		
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}
              
	}

	/**
	 * @param: Authtoken(type string)	 
	 * @Description: Show all Customer Secondary Tag 	
	 * @Author: Antaryami
	 * @Created: 28 Nov 2017
	 */

      public function secondarytag_post()
	 {
		$customerauthtoken = $this->post('Authtoken');		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		
		$tablesecondtag = "tbl_secondary_tag";
		$getcustomersecondtag = $this->Customer_Api_Model->get_customer_second_tag($tablesecondtag, $customerid);
		$secondtaglist = array();
		
			foreach($getcustomersecondtag as $secondtag)
				{
				   $secondtags['Id'] = $secondtag->Id;
				   $secondtags['Secondary_Name'] = $secondtag->Secondary_Name;
				   $secondtaglist[] = $secondtags;	

				}
			  	$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Secondary Tag fetched successfully.';
				$response['SecondaryTag'] = $secondtaglist;
				$this->response($response);
			}
		     
		
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}
              
              
	}

	/**
	 * @param: Authtoken(type string)	 
	 * @Description: Add Manual Customer Tag 	
	 * @Author: Antaryami
	 * @Created: 29 Nov 2017
	 */

	public function addmanualtag_post()
	 {
		
           
		$customerauthtoken = $this->post('Authtoken');		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		       if ( empty($this->post('Tag_Send_Date')))
			{
                $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
			}
			else
			{
				$tabletag = "tbl_tag";				
				$tableprimary = "tbl_primary_tag";
				$tablesecondry = "tbl_secondary_tag";
				$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,$this->post('Prime_Name'));
				$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Tag'));
				$amount = $this->post('Amount');
				$description = $this->post('Description');
				$tagtype = $this->post('Tag_Type');
				$tagsenddate = $this->post('Tag_Send_Date');
				if($this->post('Tag_Status') == 6)
				{
					$tagstatus = 2;
				}
				else
				{
					$tagstatus = $this->post('Tag_Status');
				}
				$mysqldate = date('Y-m-d', strtotime($tagsenddate));
				if($description == '')
				{
					$description = null;

				}
				else
				{
					$description = $description;

				}
				
				$data = array(
						'Customer_Id'=>$customerid,
						'Primary_Tag'=>$checkprimarytag,
						'Secondry_Tag'=>$checksecondrytag,
						'Amount'=>$amount,
						'Description'=>$description,
						'Tag_Type'=>$tagtype,
						'Tag_Send_Date'=>$tagsenddate,
						'Tag_Status'=>$tagstatus,
						'Tag_Date'=>$mysqldate,
						'isUploaded'=>0,
						'Tag_Date_int'=>strtotime($tagsenddate)
					    );

			   	$insert_tag = $this->Customer_Api_Model->insert_tag($tabletag,$data);
				if($insert_tag)
				   {
					$response['ResponseCode'] = "200";
					$response['ResponseMessage'] = 'Add manual tag successfully.';					
					$this->response($response);
				     }
				else
				   {
					$response['ResponseCode'] = "200";
					$response['ResponseMessage'] = 'Some technical problem.';					
					$this->response($response);
				   }
			}
		}
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}


	 }
	
	/**
	 * @param: Authtoken(type string)	 
	 * @Description: Add Customer Tag 	
	 * @Author: Antaryami
	 * @Created: 29 Nov 2017
	 */
	
	public function addtag_post()
	 {
		$customerauthtoken = $this->post('Authtoken');		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		 	 if (empty($this->post('Tag_Send_Date')))
			{
                $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
			}
			else
			{
				$tabletag = "tbl_tag";				
				$tableprimary = "tbl_primary_tag";
				$tablesecondry = "tbl_secondary_tag";
				$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,$this->post('Prime_Name'));
				

				$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Tag'));
				
				$amount = $this->post('Amount');
				$imageurl = $this->post('Image_Url');
				$imageurlthumb = $this->post('Image_Url_Thumb');
				$tagtype = $this->post('Tag_Type');
				$description = $this->post('Description');
				$tagsenddate = $this->post('Tag_Send_Date');
				if($this->post('Tag_Status') == 6)
				{
					$tagstatus = 2;
				}
				else
				{
					$tagstatus = $this->post('Tag_Status');
				}

				if($this->post('Image_Url') == '' && $this->post('Image_Url_Thumb') == '')
				{
					$isUploaded = 0;
				}
				else
				{
					$isUploaded = 1;
				}

				$mysqldate = date('Y-m-d', strtotime($tagsenddate));
				
				$data = array(
						'Customer_Id'=>$customerid,
						'Primary_Tag'=>$checkprimarytag,
						'Secondry_Tag'=>$checksecondrytag,
						'Amount'=>$amount,
						'Image_Url'=>$imageurl,
						'Image_Url_Thumb'=>$imageurlthumb,
						'Tag_Type'=>$tagtype,
						'Description'=>$description,
						'Tag_Send_Date'=>$tagsenddate,
						'Tag_Status'=>$tagstatus,
						'Tag_Date'=>$mysqldate,
						'isUploaded'=>$isUploaded,
						'Tag_Date_int'=>strtotime($tagsenddate)
					    );

			   	$insert_tag = $this->Customer_Api_Model->insert_tag($tabletag,$data);
				if($insert_tag)
				   {
					$response['ResponseCode'] = "200";
					$response['ResponseMessage'] = 'Add tag successfully.';					
					$this->response($response);
				     }
				else
				   {
					$response['ResponseCode'] = "200";
					$response['ResponseMessage'] = 'Some technical problem.';					
					$this->response($response);
				   }
			}
		}
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}


	 }

	/**
	 * @param: Authtoken(type string)	 
	 * @Description: Show All Customer Tag 	
	 * @Author: Antaryami
	 * @Created: 29 Nov 2017
	 */
	
	 public function showalltag_post()
	 {
		$customerauthtoken = $this->post('Authtoken');		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){

			$tabletag = "tbl_tag";
			$getcustomertag = $this->Customer_Api_Model->get_customer_tag($tabletag, $customerid);
			$taglist = array();
			
			$tableprimary = "tbl_primary_tag";
			$tablesecondry = "tbl_secondary_tag";

			foreach($getcustomertag as $tag)
				{
                     
				   $primaryTag = $this->Customer_Api_Model->get_primarytag($tableprimary, $tag->Primary_Tag);
				   $secondryTag = $this->Customer_Api_Model->get_secondrytag($tablesecondry, $tag->Secondry_Tag);

				   if($tag->Description == null)
				   {

				   		$desc = '';
				   }
				   else
				   {
				   		$desc = $tag->Description;

				   }


				   if($tag->Image_Url == null)
				   {

				   		$imageurl = '';
				   }
				   else
				   {

				   		$imageurl = $tag->Image_Url;
				   }


				   if($tag->Sub_Description == null)
				   {

				   		$subdesc = '';
				   }
				   else
				   {
				   		$subdesc = $tag->Sub_Description;

				   }

				   if($tag->Invoice_Check_No == null)
				   {

				   	$icn = '';
				   }
				   else
				   {
				   	 $icn = $tag->Invoice_Check_No;

				   }

				   if($tag->Image_Url_Thumb == null)
				   {

				   	$iut = '';
				   }
				   else
				   {
				   		$iut = $tag->Image_Url_Thumb;

				   }

				   
				   $tags['Id'] = $tag->Id;
				   $tags['Customer_Id'] = $tag->Customer_Id;
				   $tags['Primary_Tag'] = $primaryTag;
				   $tags['Secondary_Tag'] = $secondryTag;
				   $tags['Amount'] = $tag->Amount;
				   $tags['Image_Url'] = $imageurl;
				   $tags['Image_Url_Thumb'] = $iut;
				   $tags['Description'] = $desc;
				   $tags['Tag_Type'] = $tag->Tag_Type;
				   $tags['Sub_Description'] = $subdesc;
				   $tags['CGST'] = $tag->CGST;
				   $tags['SGST'] = $tag->SGST;
				   $tags['IGST'] = $tag->IGST;
				   $tags['Invoice_Check_No'] = $icn;
				   $tags['Tag_Date'] = $tag->Tag_Send_Date;
				   $tags['Tag_Status'] = $tag->Tag_Status;				   
				   $taglist[] = $tags;	

				}
			  	$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Tag fetched successfully.';
				$response['Tag'] = $taglist;
				$this->response($response);
		 
		}
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}


	 } 

	
	public function gallerytag_post()
	 {
	 	$customerauthtoken = $this->post('Authtoken');
	 	$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);		
		$allprimarytagimagedata = array();
		$testedarray = array();
		if($customerid){
				$getallprimarytag = $this->Customer_Api_Model->gallery_primary_tag($customerid);
				
				
				foreach($getallprimarytag as $key => $gapt)
				{
						
						$getallimage = $this->Customer_Api_Model->all_image_data($gapt->Primary_Tag);

						$gapt->Timestamp = $this->Customer_Api_Model->get_lasttimestamp_data($gapt->Primary_Tag);

						$gapt->Count = $this->Customer_Api_Model->all_image_data_count($gapt->Primary_Tag); 

						$gapt->Images = $getallimage;

						array_push($allprimarytagimagedata,$gapt);

				}
				
				$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Tag fetched successfully.';
				$response['Taglist'] = $allprimarytagimagedata;
				$this->response($response);
		}
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}	

	 }

	 public function homedata_post()
	 {
	 	$customerauthtoken = $this->post('Authtoken');
	 	$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);		
		$payload = array();
		$gallery = array();
		$bankplusarray = array();
		$bankminusarray = array();
		$cashplusarray = array();
		$cashminusarray = array();
		$otherarray = array();
		if($customerid){

				$cashplus = "cash+";
				$cashminus = "cash-";
				$bankplus = "bank+";
				$bankminus = "bank-";
				$other = "other";

				$allgallerydata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid);
				$allgalleryTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid);				
				
				$gallery['Tag_Type'] = "gallery";	
				$gallery['Total'] = $allgalleryTotal;									
				$gallery['Images'] = $allgallerydata;
				$payload['Gallerydata'] = $gallery;
				
                
				$allbankplusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $bankplus);				
				$allbankplusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $bankplus);

				$bankplusarray['Tag_Type'] = $bankplus;	
				$bankplusarray['Total'] = $allbankplusTotal;
				$bankplusarray['Images'] = $allbankplusdata;
				$payload['Bankplusdata'] = $bankplusarray;
				

				$allbankminusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $bankminus);
				$allbankminusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $bankminus);
				
				$bankminusarray['Tag_Type'] = $bankminus;	
				$bankminusarray['Total'] = $allbankminusTotal;
				$bankminusarray['Images'] = $allbankminusdata;
				$payload['Bankminusdata'] = $bankminusarray;
				

				$allcashplusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $cashplus);
				$allcashplusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $cashplus);

				$cashplusarray['Tag_Type'] = $cashplus;	
				$cashplusarray['Total'] = $allcashplusTotal;
				$cashplusarray['Images'] = $allcashplusdata;
				$payload['Cashplusdata'] = $cashplusarray;
				

				
				$allcashminusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $cashminus);
				$allcashminusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $cashminus);
				
				$cashminusarray['Tag_Type'] = $cashminus;	
				$cashminusarray['Total'] = $allcashminusTotal;
				$cashminusarray['Images'] = $allcashminusdata;
				$payload['Cashminusdata'] = $cashminusarray;
				

				$allotherdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $other);
				$allotherdataTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $other);

				$otherarray['Tag_Type'] = $other;	
				$otherarray['Total'] = $allotherdataTotal;
				$otherarray['Images'] = $allotherdata;
				$payload['Otherdata'] = $otherarray; 
				


				$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Home page fetched successfully.';				
				$response['Payload'] = $payload;
				$this->response($response);
		}
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}	

	 }

public function useritemtagdetails_post()
	 {
		 	$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			if($customerid)
			{

				 if (empty($this->post('Id')))
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{
                        $getTagDetails = $this->Customer_Api_Model->get_useritem_tag_details($customerid, $this->post('Id'));

                        
                        //$payload['useritemtagdetails'] = $getTagDetails;
                        $response['ResponseCode'] = "200";
						$response['ResponseMessage'] = 'Details Fetched successfully.';				
						$response['Payload'] = $getTagDetails;
						$this->response($response);                        

					}

			
			}
		 	else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

	 }

	 public function useritemedittagdetails_post()
	 {
		 	$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			if($customerid)
			{

				 if ($this->post('Id') == '' || $this->post('Tag_Send_Date') == '')
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{
                        
						$primary_Tag_Name = $this->post('Prime_Name');
						$amount = $this->post('Amount');
						$tagsenddate = $this->post('Tag_Send_Date');
						$Secondary_Tag = $this->post('Secondary_Tag');
						$description = $this->post('Description');
						$imageurl = $this->post('Image_Url');
						$imageurlthumb = $this->post('Image_Url_Thumb');
						$CGST = $this->post('CGST');
						$SGST = $this->post('SGST');
						$IGST = $this->post('IGST');
						$tabletag = "tbl_tag";				
						$tableprimary = "tbl_primary_tag";
						$tablesecondry = "tbl_secondary_tag";
						$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,$primary_Tag_Name);
						$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$Secondary_Tag);

						if($this->post('Image_Url') == '' && $this->post('Image_Url_Thumb') == '')
						{
							$isUploaded = 0;
						}
						else
						{
							$isUploaded = 1;
						}
						$mysqldate = date('Y-m-d', strtotime($tagsenddate));

						$data = array(						
						'Primary_Tag'=>$checkprimarytag,
						'Secondry_Tag'=>$checksecondrytag,
						'Amount'=>$amount,
						'Image_Url'=>$imageurl,	
						'Image_Url_Thumb'=>$imageurlthumb,
						'CGST'=>$CGST,
						'SGST'=>$SGST,
						'IGST'=>$IGST,	
						'Description'=>$description,						
						'Tag_Send_Date'=>$tagsenddate,
						'Tag_Date'=>$mysqldate,							
						'isUploaded'=>$isUploaded,
						'Tag_Date_int'=>strtotime($tagsenddate)
					    );

                        $getTagDetails = $this->Customer_Api_Model->get_useritem_edit_tag_details($tabletag, $this->post('Id'), $data);                        
                        
                        if($getTagDetails)
                        {
	                        $response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Update successfully.';
							$this->response($response);
						}
						else{

							 $response['ResponseCode'] = "400";
							 $response['ResponseMessage'] = 'Some technical problem.';
							 $this->response($response);
						}                        

					}

			
			}
		 	else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

	 }

  public function userprimarytagsearchlist_post()
  {

		 	$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';

			if($customerid)
			{

				 if (empty($this->post('Primary_Tag')))
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{

						$primarytagid = $this->post('Primary_Tag');
						 $get_primary_secondary_tag = $this->db->query("select Secondry_Tag from tbl_tag where Customer_Id = $customerid AND Primary_Tag = $primarytagid")->result();
               				//echo $this->db->last_query(); die;
			               foreach($get_primary_secondary_tag as $st)
			               {
			                   $allst .= $st->Secondry_Tag.","; 
			               }
          
				          $filterValue = trim($allst,',');
				          $arraySecondaryValue = explode(',',$filterValue);
				          $uniqeValue = array_unique($arraySecondaryValue);
                          $getsearchTagDetails = $this->Customer_Api_Model->get_user_primarytag_list($customerid,$uniqeValue);
                          $tableprimary = "tbl_primary_tag";
                          foreach($getsearchTagDetails as $key => $gapt)
							{
									
									$getallimage = $this->Customer_Api_Model->all_secondarytag_image_data($gapt->Secondary_Tag, $customerid, $primarytagid);

									$gapt->Primary_Tag = $primarytagid;
									$gapt->Prime_Name = $this->Customer_Api_Model->get_primarytag($tableprimary, $primarytagid);	
									$gapt->Total = $allTotal = $this->Customer_Api_Model->get_all_secondarytag_total($gapt->Secondary_Tag, $customerid, $primarytagid);
									$gapt->Count = count($getallimage);
									$gapt->Images = $getallimage;

									array_push($payload,$gapt);

							}

							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Secondary Tag fetched successfully.';
							$response['Payload'] = $payload;
							$this->response($response);
					}

			
			}
		 	else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

 public function userprimarytagsearchdetails_post()
  { 
  			$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					$primary_tag = $this->post('Primary_Tag');
					$secondary_tag = $this->post('Secondary_Tag');
					$page = $this->post('Page');
					$limit = 20;
					if($this->post('Primary_Tag') == '' || $this->post('Secondary_Tag') == '' || $this->post('Page') == '' || $this->post('Page') == '0')
					{
						$response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);

					}
					else
					{
						 if($page == '1')
						   {
						   		$page = 0;

						   }
						   else
						   {
						   		$page = ($page-1) * $limit;

						   }

						 $getsearchTagviewDetails = $this->Customer_Api_Model->get_user_primarysecondarytag_detail($customerid,$primary_tag,$secondary_tag);

						 foreach($getsearchTagviewDetails as $key => $gapt)
						 {
						 	$gapt->Prime_Name = $this->db->get_where('tbl_primary_tag', array('Id' => $primary_tag))->row()->Prime_Name;
						 	$gapt->Month = date('F',strtotime($gapt->Date));
						 	$gapt->Images = $this->Customer_Api_Model->get_user_primarysecondarytag_detail_image($customerid,$primary_tag,$secondary_tag,$gapt->Date,$page,$limit);
						 	$Flag_Count = count($gapt->Images);
						 	if($Flag_Count == 20)
						 	{
						 		$Flag = 1;

						 	}
						 	else
						 	{
						 		$Flag = 0;

						 	}
						 	array_push($payload,$gapt);
						 }

						$response['ResponseCode'] = "200";
						$response['ResponseMessage'] = 'Fetched successfully.';
						$response['Flag'] = $Flag;
						$response['Payload'] = $payload;
						$this->response($response);

					}
			}
		 	else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function usersecondarytagsearchlist_post()
  {
  			$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allsecondarytagimagedata = array();			
			if($customerid)
			{
				 if (empty($this->post('Secondary_Tag')))
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{
						$secondarytagid = $this->post('Secondary_Tag');
						$getsearchTagDetails = $this->Customer_Api_Model->get_user_secondarytag_list($customerid,$secondarytagid);
						$tablesecondry = "tbl_secondary_tag";

						 foreach($getsearchTagDetails as $key => $gapt)
							{
									$gapt->Secondary_Tag = $secondarytagid;
									$gapt->Secondary_Name = $this->Customer_Api_Model->get_secondrytag($tablesecondry, $secondarytagid);	
									$getallimage = $this->Customer_Api_Model->all_primarytag_image_data($gapt->Primary_Tag, $customerid, $secondarytagid);
									$gapt->Count = count($getallimage);
									$gapt->Images = $getallimage;
									array_push($payload,$gapt);

							}

							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Primary Tag fetched successfully.';
							$response['Payload'] = $payload;
							$this->response($response);

					}

			}
  		else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function gallerydetail_post()
  {
  			$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					$primary_tag = $this->post('Primary_Tag');
					$page = $this->post('Page');
					$limit = 20;
					
					if($this->post('Primary_Tag') == '' || $this->post('Page') == '' || $this->post('Page') == '0')
					{
						$response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);

					}
					else
					{

						  if($page == '1')
						   {
						   		$page = 0;

						   }
						   else
						   {
						   		$page = ($page-1) * $limit;

						   }
						 $getsearchTagviewDetails = $this->Customer_Api_Model->get_user_gelleryprimarytag_detail($customerid,$primary_tag);

						 foreach($getsearchTagviewDetails as $key => $gapt)
						 {
						 	$gapt->Prime_Name = $this->db->get_where('tbl_primary_tag', array('Id' => $primary_tag))->row()->Prime_Name;
						 	$gapt->Month = date('F',strtotime($gapt->Date));
						 	$gapt->Images = $this->Customer_Api_Model->get_user_gelleryprimarytag_detail_image($customerid,$primary_tag,$gapt->Date,$page,$limit);
						 	$Flag_Count = count($gapt->Images);
						 	if($Flag_Count == 20)
						 	{
						 		$Flag = 1;

						 	}
						 	else
						 	{
						 		$Flag = 0;

						 	}
						 	array_push($payload,$gapt);
						 }

						$response['ResponseCode'] = "200";
						$response['ResponseMessage'] = 'Fetched successfully.';
						$response['Flag'] = $Flag;
						$response['Payload'] = $payload;
						$this->response($response);

					}
			}
		 	else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function homedatatagtypedetail_post()
  {
  			$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
				$tagtype = strtolower($this->post('Tag_Type'));
				

				if($this->post('Tag_Type') == '')
					{
						$response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);

					}
					else
					{
						 $getsearchTagviewDetails = $this->Customer_Api_Model->get_user_homedatatagtype_detail($customerid,$tagtype);

						 foreach($getsearchTagviewDetails as $key => $gapt)
						 {

						 	$gapt->Month = date('F',strtotime($gapt->Date));
						 	$gapt->Images = $this->Customer_Api_Model->get_user_homedatatagtype_detail_image($customerid,$tagtype,$gapt->Date);

						 	array_push($payload,$gapt);
						 }

						$response['ResponseCode'] = "200";
						$response['ResponseMessage'] = 'Fetched successfully.';
						$response['Payload'] = $payload;
						$this->response($response);

					}


			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }
/**** Filter API ***/

  public function filteralphabateprimarytag_post()
  {
  		  $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Customer_Api_Model->gallery_primary_tag_alpha($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Customer_Api_Model->all_image_data($gapt->Primary_Tag);

									$gapt->Images = $getallimage;

									array_push($allprimarytagimagedata,$gapt);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);

						
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function filteralphabatesecondarytag_post()
  {
  		    $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallsecondarytag = $this->Customer_Api_Model->gallery_secondary_tag_alpha($customerid);
							foreach($getallsecondarytag as $key => $gapt)
							{
									
									$gapt->Total = $this->Customer_Api_Model->secondary_tag_total($gapt->Secondary_Tag);

									$getallimage = $this->Customer_Api_Model->all_secondary_image_data($gapt->Secondary_Tag);

									$gapt->Images = $getallimage;

									array_push($allprimarytagimagedata,$gapt);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);

						
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function filteramountprimarytag_post()
  {
  		    $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Customer_Api_Model->gallery_primary_tag_amount($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Customer_Api_Model->all_image_data($gapt->Primary_Tag);

									$gapt->Images = $getallimage;

									array_push($allprimarytagimagedata,$gapt);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function filteramountsecondarytag_post()
  {
  		    $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$a = array();
			$b = array();
			$data = array();
			$tablesecondry = "tbl_secondary_tag";
			if($customerid)
			{
					
					      $getallsecondarytag = $this->Customer_Api_Model->gallery_secondary_tag_amount($customerid);
					      foreach($getallsecondarytag as $ss)
					      {
					      	$query = $this->db->query("select sum(Amount) as Total from tbl_tag where Customer_Id = $customerid AND FIND_IN_SET($ss->Id,Secondry_Tag)")->row();
					      	$a[] = $ss->Id;
					      	$b[] = $query->Total;
					      }
					       
					       $unsortedValue = array_combine($a, $b);
					       arsort($unsortedValue);                           
							foreach($unsortedValue as $key => $value)
							{
									$data['Secondary_Tag'] = $key;
									$data['Secondary_Name'] = $this->Customer_Api_Model->get_secondrytag($tablesecondry, $key);;
									$data['Total'] = $value;
									$getallimage = $this->Customer_Api_Model->all_secondary_image_data($key);
									$data['Images'] = $getallimage;
									array_push($allprimarytagimagedata,$data);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function filtercountprimarytag_post()
  {
  		  $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Customer_Api_Model->gallery_primary_tag_count($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Customer_Api_Model->all_image_data($gapt->Primary_Tag);

									$gapt->Images = $getallimage;

									array_push($allprimarytagimagedata,$gapt);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function filtercountsecondarytag_post()
  {
  		    $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$a = array();
			$b = array();
			$data = array();
			$tablesecondry = "tbl_secondary_tag";
			if($customerid)
			{
					
					      $getallsecondarytag = $this->Customer_Api_Model->gallery_secondary_tag_amount($customerid);
					      foreach($getallsecondarytag as $ss)
					      {
					      	$query = $this->db->query("select COUNT(Id) as Count from tbl_tag where Customer_Id = $customerid AND FIND_IN_SET($ss->Id,Secondry_Tag)")->row();
					      	$a[] = $ss->Id;
					      	$b[] = $query->Count;
					      }

					       $unsortedValue = array_combine($a, $b);
					       arsort($unsortedValue);

							foreach($unsortedValue as $key => $value)
							{
									$data['Secondary_Tag'] = $key;
									$data['Secondary_Name'] = $this->Customer_Api_Model->get_secondrytag($tablesecondry, $key);;
									$data['Total'] = $this->db->query("select SUM(Amount) as Total from tbl_tag where Customer_Id = $customerid AND FIND_IN_SET($key,Secondry_Tag)")->row()->Total;
									$data['Count'] = $value;
									$getallimage = $this->Customer_Api_Model->all_secondary_image_data($key);
									$data['Images'] = $getallimage;
									array_push($allprimarytagimagedata,$data);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function filterusesprimarytag_post()
  {
  		    $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Customer_Api_Model->gallery_primary_tag_uses($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Customer_Api_Model->all_image_data($gapt->Primary_Tag);

									$gapt->Images = $getallimage;

									array_push($allprimarytagimagedata,$gapt);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  public function filterusessecondarytag_post()
  {
  		    $customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$a = array();
			$b = array();
			$data = array();
			$tablesecondry = "tbl_secondary_tag";
			if($customerid)
			{
					
					      $getallsecondarytag = $this->Customer_Api_Model->gallery_secondary_tag_amount($customerid);
					      foreach($getallsecondarytag as $ss)
					      {
					      	$query = $this->db->query("select COUNT(Id) as Uses from tbl_tag where Customer_Id = $customerid AND FIND_IN_SET($ss->Id,Secondry_Tag)")->row();
					      	$a[] = $ss->Id;
					      	$b[] = $query->Uses;
					      }
					      					       
					       $unsortedValue = array_combine($a, $b);
					       arsort($unsortedValue);

							foreach($unsortedValue as $key => $value)
							{
									$data['Secondary_Tag'] = $key;
									$data['Secondary_Name'] = $this->Customer_Api_Model->get_secondrytag($tablesecondry, $key);;
									$data['Total'] = $this->db->query("select SUM(Amount) as Total from tbl_tag where Customer_Id = $customerid AND FIND_IN_SET($key,Secondry_Tag)")->row()->Total;
									$data['Uses'] = $value;
									$getallimage = $this->Customer_Api_Model->all_secondary_image_data($key);
									$data['Images'] = $getallimage;
									array_push($allprimarytagimagedata,$data);

							}
							
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Tag fetched successfully.';
							$response['Taglist'] = $allprimarytagimagedata;
							$this->response($response);
			}
			else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

  }

  /**** Filter API End ***/

  public function deletetag_post()
	 {
		 	$customerauthtoken = $this->post('Authtoken');
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			if($customerid)
			{

				 if (empty($this->post('Id')))
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{
                        $getTagDetails = $this->Customer_Api_Model->delete_tag($customerid, $this->post('Id'));

                        if($getTagDetails == TRUE) 
                        {                       
	                        $response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Delete Tag successfully.';
							$this->response($response);
						}
						else{

							$response['ResponseCode'] = "400";
							$response['ResponseMessage'] = 'Some tachnical problem!.';
							$this->response($response);
						}                        

					}
			
			}
		 	else
			{
				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

	 }

     
      
}
