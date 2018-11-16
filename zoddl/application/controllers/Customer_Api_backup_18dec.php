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
			   
		    		//$this->Customer_Api_Model->update_device_entry($device_type,$addcustomer->Id,$device_token);
				$response['ResponseCode'] = "200";
				$response['ResponseMessage'] ="You have registered successfully. Please login to continue.";						
				$adddetail['BaseUrl'] = $url;			
				$response['AddDetail'] = $adddetail;	      
				$this->response($response);
		    
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
		$delete_token = $this->Customer_Api_Model->delete_data($table,$condition);
		if($delete_token){
			$response['ResponseCode'] = "200";
			$response['ResponseMessage'] = 'Logout successfully.';
			$this->response($response);
		}else{
			$response['ResponseCode'] = "0";
			$response['ResponseMessage'] = 'Oops something went wrong.';
			$this->response($response);
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
				$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Name'));
				$amount = $this->post('Amount');
				$description = $this->post('Description');
				$tagtype = $this->post('Tag_Type');
				$tagsenddate = $this->post('Tag_Send_Date');
				$tagstatus = $this->post('Tag_Status');
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
				

				$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Name'));
				
				$amount = $this->post('Amount');
				$imageurl = $this->post('Image_Url');
				$tagtype = $this->post('Tag_Type');
				$tagsenddate = $this->post('Tag_Send_Date');
				$tagstatus = $this->post('Tag_Status');
				$mysqldate = date('Y-m-d', strtotime($tagsenddate));
				if($this->post('Image_Url') == '')
				{
					$isUploaded = 0;	
				}
				else
				{
					$isUploaded = 1;	

				}
				
				$data = array(
						'Customer_Id'=>$customerid,
						'Primary_Tag'=>$checkprimarytag,
						'Secondry_Tag'=>$checksecondrytag,
						'Amount'=>$amount,
						'Image_Url'=>$imageurl,
						'Tag_Type'=>$tagtype,
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
				   
				   $tags['Id'] = $tag->Id;
				   $tags['Customer_Id'] = $tag->Customer_Id;
				   $tags['Primary_Tag'] = $primaryTag;
				   $tags['Secondry_Tag'] = $secondryTag;
				   $tags['Amount'] = $tag->Amount;
				   $tags['Image_Url'] = $tag->Image_Url;
				   $tags['Description'] = $tag->Description;
				   $tags['Tag_Type'] = $tag->Tag_Type;
				   $tags['Sub_Description'] = $tag->Sub_Description;
				   $tags['CGST'] = $tag->CGST;
				   $tags['SGST'] = $tag->SGST;
				   $tags['IGST'] = $tag->IGST;
				   $tags['Invoice_Check_No'] = $tag->Invoice_Check_No;
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

	function forget_post()
	{
		$lostpw_code = $this->Customer_Api_Model->create_lostpw_code();
        $sendEmail   = $this->input->post('email');

        if (empty($this->post('email')))
			{
                
                $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
			}
		elseif ($this->Customer_Api_Model->exists_email($this->input->post('email')) == 0) {
				
				$response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Please enter valid register email.';
			    $this->response($response_data);           
        	}
		else
			{
				if ($lostpw_code) {

                $to = $sendEmail;
                $subject = 'Forget Password';
                $data['lostCode'] = $lostpw_code;
                $body = $this->load->view('mailers/admin-forget-mail', $data, true);
                
                $send = Send_Mail($to, $subject, $body);

                if ($send) {
                    
                    $this->Customer_Api_Model->update_user_password($this->input->post('email'), $lostpw_code);

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
						$tabletag = "tbl_tag";				
						$tableprimary = "tbl_primary_tag";
						$tablesecondry = "tbl_secondary_tag";
						$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,$primary_Tag_Name);
						$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag_new($tablesecondry,$customerid,$Secondary_Tag);

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
     
      
}
