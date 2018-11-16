<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Document_Api Controller 
 */

require APPPATH . '/libraries/REST_Controller.php';

class Document_Api extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Document_Api_Model');

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
			$Api_Name = "Document Gallery Primary Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date);
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
		/* Api Hit History */

        $customerauthtoken = fpv($this->post('Authtoken'));

		$tablecustomer = "tbl_customer";
		$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);

		if($customerid){
		
		$tableprimetag = "tbl_doc_primary_tag";
		$getcustomerprimetag = $this->Document_Api_Model->get_customer_prime_tag($tableprimetag, $customerid);
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
			$Api_Name = "Document Gallery Secondary Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
		/* Api Hit History */

		$customerauthtoken = fpv($this->post('Authtoken'));		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		
		$tablesecondtag = "tbl_doc_secondary_tag";
		$getcustomersecondtag = $this->Document_Api_Model->get_customer_second_tag($tablesecondtag, $customerid);
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
			$Api_Name = "Document Gallery Add Manual Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
		/* Api Hit History */ 


		$customerauthtoken = fpv($this->post('Authtoken'));		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		       if ( empty(fpv($this->post('Tag_Send_Date'))))
			{
                $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
			}
			else
			{
				$tabletag = "tbl_doc_tag";				
				$tableprimary = "tbl_doc_primary_tag";
				$tablesecondry = "tbl_doc_secondary_tag";
				$checkprimarytag = $this->Document_Api_Model->check_primary_tag($tableprimary,$customerid,fpv($this->post('Prime_Name')));
				$checksecondrytag = $this->Document_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Tag'));
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

			   	$insert_tag = $this->Document_Api_Model->insert_tag($tabletag,$data);
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
			$Api_Name = "Document Gallery Add Image Tag";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
		/* Api Hit History */

		$customerauthtoken = fpv($this->post('Authtoken'));		
		
		$tablecustomer = "tbl_customer";
		$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){
		 	 if (empty(fpv($this->post('Tag_Send_Date'))))
			{
                $response_data['ResponseCode'] = "400";
			    $response_data['ResponseMessage'] = 'Fields are required.';
			    $this->response($response_data);
			}
			else
			{
				$tabletag = "tbl_doc_tag";				
				$tableprimary = "tbl_doc_primary_tag";
				$tablesecondry = "tbl_doc_secondary_tag";
				$checkprimarytag = $this->Document_Api_Model->check_primary_tag($tableprimary,$customerid,fpv($this->post('Prime_Name')));
				

				$checksecondrytag = $this->Document_Api_Model->check_secondry_tag($tablesecondry,$customerid,$this->post('Secondary_Tag'));
				
				$amount = fpv($this->post('Amount'));
				$imageurl = fpv($this->post('Image_Url'));
				$imageurlthumb = fpv($this->post('Image_Url_Thumb'));
				$docurl = fpv($this->post('Doc_Url'));
				$tagtype = fpv($this->post('Tag_Type'));
				$description = fpv($this->post('Description'));
				$tagsenddate = fpv($this->post('Tag_Send_Date'));
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
						'Tag_Send_Date'=>$tagsenddate,
						'Description'=>$description,
						'Tag_Status'=>$tagstatus,
						'Tag_Date'=>$mysqldate,
						'Doc_Url' =>$docurl,
						'isUploaded'=>$isUploaded,
						'Tag_Date_int'=>strtotime($tagsenddate)
					    );

			   	$insert_tag = $this->Document_Api_Model->insert_tag($tabletag,$data);
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
		$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		if($customerid){

			$tabletag = "tbl_doc_tag";
			$getcustomertag = $this->Document_Api_Model->get_customer_tag($tabletag, $customerid);
			$taglist = array();
			
			$tableprimary = "tbl_doc_primary_tag";
			$tablesecondry = "tbl_doc_secondary_tag";

			foreach($getcustomertag as $tag)
				{
                     
				   $primaryTag = $this->Document_Api_Model->get_primarytag($tableprimary, $tag->Primary_Tag);
				   $secondryTag = $this->Document_Api_Model->get_secondrytag($tablesecondry, $tag->Secondry_Tag);

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
				   $tags['Secondry_Tag'] = $secondryTag;
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
		 error_reporting(0);

		 /* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Document Gallery";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
		/* Api Hit History */


	 	$customerauthtoken = fpv($this->post('Authtoken'));
	 	$tablecustomer = "tbl_customer";
		$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);		
		$allprimarytagimagedata = array();
		$testedarray = array();
		if($customerid){
				$getallprimarytag = $this->Document_Api_Model->gallery_primary_tag($customerid);
				
				
				foreach($getallprimarytag as $key => $gapt)
				{
						
						$getallimage = $this->Document_Api_Model->all_image_data($gapt->Primary_Tag);
						
						$gapt->Timestamp = $this->Document_Api_Model->get_lasttimestamp_data($gapt->Primary_Tag);
						
						$gapt->Count = $this->Document_Api_Model->all_image_data_count($gapt->Primary_Tag); 

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
	 	$customerauthtoken = fpv($this->post('Authtoken'));
	 	$tablecustomer = "tbl_customer";
		$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);		
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

				$allgallerydata = $this->Document_Api_Model->get_gallery_data_by_primarytag($customerid);
				$allgalleryTotal = $this->Document_Api_Model->get_gallery_data_by_primarytag_total($customerid);				
				
				$gallery['Tag_Type'] = "gallery";	
				$gallery['Total'] = $allgalleryTotal;									
				$gallery['Images'] = $allgallerydata;
				$payload['Gallerydata'] = $gallery;
				
                
				$allbankplusdata = $this->Document_Api_Model->get_gallery_data_by_primarytag($customerid, $bankplus);				
				$allbankplusTotal = $this->Document_Api_Model->get_gallery_data_by_primarytag_total($customerid, $bankplus);

				$bankplusarray['Tag_Type'] = $bankplus;	
				$bankplusarray['Total'] = $allbankplusTotal;
				$bankplusarray['Images'] = $allbankplusdata;
				$payload['Bankplusdata'] = $bankplusarray;
				

				$allbankminusdata = $this->Document_Api_Model->get_gallery_data_by_primarytag($customerid, $bankminus);
				$allbankminusTotal = $this->Document_Api_Model->get_gallery_data_by_primarytag_total($customerid, $bankminus);
				
				$bankminusarray['Tag_Type'] = $bankminus;	
				$bankminusarray['Total'] = $allbankminusTotal;
				$bankminusarray['Images'] = $allbankminusdata;
				$payload['Bankminusdata'] = $bankminusarray;
				

				$allcashplusdata = $this->Document_Api_Model->get_gallery_data_by_primarytag($customerid, $cashplus);
				$allcashplusTotal = $this->Document_Api_Model->get_gallery_data_by_primarytag_total($customerid, $cashplus);

				$cashplusarray['Tag_Type'] = $cashplus;	
				$cashplusarray['Total'] = $allcashplusTotal;
				$cashplusarray['Images'] = $allcashplusdata;
				$payload['Cashplusdata'] = $cashplusarray;
				

				
				$allcashminusdata = $this->Document_Api_Model->get_gallery_data_by_primarytag($customerid, $cashminus);
				$allcashminusTotal = $this->Document_Api_Model->get_gallery_data_by_primarytag_total($customerid, $cashminus);
				
				$cashminusarray['Tag_Type'] = $cashminus;	
				$cashminusarray['Total'] = $allcashminusTotal;
				$cashminusarray['Images'] = $allcashminusdata;
				$payload['Cashminusdata'] = $cashminusarray;
				

				$allotherdata = $this->Document_Api_Model->get_gallery_data_by_primarytag($customerid, $other);
				$allotherdataTotal = $this->Document_Api_Model->get_gallery_data_by_primarytag_total($customerid, $other);

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
			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Document Gallery Item Tag Details";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */ 	

		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
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
                        $getTagDetails = $this->Document_Api_Model->get_useritem_tag_details($customerid, $this->post('Id'));

                        
                        
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
			$Api_Name = "User Document Gallery Item Tag Details Edit";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
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
						$description = $this->post('Description');
						$imageurl = fpv($this->post('Image_Url'));
						$imageurlthumb = fpv($this->post('Image_Url_Thumb'));
						$CGST = fpv($this->post('CGST'));
						$SGST = fpv($this->post('SGST'));
						$IGST = fpv($this->post('IGST'));
						$docurl = fpv($this->post('Doc_Url'));
						$tabletag = "tbl_doc_tag";				
						$tableprimary = "tbl_doc_primary_tag";
						$tablesecondry = "tbl_doc_secondary_tag";
						$checkprimarytag = $this->Document_Api_Model->check_primary_tag($tableprimary,$customerid,$primary_Tag_Name);
						$checksecondrytag = $this->Document_Api_Model->check_secondry_tag($tablesecondry,$customerid,$Secondary_Tag);

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
						'Doc_Url'=>$docurl,	
						'Description'=>$description,						
						'Tag_Send_Date'=>$tagsenddate,
						'Tag_Date'=>$mysqldate,							
						'isUploaded'=>$isUploaded,
						'Tag_Date_int'=>strtotime($tagsenddate)
					    );

                        $getTagDetails = $this->Document_Api_Model->get_useritem_edit_tag_details($tabletag, $this->post('Id'), $data);                        
                        
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
			$Api_Name = "User Document Gallery Primary Tag Search List";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */
		 	
		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
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
						 $get_primary_secondary_tag = $this->db->query("select Secondry_Tag from tbl_doc_tag where Customer_Id = $customerid AND Primary_Tag = $primarytagid")->result();
               				
			               foreach($get_primary_secondary_tag as $st)
			               {
			                   $allst .= $st->Secondry_Tag.","; 
			               }
          
				          $filterValue = trim($allst,',');
				          $arraySecondaryValue = explode(',',$filterValue);
				          $uniqeValue = array_unique($arraySecondaryValue);
                          $getsearchTagDetails = $this->Document_Api_Model->get_user_primarytag_list($customerid,$uniqeValue);
                          $tableprimary = "tbl_doc_primary_tag";
                          foreach($getsearchTagDetails as $key => $gapt)
							{
									
									$getallimage = $this->Document_Api_Model->all_secondarytag_image_data($gapt->Secondary_Tag, $customerid, $primarytagid);

									$gapt->Primary_Tag = $primarytagid;
									$gapt->Prime_Name = $this->Document_Api_Model->get_primarytag($tableprimary, $primarytagid);	
									$gapt->Total = $allTotal = $this->Document_Api_Model->get_all_secondarytag_total($gapt->Secondary_Tag, $customerid, $primarytagid);
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
	        error_reporting(0);

	        /* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Document Gallery Primary And Secondary Tag Search Details";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */


  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$Flag = '';
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

						 $getsearchTagviewDetails = $this->Document_Api_Model->get_user_primarysecondarytag_detail($customerid,$primary_tag,$secondary_tag);

						 foreach($getsearchTagviewDetails as $key => $gapt)
						 {
						 	$gapt->Prime_Name = $this->db->get_where('tbl_doc_primary_tag', array('Id' => $primary_tag))->row()->Prime_Name;
						 	$gapt->Month = date('F',strtotime($gapt->Date));
						 	$gapt->Images = $this->Document_Api_Model->get_user_primarysecondarytag_detail_image($customerid,$primary_tag,$secondary_tag,$gapt->Date,$page,$limit);
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
			$Api_Name = "User Document Gallery Secondary Tag Search List";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
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
						$getsearchTagDetails = $this->Document_Api_Model->get_user_secondarytag_list($customerid,$secondarytagid);
						$tablesecondry = "tbl_doc_secondary_tag";

						 foreach($getsearchTagDetails as $key => $gapt)
							{
									$gapt->Secondary_Tag = $secondarytagid;
									$gapt->Secondary_Name = $this->Document_Api_Model->get_secondrytag($tablesecondry, $secondarytagid);	
									$getallimage = $this->Document_Api_Model->all_primarytag_image_data($gapt->Primary_Tag, $customerid, $secondarytagid);
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
  			error_reporting(0);

  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Document Gallery Deatails";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$Flag = '';
			if($customerid)
			{
					$primary_tag = fpv($this->post('Primary_Tag'));
					$page = fpv($this->post('Page'));
					$limit = 20;
					
					if(fpv($this->post('Primary_Tag')) == '' || fpv($this->post('Page')) == '' || fpv($this->post('Page')) == '0')
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

						 $getsearchTagviewDetails = $this->Document_Api_Model->get_user_gelleryprimarytag_detail($customerid,$primary_tag);

						 foreach($getsearchTagviewDetails as $key => $gapt)
						 {
						 	$gapt->Primary_Tag = $this->post('Primary_Tag');
						 	$gapt->Prime_Name = $this->db->get_where('tbl_doc_primary_tag', array('Id' => $primary_tag))->row()->Prime_Name;
						 	$gapt->Month = date('F',strtotime($gapt->Date));
						 	$gapt->Images = $this->Document_Api_Model->get_user_gelleryprimarytag_detail_image($customerid,$primary_tag,$gapt->Date,$page,$limit);
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
  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
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
						 $getsearchTagviewDetails = $this->Document_Api_Model->get_user_homedatatagtype_detail($customerid,$tagtype);

						 foreach($getsearchTagviewDetails as $key => $gapt)
						 {

						 	$gapt->Month = date('F',strtotime($gapt->Date));
						 	$gapt->Images = $this->Document_Api_Model->get_user_homedatatagtype_detail_image($customerid,$tagtype,$gapt->Date);

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
  		    
  		    /* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "Filter Document Gallery Primary Tag Alphabate";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Document_Api_Model->gallery_primary_tag_alpha($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Document_Api_Model->all_image_data($gapt->Primary_Tag);

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
			$Api_Name = "Filter Document Gallery Secondary Tag Alphabate";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallsecondarytag = $this->Document_Api_Model->gallery_secondary_tag_alpha($customerid);
							foreach($getallsecondarytag as $key => $gapt)
							{
									
									$gapt->Total = $this->Document_Api_Model->secondary_tag_total($gapt->Secondary_Tag);

									$getallimage = $this->Document_Api_Model->all_secondary_image_data($gapt->Secondary_Tag);

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
			
			$Api_Name = "Filter Document Gallery Primary Tag Amount";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */


  		    $customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Document_Api_Model->gallery_primary_tag_amount($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Document_Api_Model->all_image_data($gapt->Primary_Tag);

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
			
			$Api_Name = "Filter Document Gallery Secondary Tag Amount";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */	    

  		    $customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$a = array();
			$b = array();
			$data = array();
			$tablesecondry = "tbl_doc_secondary_tag";
			if($customerid)
			{
					
					      $getallsecondarytag = $this->Document_Api_Model->gallery_secondary_tag_amount($customerid);
					      foreach($getallsecondarytag as $ss)
					      {
					      	$query = $this->db->query("select sum(Amount) as Total from tbl_doc_tag where Customer_Id = $customerid AND FIND_IN_SET($ss->Id,Secondry_Tag)")->row();
					      	$a[] = $ss->Id;
					      	$b[] = $query->Total;
					      }
					       
					       $unsortedValue = array_combine($a, $b);
					       arsort($unsortedValue);                           
							foreach($unsortedValue as $key => $value)
							{
									$data['Secondary_Tag'] = $key;
									$data['Secondary_Name'] = $this->Document_Api_Model->get_secondrytag($tablesecondry, $key);;
									$data['Total'] = $value;
									$getallimage = $this->Document_Api_Model->all_secondary_image_data($key);
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
			
			$Api_Name = "Filter Document Gallery Primary Tag Count";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		  	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Document_Api_Model->gallery_primary_tag_count($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Document_Api_Model->all_image_data($gapt->Primary_Tag);

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
			
			$Api_Name = "Filter Document Gallery Secondary Tag Count";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */	    

  		    $customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$a = array();
			$b = array();
			$data = array();
			$tablesecondry = "tbl_doc_secondary_tag";
			if($customerid)
			{
					
					      $getallsecondarytag = $this->Document_Api_Model->gallery_secondary_tag_amount($customerid);
					      foreach($getallsecondarytag as $ss)
					      {
					      	$query = $this->db->query("select COUNT(Id) as Count from tbl_doc_tag where Customer_Id = $customerid AND FIND_IN_SET($ss->Id,Secondry_Tag)")->row();
					      	$a[] = $ss->Id;
					      	$b[] = $query->Count;
					      }

					       $unsortedValue = array_combine($a, $b);
					       arsort($unsortedValue);

							foreach($unsortedValue as $key => $value)
							{
									$data['Secondary_Tag'] = $key;
									$data['Secondary_Name'] = $this->Document_Api_Model->get_secondrytag($tablesecondry, $key);;
									$data['Total'] = $this->db->query("select SUM(Amount) as Total from tbl_doc_tag where Customer_Id = $customerid AND FIND_IN_SET($key,Secondry_Tag)")->row()->Total;
									$data['Count'] = $value;
									$getallimage = $this->Document_Api_Model->all_secondary_image_data($key);
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
			
			$Api_Name = "Filter Document Gallery Primary Tag Uses";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  		    $customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			if($customerid)
			{
					
					        $getallprimarytag = $this->Document_Api_Model->gallery_primary_tag_uses($customerid);				
							
							foreach($getallprimarytag as $key => $gapt)
							{
									
									$getallimage = $this->Document_Api_Model->all_image_data($gapt->Primary_Tag);

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
			$Api_Name = "Filter Document Gallery Secondary Tag Uses";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */	    


  		    $customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$a = array();
			$b = array();
			$data = array();
			$tablesecondry = "tbl_doc_secondary_tag";
			if($customerid)
			{
					
					      $getallsecondarytag = $this->Document_Api_Model->gallery_secondary_tag_amount($customerid);
					      foreach($getallsecondarytag as $ss)
					      {
					      	$query = $this->db->query("select COUNT(Id) as Uses from tbl_doc_tag where Customer_Id = $customerid AND FIND_IN_SET($ss->Id,Secondry_Tag)")->row();
					      	$a[] = $ss->Id;
					      	$b[] = $query->Uses;
					      }
					      					       
					       $unsortedValue = array_combine($a, $b);
					       arsort($unsortedValue);

							foreach($unsortedValue as $key => $value)
							{
									$data['Secondary_Tag'] = $key;
									$data['Secondary_Name'] = $this->Document_Api_Model->get_secondrytag($tablesecondry, $key);;
									$data['Total'] = $this->db->query("select SUM(Amount) as Total from tbl_doc_tag where Customer_Id = $customerid AND FIND_IN_SET($key,Secondry_Tag)")->row()->Total;
									$data['Uses'] = $value;
									$getallimage = $this->Document_Api_Model->all_secondary_image_data($key);
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
			$Api_Name = "Delete Document Gallery Single Document";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */
		 	
		 	$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
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
                        $getTagDetails = $this->Document_Api_Model->delete_tag($customerid, $this->post('Id'));

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


	public function gallerydetailbymonth_post()
  	{

  			

  			/* Api Hit History */

			$Table_Name = "tbl_api_report";
			$Api_Name = "User Document Gallery Deatails By Month";
			$Api_Count = 1;
			$Api_Date = date("Y-m-d");		
			$Api_Date_Int = strtotime($Api_Date); 
			
			Api_Hit($Table_Name, $Api_Name, $Api_Count, $Api_Date, $Api_Date_Int);
		
			/* Api Hit History */

  			$customerauthtoken = fpv($this->post('Authtoken'));
		 	$tablecustomer = "tbl_customer";
			$customerid = $this->Document_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$payload = array();
			$allprimarytagimagedata = array();
			$allst = '';
			$Flag = 0;
			if($customerid)
			{
					$primary_tag = fpv($this->post('Primary_Tag'));
					$month = fpv($this->post('Month'));

					$getmonthNo = date("m",strtotime($month));

					$page =fpv($this->post('Page'));
					$limit = 20;
					
					if(fpv($this->post('Primary_Tag')) == '' || fpv($this->post('Page')) == '' || fpv($this->post('Page') == '0') || $month == '')
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
						 
						 $getsearchTagviewDetails = $this->Document_Api_Model->get_user_gelleryprimarytag_detail_by_month($customerid,$primary_tag,$getmonthNo);
						 
						 if(!empty($getsearchTagviewDetails))
						 {
							 $images = $this->Document_Api_Model->get_user_gelleryprimarytag_detail_image($customerid,$primary_tag,$getsearchTagviewDetails->Date,$page,$limit);
							 	
							 	if(!empty($images))
							 	{

								 	
							 		$getsearchTagviewDetails->Primary_Tag = $this->post('Primary_Tag');
								 	$getsearchTagviewDetails->Prime_Name = $this->db->get_where('tbl_doc_primary_tag', array('Id' => $primary_tag))->row()->Prime_Name;
								 	$getsearchTagviewDetails->Month = date('F',strtotime($getsearchTagviewDetails->Date));
								 	$getsearchTagviewDetails->Images = $images;
								 	$Flag_Count = count($getsearchTagviewDetails->Images);
								 	if($Flag_Count == 20)
								 	{
								 		$Flag = 1;

								 	}
								 	else
								 	{
								 		$Flag = 0;

								 	}
								 	array_push($payload,$getsearchTagviewDetails);
							   }
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
	
      
}
