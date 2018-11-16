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
		
		/* Api Hit History */

		$Table_Name = "tbl_api_report";
		$Api_Name = "Customer Login";
		$Api_Count = 1;
		$Api_Date = date("Y-m-d");		
		$Api_Date_Int = strtotime($Api_Date); 
		
		Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
		/* Api Hit History */


	if (fpv($this->post('Login_Type')) == 'm' && fpv($this->post('Login_Type')) != 'f' && fpv($this->post('Login_Type')) != 'g')
		{
		if (empty(fpv($this->post('Email_Id'))) || empty(fpv($this->post('Password'))))
			{
			$response_data['ResponseCode'] = "400";
			$response_data['ResponseMessage'] = 'Fields are required.';
			$this->response($response_data);
			}
		  else
			{
			$url = base_url();
			$device_token = fpv($this->post('Device_Token'));
			$device_type = fpv($this->post('Device_Type'));
			$tableName = "tbl_customer";
			$customer = $this->Customer_Api_Model->get_login_data($tableName, fpv($this->post('Email_Id')) , fpv($this->post('Password')));
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
		if (empty(fpv($this->post('Email_Id'))) || empty(fpv($this->post('Login_Type'))) || empty(fpv($this->post('Social_Id'))))
			{
			$response_data['ResponseCode'] = "400";
			$response_data['ResponseMessage'] = 'Fields are required.';
			$this->response($response_data);
			}
		  else
			{
			$url = base_url();
			$device_token = fpv($this->post('Device_Token'));
			$device_type = fpv($this->post('Device_Type'));
			$tableName = "tbl_customer";
			$socialcustomer = $this->Customer_Api_Model->check_social_login($tableName, fpv($this->post('Email_Id')) , fpv($this->post('Login_Type')) , fpv($this->post('Social_Id')));
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

		/* Api Hit History */

		$Table_Name = "tbl_api_report";
		$Api_Name = "Customer Signup";
		$Api_Count = 1;
		$Api_Date = date("Y-m-d");		
		$Api_Date_Int = strtotime($Api_Date); 
		
		Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
		/* Api Hit History */
		 
		 if(empty(fpv($this->post('Email_Id'))) ||empty(fpv($this->post('Password'))) || empty(fpv($this->post('Login_Type'))) || fpv($this->post('Email_Id')) == NULL || fpv($this->post('Password')) == NULL || fpv($this->post('Login_Type')) == NULL){

			    $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
		    }
		else
		   {
			$url = base_url();
			$device_token = fpv($this->post('Device_Token'));
			$device_type  = fpv($this->post('Device_Type'));
			$tableName = "tbl_customer";
			$addcustomer = $this->Customer_Api_Model->add_customer($tableName,fpv($this->post('Email_Id')),fpv($this->post('Password')),fpv($this->post('Login_Type')));
			$adddetail = array();
				if($addcustomer == "You have successfully registered."){
			   
		    		if($this->_send_verificationemai(fpv($this->post('Email_Id'))) == TRUE)
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

    		/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Customer Logout";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		if(empty(fpv($this->post('Customerid'))) || empty(fpv($this->post('Device_Token'))))
			{
	            $response_data['ResponseCode'] = "400";
				$response_data['ResponseMessage'] = 'Fields are required.';
				$this->response($response_data);
			}else{	
			
			$customerid = fpv($this->post('Customerid'));
			$Device_Token = fpv($this->post('Device_Token'));
			
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
		
		/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Customer Forget Password";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		$token = time();
        $sendEmail   = fpv($this->input->post('Email_Id'));

        if (empty(fpv($this->post('Email_Id'))))
			{                
                $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
			}
		elseif ($this->Customer_Api_Model->exists_email(fpv($this->input->post('Email_Id'))) == 0) {
				
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

	                $data['lostCode'] = $token;
	                $body = $this->load->view('mailers/admin-forget-mail', $data, true);
	                
	                $send = Send_Mail($to, $subject, $body);

                if ($send) {                    
                    $this->Customer_Api_Model->update_user_token(fpv($this->input->post('Email_Id')), $token);
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
	 		/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Customer Primary Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

        $customerauthtoken = fpv($this->post('Authtoken'));

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
	 		/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Customer Secondary Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		$customerauthtoken = fpv($this->post('Authtoken'));		
		
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
		
         /* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Add Manual Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		$customerauthtoken = $this->post('Authtoken');		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		       if ( empty(fpv($this->post('Tag_Send_Date'))))
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
				$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,fpv($this->post('Prime_Name')));
				$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Tag'));
				$amount = fpv($this->post('Amount'));
				$description = fpv($this->post('Description'));
				$tagtype = fpv($this->post('Tag_Type'));
				$tagsenddate = fpv($this->post('Tag_Send_Date'));
				if(fpv($this->post('Tag_Status')) == 6)
				{
					$tagstatus = 2;
				}
				else
				{
					$tagstatus = fpv($this->post('Tag_Status'));
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
		
	 	 /* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Add Image Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		$customerauthtoken = fpv($this->post('Authtoken'));		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		 	 if (empty(fpv($this->post('Tag_Send_Date'))))
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
				$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,fpv($this->post('Prime_Name')));
				

				$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Tag'));
				
				$amount = fpv($this->post('Amount'));
				$imageurl = fpv($this->post('Image_Url'));
				$imageurlthumb = fpv($this->post('Image_Url_Thumb'));
				$tagtype = fpv($this->post('Tag_Type'));
				$description = fpv($this->post('Description'));
				$tagsenddate = fpv($this->post('Tag_Send_Date'));
				if(fpv($this->post('Tag_Status')) == 6)
				{
					$tagstatus = 2;
				}
				else
				{
					$tagstatus = fpv($this->post('Tag_Status'));
				}

				if(fpv($this->post('Image_Url')) == '' && fpv($this->post('Image_Url_Thumb')) == '')
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
	 	   /* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Gallery";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

	 	$customerauthtoken = fpv($this->post('Authtoken'));
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

	 	/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Home Page";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

	 	$customerauthtoken = fpv($this->post('Authtoken'));
	 	$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		//echo $this->db->last_query(); die;		
		$payload = array();
		$gallery = array();
		$document = array();
		$bankplusarray = array();
		$bankminusarray = array();
		$cashplusarray = array();
		$cashminusarray = array();
		$otherarray = array();
		$datatag = array();
		$datatagtotal = '';
		if($customerid){


				$cashplus = "cash+";
				$cashminus = "cash-";
				$bankplus = "bank+";
				$bankminus = "bank-";
				$other = "other";

				
				
                /* Bank+ */
				$allbankplusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $bankplus);
				$allbankplusdatadoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_doc($customerid, $bankplus);

				$datatagbankplus = array_merge($allbankplusdata,$allbankplusdatadoc);


				$allbankplusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $bankplus);
				$allbankplusTotaldoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total_doc($customerid, $bankplus);

				$datatagtotalbankplus = ($allbankplusTotal + $allbankplusTotaldoc);


				$bankplusarray['Tag_Type'] = $bankplus;	
				$bankplusarray['Total'] = $datatagtotalbankplus;
				$bankplusarray['Images'] = $datatagbankplus;
				$payload['Bankplusdata'] = $bankplusarray;

				/* Bank+ */

				
				/* Bank- */
				$allbankminusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $bankminus);
				$allbankminusdatadoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_doc($customerid, $bankminus);
				$datatagbankminus = array_merge($allbankminusdata,$allbankminusdatadoc);

				$allbankminusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $bankminus);
				$allbankminusTotaldoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total_doc($customerid, $bankminus);
				
				$datatagtotalbankminus = ($allbankminusTotal + $allbankminusTotaldoc);


				$bankminusarray['Tag_Type'] = $bankminus;	
				$bankminusarray['Total'] = $datatagtotalbankminus;
				$bankminusarray['Images'] = $datatagbankminus;
				$payload['Bankminusdata'] = $bankminusarray;

				/* Bank- */
				

				/* Cash+ */
				$allcashplusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $cashplus);

				$allcashplusdatadoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_doc($customerid, $cashplus);

				$datatagcashplus = array_merge($allcashplusdata,$allcashplusdatadoc);



				$allcashplusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $cashplus);
				$allcashplusTotaldoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total_doc($customerid, $cashplus);

				$datatagtotalcashplus = ($allcashplusTotal + $allcashplusTotaldoc);

				$cashplusarray['Tag_Type'] = $cashplus;	
				$cashplusarray['Total'] = $datatagtotalcashplus;
				$cashplusarray['Images'] = $datatagcashplus;
				$payload['Cashplusdata'] = $cashplusarray;

				/* Cash+ */
				

				/* Cash- */
				$allcashminusdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $cashminus);

				$allcashminusdatadoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_doc($customerid, $cashminus);

				$datatagcashminus = array_merge($allcashminusdata,$allcashminusdatadoc);


				$allcashminusTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $cashminus);

				$allcashminusTotaldoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total_doc($customerid, $cashminus);

				$datatagtotalcashminus = ($allcashminusTotal + $allcashminusTotaldoc);
				
				$cashminusarray['Tag_Type'] = $cashminus;	
				$cashminusarray['Total'] = $datatagtotalcashminus;
				$cashminusarray['Images'] = $datatagcashminus;
				$payload['Cashminusdata'] = $cashminusarray;
				/* Cash- */
				

				/* Other */
				$allotherdata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid, $other);
				$allotherdatadoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_doc($customerid, $other);

				$datatagotherdata = array_merge($allotherdata,$allotherdatadoc);


				$allotherdataTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid, $other);

				$allotherdataTotaldoc = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total_doc($customerid, $other);

				$datatagtotalother = ($allotherdataTotal + $allotherdataTotaldoc);

				$otherarray['Tag_Type'] = $other;	
				$otherarray['Total'] = $datatagtotalother;
				$otherarray['Images'] = $datatagotherdata;
				$payload['Otherdata'] = $otherarray;
				/* Other */ 


				$allgallerydata = $this->Customer_Api_Model->get_gallery_data_by_primarytag($customerid);
				$allgalleryTotal = $this->Customer_Api_Model->get_gallery_data_by_primarytag_total($customerid);				
				
				$gallery['Tag_Type'] = "gallery";	
				$gallery['Total'] = $allgalleryTotal;									
				$gallery['Images'] = $allgallerydata;
				$payload['Gallerydata'] = $gallery;


				$alldocumentdata = $this->Customer_Api_Model->get_document_data_by_primarytag($customerid);
				$alldocumentTotal = $this->Customer_Api_Model->get_document_data_by_primarytag_total($customerid);				
				
				$document['Tag_Type'] = "document";	
				$document['Total'] = $alldocumentTotal;									
				$document['Images'] = $alldocumentdata;
				$payload['Documentdata'] = $document;


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
		    error_reporting(0);
	 		/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Item Tag Details";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			if($customerid)
			{

				 if (empty(fpv($this->post('Id'))))
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{
                        $getTagDetails = $this->Customer_Api_Model->get_useritem_tag_details($customerid, fpv($this->post('Id')));

                        
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
		 	/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Item Tag Details Edit";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			if($customerid)
			{

				 if (fpv($this->post('Id')) == '' || fpv($this->post('Tag_Send_Date')) == '')
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{
                        
						$primary_Tag_Name = fpv($this->post('Prime_Name'));
						$amount = fpv($this->post('Amount'));
						$tagsenddate = fpv($this->post('Tag_Send_Date'));
						$Secondary_Tag = $this->post('Secondary_Tag');
						$description = fpv($this->post('Description'));
						$imageurl = fpv($this->post('Image_Url'));
						$imageurlthumb = fpv($this->post('Image_Url_Thumb'));
						$CGST = fpv($this->post('CGST'));
						$SGST = fpv($this->post('SGST'));
						$IGST = fpv($this->post('IGST'));
						$tabletag = "tbl_tag";				
						$tableprimary = "tbl_primary_tag";
						$tablesecondry = "tbl_secondary_tag";
						$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,$primary_Tag_Name);
						$checksecondrytag = $this->Customer_Api_Model->check_secondry_tag($tablesecondry,$customerid,$Secondary_Tag);

						if(fpv($this->post('Image_Url')) == '' && fpv($this->post('Image_Url_Thumb')) == '')
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

                        $getTagDetails = $this->Customer_Api_Model->get_useritem_edit_tag_details($tabletag, fpv($this->post('Id')), $data);                        
                        
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

  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Primary Tag Search List";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';

			if($customerid)
			{

				 if (empty(fpv($this->post('Primary_Tag'))))
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{

						$primarytagid = fpv($this->post('Primary_Tag'));
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

  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Primary And Secondary Tag Search Details";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					$primary_tag = fpv($this->post('Primary_Tag'));
					$secondary_tag = fpv($this->post('Secondary_Tag'));
					$page = fpv($this->post('Page'));
					$limit = 20;
					if(fpv($this->post('Primary_Tag')) == '' || fpv($this->post('Secondary_Tag')) == '' || fpv($this->post('Page')) == '' || fpv($this->post('Page')) == '0')
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
  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Secondary Tag Search List";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allsecondarytagimagedata = array();			
			if($customerid)
			{
				 if (empty(fpv($this->post('Secondary_Tag'))))
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{
						$secondarytagid = fpv($this->post('Secondary_Tag'));
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

  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Gallery Deatails";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					$primary_tag = fpv($this->post('Primary_Tag'));
					$page =fpv($this->post('Page'));
					$limit = 20;
					
					if(fpv($this->post('Primary_Tag')) == '' || fpv($this->post('Page')) == '' || fpv($this->post('Page') == '0'))
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

  			error_reporting(0);
  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Home Page Tag Type Deatails";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$tagtypedata = array();
			if($customerid)
			{
				$tagtype = strtolower($this->post('Tag_Type'));
				

				if(fpv($this->post('Tag_Type')) == '')
					{
						$response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);

					}
					else
					{	
						if($tagtype == 'gallery' || $tagtype == 'document')
						{
							 $getsearchTagviewDetails = $this->Customer_Api_Model->get_user_homedatatagtype_detail($customerid,$tagtype);

							 
							 foreach($getsearchTagviewDetails as $key => $gapt)
							 {

							 	$gapt->Month = date('F',strtotime($gapt->Date));


							 	$gallmages = $this->Customer_Api_Model->get_user_homedatatagtype_detail_image($customerid,$tagtype,$gapt->Date);
							 	
							 	$gapt->Images = $gallmages;
							 	
							 	array_push($payload,$gapt);
							 }
						}
						else
						{
						     
							$gdate = array();
							$ddate = array();
							$amerge = array();
							$aunique = array();
							$tableg = "tbl_tag";
							$tabled = "tbl_doc_tag";
							
						     $getsearchTagviewDetails = $this->Customer_Api_Model->get_user_homedatatagtype_detail($customerid,$tagtype);
						     

						     $getsearchTagviewDetailsdoc = $this->Customer_Api_Model->get_user_homedatatagtype_detail_doc($customerid,$tagtype);
						      
						     foreach($getsearchTagviewDetails as $key => $gapt)
							 {

							 	$gdate[] = $gapt->Month = date('m',strtotime($gapt->Date));
							 }
							 foreach($getsearchTagviewDetailsdoc as $key => $dapt)
							 {

							 	$ddate[] = $dapt->Month = date('m',strtotime($dapt->Date));
							 }

							 $amerge = array_merge($gdate,$ddate);
							 $aunique = array_unique($amerge);
							 natsort($aunique);

							 //print_r($aunique); die;


							 foreach($aunique as $key => $value)
							 {

							 		$gtotal = $this->Customer_Api_Model->get_user_homedatatagtype_total($customerid,$tagtype,$tableg,$value);
							 		$dtotal = $this->Customer_Api_Model->get_user_homedatatagtype_total($customerid,$tagtype,$tabled,$value);
							 		
							 		$tempdata = new stdClass();

							 		if(!empty($gtotal->Date) && !empty($dtotal->Date) )
							 		{

							 			$tempdata->Date = $gtotal->Date ;
							 			$tempdata->Month = date('F',strtotime($gtotal->Date));

							 		}
							 		else if(empty($gtotal->Date) && !empty($dtotal->Date))
							 		{
							 			$tempdata->Date =  $dtotal->Date;
							 			$tempdata->Month = date('F',strtotime($dtotal->Date));

							 		}
							 		else if(!empty($gtotal->Date) && empty($dtotal->Date))
							 		{
							 			$tempdata->Date =  $gtotal->Date;
							 			$tempdata->Month = date('F',strtotime($gtotal->Date));
							 		}
							 		else
							 		{
							 			$tempdata->Date = '';
							 			$tempdata->Month = '';

							 		}


							 		if(!empty($gtotal->Count) && !empty($dtotal->Count) )
							 		{

							 			$tempdata->Count =  ($gtotal->Count + $dtotal->Count);
							 		}
							 		else if(empty($gtotal->Count) && !empty($dtotal->Count))
							 		{
							 			$tempdata->Count =  $dtotal->Count;

							 		}
							 		else if(!empty($gtotal->Count) && empty($dtotal->Count))
							 		{
							 			$tempdata->Count =  $gtotal->Count;
							 		}
							 		else
							 		{
							 			$tempdata->Count = 0;

							 		}

							 		if(!empty($gtotal->Total) && !empty($dtotal->Total) )
							 		{

							 			$tempdata->Total =  ($gtotal->Total + $dtotal->Total);
							 		}
							 		else if(empty($gtotal->Total) && !empty($dtotal->Total))
							 		{
							 			$tempdata->Total =  $dtotal->Total;

							 		}
							 		else if(!empty($gtotal->Total) && empty($dtotal->Total))
							 		{
							 			$tempdata->Total = $gtotal->Total;
							 		}
							 		else
							 		{
							 			$tempdata->Total = 0;

							 		}

							 		//$tempdata->Count = (int) $tempdata->Count;
							 		//var_dump($tempdata); die;

							 		$gallImage = $this->Customer_Api_Model->get_user_homedatatagtype_total_image_gallery($customerid,$tagtype,$value);
							 		$docImage =  $this->Customer_Api_Model->get_user_homedatatagtype_total_image_document($customerid,$tagtype,$value);

							 		
							 		if(!empty($gallImage) && !empty($docImage) )
							 		{

							 			$tagtypedata = array_merge($gallImage,$docImage);
                                		$tempdata->Images = $tagtypedata;

							 		}
							 		else if(empty($gallImage) && !empty($docImage))
							 		{
							 			
							 			$tempdata->Images = $docImage;

							 		}
							 		else if(!empty($gallImage) && empty($docImage))
							 		{
							 			$tempdata->Images = $gallImage;
							 		}
							 		else
							 		{
							 			$tempdata->Images = array();

							 		}

							 	array_push($payload,$tempdata);	
							 		
							 }
								
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

  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Filter Primary Tag Alphabate";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		  $customerauthtoken = fpv($this->post('Authtoken'));
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
  		    
  			/* Api Hit History */

			$Table_Name = "tbl_api_report";

			$Api_Name = "Filter Secondary Tag Alphabate";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
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

  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			
			$Api_Name = "Filter Primary Tag Amount";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
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
  		    
  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			
			$Api_Name = "Filter Secondary Tag Amount";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */


  		    $customerauthtoken = fpv($this->post('Authtoken'));
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
  		  	
  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			
			$Api_Name = "Filter Primary Tag Count";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		  	$customerauthtoken = fpv($this->post('Authtoken'));
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
  		    
  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			
			$Api_Name = "Filter Secondary Tag Count";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
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
  		    /* Api Hit History */

			$Table_Name = "tbl_api_report";
			
			$Api_Name = "Filter Primary Tag Uses";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
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
  		    
  		    /* Api Hit History */

			$Table_Name = "tbl_api_report";			
			$Api_Name = "Filter Secondary Tag Uses";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
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

	 		/* Api Hit History */

			$Table_Name = "tbl_api_report";			
			$Api_Name = "Delete Single Image";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			if($customerid)
			{

				 if (empty(fpv($this->post('Id'))))
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


public function getUserProfile_post()
  {
  			/* Api Hit History */
			$Table_Name = "tbl_api_report";
			$Api_Name = "Get User Profile Details";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */
			error_reporting(0);
  			$customerauthtoken = $this->post('Authtoken');

  			
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);

			
			
			$payload = array();
			if($customerid)
			{
                        
                $getUserDetails = $this->Customer_Api_Model->get_user_profile_details($customerid);

                if( $getUserDetails == false)
                {

                	$response['ResponseCode'] = "400";
					$response['ResponseMessage'] = 'Some Technical Problem!';
					$this->response($response);
                }
                else         
                {
					$response->ResponseCode = "200";
					$response->ResponseMessage = 'Details Fetched successfully.';				
					$response->Payload = $getUserDetails;
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

  public function updateUserProfile_post()
  {
  			error_reporting(0);
  			/* Api Hit History */
			$Table_Name = "tbl_api_report";
			$Api_Name = "Update User Profile Details";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			if($customerid)
			{

					$data = array(
	 					"First_Name" => fpv($this->input->post('first_name')),
	 					"Last_Name" => fpv($this->input->post('last_name')),
	 					"Customer_Name" => fpv($this->input->post('first_name')).' '.fpv($this->input->post('last_name')),
	 					"Gender" => fpv($this->input->post('gender')),
	 					"Dob" => fpv($this->input->post('dob')),
	 					"Company_Name" => fpv($this->input->post('company_name')),
	 					"Pan_Number" => fpv($this->input->post('pan_number')),
	 					"Gstn" => fpv($this->input->post('gstn')),
	 					"City" => fpv($this->input->post('city')),
	 					"Aadhar_No" => fpv($this->input->post('aadhar_number')),
	 					"Phone_No" => fpv($this->input->post('phone')),
	 					"Alt_Phone_No" => fpv($this->input->post('alt_phone')),
	 					"Skype_Id" => fpv($this->input->post('skype_id')),
	 					"Profile_Image_Url" => fpv($this->input->post('profile_image_url'))
	 				);

                        
                $updateUserDetails = $this->Customer_Api_Model->update_user_profile_details($customerid,$data);

                if( $updateUserDetails == FALSE)
                {

                	$response['ResponseCode'] = "400";
					$response['ResponseMessage'] = 'Some Technical Problem!';
					$this->response($response);
                }
                else         
                {
					$response->ResponseCode = "200";
					$response->ResponseMessage = 'Details update successfully.';
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

  public function permission_post()
	{

		error_reporting(0);

		$type = fpv($this->post('type'));
		$value = fpv($this->post('value'));
		$customerauthtoken = fpv($this->post('Authtoken'));

		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);		
		
		$payload = array();
		if($customerid)
			{
					if( empty($type) || $value == '')
					{
						$response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else{

							$setpermission = $this->Customer_Api_Model->setpermission($customerid, $type, $value);

	                        if($setpermission == TRUE) 
	                        {                       
		                        $response['ResponseCode'] = "200";
								$response['ResponseMessage'] = 'Permission update successfully.';
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

	public function getpermission_post()
	{
		
		error_reporting(0);
		$customerauthtoken = fpv($this->post('Authtoken'));
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		$payload = array();

		if($customerid)
			{
				$this->db->select('permission_g as g, permission_d as d, permission_r as r, Paid_Status');
				$this->db->from('tbl_customer');
				$this->db->where('Id',$customerid);
				$query = $this->db->get(); 

				$getPermission = $query->row();
				
				array_push($payload,$getPermission);
				
				$response->ResponseCode = "200";
				$response->ResponseMessage = 'Permission fetched successfully.';				
				$response->Payload = $payload;
				$this->response($response);

			}
		else
			{

				$response['ResponseCode'] = "400";
				$response['ResponseMessage'] = 'Auth token mismatch';			
				$this->response($response);
			}

	}
	

	public function sendmessage_post()
	{

		error_reporting(0);

		
		$customerauthtoken = fpv($this->post('Authtoken'));
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);

		$name = fpv($this->post('Customer_Name'));
		$email = fpv($this->post('Email_Id'));
		$mobile = fpv($this->post('phone'));
		$message = fpv($this->post('Message'));		
		
		$payload = array();
		if($customerid)
			{
					if( $name == '' || $email == '' || $mobile == '' || $message == '')
					{
						$response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else{

							$tbl = "tbl_contact";

						     $insertdata = array(

						     					"Customer_Id" => $customerid,
						     					"Customer_Name" => $name,
						     					"Email_Id" => $email,
						     					"Phone_No" => $mobile,
						     					"Message" => $message,
						     					"senddate" => strtotime(date("Y-m-d H:i:s"))
						     	);
			               
			                $data['Customer_Name'] = $name;
			                $data['Email_Id'] = $email;
			                $data['Phone_No'] = $mobile;
			                $data['Message'] = $message;
			                
			                $to = "admin@zoddl.com";
			                $subject = 'Contact Us';			                
			                $body = $this->load->view('mailers/admin-contact-us-mail', $data, true);
			                
			                $send = Send_Mail($to, $subject, $body);

			                if ($send) {                    
			                    $this->Customer_Api_Model->contact_us($tbl, $insertdata);
			                    $response['ResponseCode'] = "200";
								$response['ResponseMessage'] = 'Mail send successfully.';			
								$this->response($response);  

			                } else {
			                    
			                    $response['ResponseCode'] = "400";
								$response['ResponseMessage'] = 'Sorry try again.';			
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

	public function getcontactdetails_post()
	{
		$customerauthtoken = fpv($this->post('Authtoken'));
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);

		$payload = array();

		//$customerid; die;
		if($customerid)
			{	

				error_reporting(0);			

				$getcontactdetails = $this->Customer_Api_Model->get_contact_details($customerid);
				$getzoddldetails = $this->Customer_Api_Model->get_zoddl_details();

				$data->Email_Id = $getzoddldetails->Email_Id;
				$data->phone = $getzoddldetails->phone;
				$data->Website = $getzoddldetails->Website;

				$data->contacts = $getcontactdetails;

				

				array_push($payload,$data);
				
				$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Contact fetched successfully.';				
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

	public function reportprimarytag_post()
	{
		$customerauthtoken = fpv($this->post('Authtoken'));

		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);

		if($customerid){
		
		$tableprimetag = "tbl_primary_tag";
		$tabledocprimetag = "tbl_doc_primary_tag";
		$getcustomerprimetag = $this->Customer_Api_Model->get_customer_prime_tag($tableprimetag, $customerid);
		$getdocprimetag = $this->Customer_Api_Model->get_customer_prime_tag($tabledocprimetag, $customerid);
		$primarytaglist = array();
		$docprimarytaglist = array();
		$data = array();
		
			foreach($getcustomerprimetag as $primetag)
				{
				   $primetags['Source_Type'] = "image gallery";
				   $primetags['Tag_Type'] = "primary tag";
				   $primetags['Id'] = $primetag->Id;
				   $primetags['Prime_Name'] = $primetag->Prime_Name;
				   $primetags['Primary_Tag'] = $primetag->Id;				   
				   $primarytaglist[] = $primetags;	

				}
			foreach($getdocprimetag as $docprimetag)
				{
				   $docprimetags['Source_Type'] = "document gallery";
				   $docprimetags['Tag_Type'] = "primary tag";
				   $docprimetags['Id'] = $docprimetag->Id;
				   $docprimetags['Prime_Name'] = $docprimetag->Prime_Name;
				   $docprimetags['Primary_Tag'] = $docprimetag->Id;				   
				   $docprimarytaglist[] = $docprimetags;	

				}

				$data = array_merge($primarytaglist,$docprimarytaglist);
			  	$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Primary Tag fetched successfully.';
				$response['Primary_Tag'] = $data;
				$this->response($response);
			}
		     
		
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}

	}

	 public function reportsecondarytag_post()
	 {
	 		

		$customerauthtoken = fpv($this->post('Authtoken'));		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		
		$tablesecondtag = "tbl_secondary_tag";
		$tabledocsecondtag = "tbl_doc_secondary_tag";
		$getcustomersecondtag = $this->Customer_Api_Model->get_customer_second_tag($tablesecondtag, $customerid);
		$getcustomerdocsecondtag = $this->Customer_Api_Model->get_customer_second_tag($tabledocsecondtag, $customerid);
		$secondtaglist = array();
		$docsecondtaglist = array();
		$data = array();
		
			foreach($getcustomersecondtag as $secondtag)
				{
				   $secondtags['Source_Type'] = "image gallery";
				   $secondtags['Tag_Type'] = "secondary tag";
				   $secondtags['Id'] = $secondtag->Id;
				   $secondtags['Secondary_Name'] = $secondtag->Secondary_Name;
				   $secondtags['Secondary_Tag'] = $secondtag->Id;
				   $secondtaglist[] = $secondtags;	

				}

			foreach($getcustomerdocsecondtag as $docsecondtag)
				{
				   $docsecondtags['Source_Type'] = "document gallery";
				   $docsecondtags['Tag_Type'] = "secondary tag";
				   $docsecondtags['Id'] = $docsecondtag->Id;
				   $docsecondtags['Secondary_Name'] = $docsecondtag->Secondary_Name;
				   $docsecondtags['Secondary_Tag'] = $docsecondtag->Id;
				   $docsecondtaglist[] = $docsecondtags;	

				}

				$data = array_merge($secondtaglist,$docsecondtaglist);
			  	$response['ResponseCode'] = "200";
				$response['ResponseMessage'] = 'Secondary Tag fetched successfully.';
				$response['Secondary_Tag'] = $data;
				$this->response($response);
			}
		     
		
		else
		{
			$response['ResponseCode'] = "400";
			$response['ResponseMessage'] = 'Auth token mismatch';			
			$this->response($response);
		}
              
              
	}

     
      
}
