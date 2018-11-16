<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Companycustomer Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description Company Type Customer Controller
* @link http://zoddl.com/companycustomer
*/

class Entry extends CI_Controller
{

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Companycustomer>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Entry()
    {
        parent::__construct();
        $this->load->database();
        $this->load->library('s3_upload');
        $this->load->model("Customer_Api_Model");
        $this->load->model("Entry_model");
        
        /*cache control*/
	    $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		check_login();
    }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <index>
    * @Description:     <this function will Company Customer listing view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */
    
    public function index($customerid = '', $id = '')
        {
            $data = '';
			$info = '';

			$data['customerid'] = $customerid;           
    		$data['PageTitle'] = "Zoddl | Entry";

    		$data['page_title'] = "Add"; 

			$data['entryid'] = $id;

			$data['customerinfo'] = $this->Entry_model->get_customer_information($customerid);

			if($id != '')
			{
				$info = $this->Entry_model->get_tag_information($id);

				$data['page_title'] = "Edit"; 								
				
			}
			
			$data['info'] = $info;            
    		$this->load->view('admin/template/header2',$data);	
    		$this->load->view('admin/template/sidebar');		
    		$this->load->view('admin/entry/index',$data);
    		$this->load->view('admin/template/footer2');	
        }
   

    function dataresult($customerid = '')
    {
			  $id = ''; $date = ''; $tag = '';  $primary = ''; $secondary = ''; $status = ''; $amount = ''; $description = '';
			  
			  if(!empty($this->input->post('id')))
			  {
				  $id = $this->input->post('id');
			  }
			  if(!empty($this->input->post('date')))
			  {
				  $date = $this->input->post('date');
			  }
			  if(!empty($this->input->post('tag')))
			  {
				  $tag = $this->input->post('tag');
			  }
			  if(!empty($this->input->post('primary')))
			  {
				  $primary = $this->input->post('primary');
			  }
			  if(!empty($this->input->post('secondary')))
			  {
				  $secondary = $this->input->post('secondary');
			  }
			  if(!empty($this->input->post('status')))
			  {
				  $status = $this->input->post('status');
			  }
			  if(!empty($this->input->post('amount')))
			  {
				  $amount = $this->input->post('amount');
			  }

			  if(!empty($this->input->post('description')))
			  {
				  $description = $this->input->post('description');
			  }

       		  
              $count = $this->Entry_model->get_count_data( $customerid, $id,  $date, $tag, $primary, $secondary, $status, $amount, $description); 
              $iTotalRecords = $count;
              $iDisplayLength = intval($_REQUEST['length']);
              $iDisplayLength = $iDisplayLength < 0 ? $iTotalRecords : $iDisplayLength; 
              $iDisplayStart = intval($_REQUEST['start']);
              $sEcho = intval($_REQUEST['draw']);
              
              $records = array();
              $records["data"] = array(); 

              $end = $iDisplayStart + $iDisplayLength;
              $end = $end > $iTotalRecords ? $iTotalRecords : $end;

              $status_list = array(
                array("Approved" => "1"),
                array("Pending" => "2"),
                array("Decline" => "3"),
                array("Decline Blur" => "4")
              );

       		$data =  $this->Entry_model->get_result_data( $customerid, $id,  $date, $tag, $primary, $secondary, $status, $amount, $description, $iDisplayLength, $iDisplayStart);

             
              $i = 0;
              foreach($data as $data)
              {
				$i++;
				 
				 if($data->Tag_Status == 1)
				  {
					$status = "Approved";
				  }
				  else if($data->Tag_Status == 2)
				  {
					$status = "Pending";  
				  }
				  else if($data->Tag_Status == 3)
				  {
					  $status = "Decline";
				  }
				  else
				  {
					$status = "Decline Blur";  
				  }


                $records["data"][] = array(
                  //'<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input name="id[]" type="checkbox" class="checkboxes" value="'.$data->Id.'"/><span></span></label>',
                  $i,
                  $data->Tag_Send_Date,
                  $data->Prime_Name,
                  $data->Secondary_Name,
                  $data->Tag_Type,
                  $data->Amount,
                  $data->Description,				  
                  '<span class="label label-sm label-'.($status).'" style="color:black;">'.($status).'</span>',
                  '<a href="'.base_url('entry/index/'.$customerid.'/'.$data->Id).'" class="btn btn-sm btn-outline grey-salsa"><i class="fa fa-edit"></i> Edit</a>',
               );  


              }

              if (isset($_REQUEST["customActionType"]) && $_REQUEST["customActionType"] == "group_action") {
                $records["customActionStatus"] = "OK"; // pass custom message(useful for getting status of group actions)
                $records["customActionMessage"] = "Group action successfully has been completed. Well done!"; // pass custom message(useful for getting status of group actions)
              }

              $records["draw"] = $sEcho;
              $records["recordsTotal"] = $iTotalRecords;
              $records["recordsFiltered"] = $iTotalRecords;
              
              echo json_encode($records);

    }
	
	function getprimarytag()
	{
		
		if(!empty($_POST["cid"]) && !empty($_POST["val"])) {
			$query ="SELECT * FROM tbl_primary_tag WHERE Customer_Id = '".$_POST["cid"]."' AND Prime_Name like '" . $_POST["val"] . "%' ORDER BY Prime_Name LIMIT 0,15";
			$result = $this->db->query($query);
			if(!empty($result)) { ?>
				<ul id="country-list">
					<?php
					foreach($result->result() as $p) {
					?>
					<li onClick="selectCountry('<?php echo $p->Prime_Name; ?>');"><?php echo $p->Prime_Name; ?></li>
					<?php } ?>
					</ul>
				
			<?php }
		}
		if(!empty($_POST["cid"]) && empty($_POST["val"])) {
			$query ="SELECT * FROM tbl_primary_tag WHERE Customer_Id = '".$_POST["cid"]."' ORDER BY Prime_Name LIMIT 0,15";
			$result = $this->db->query($query);
			if(!empty($result)) { ?>
				<ul id="country-list">
					<?php
					foreach($result->result() as $p) {
					?>
					<li onClick="selectCountry('<?php echo $p->Prime_Name; ?>');"><?php echo $p->Prime_Name; ?></li>
					<?php } ?>
					</ul>
				
			<?php }
		}
	}
	
	function getsecondarytag()
	{
		
		if(!empty($_POST["cid"]) && !empty($_POST["val"])) {
			$query ="SELECT * FROM tbl_secondary_tag WHERE Customer_Id = '".$_POST["cid"]."' AND Secondary_Name like '" . $_POST["val"] . "%' ORDER BY Secondary_Name LIMIT 0,15";
			$result = $this->db->query($query);
			if(!empty($result)) { ?>
				<ul id="country-list">
					<?php
					foreach($result->result() as $p) {
					?>
					<li onClick="selectCountrys('<?php echo $p->Secondary_Name; ?>');"><?php echo $p->Secondary_Name; ?></li>
					<?php } ?>
					</ul>
				
			<?php }
		}

		if(!empty($_POST["cid"]) && empty($_POST["val"])) {
			$query ="SELECT * FROM tbl_secondary_tag WHERE Customer_Id = '".$_POST["cid"]."' ORDER BY Secondary_Name LIMIT 0,15";
			$result = $this->db->query($query);
			if(!empty($result)) { ?>
				<ul id="country-list">
					<?php
					foreach($result->result() as $p) {
					?>
					<li onClick="selectCountrys('<?php echo $p->Secondary_Name; ?>');"><?php echo $p->Secondary_Name; ?></li>
					<?php } ?>
					</ul>
				
			<?php }
		}
	}

	public function _create_thumbnail($fileName) 
			{
			    	
			    	
					$this->load->library('image_lib');			    	
				    $config['upload_path'] = 'upload/';
					$config['allowed_types'] = 'jpg|png|gif|bmp|jpeg|PNG|JPG|JPEG|GIF|BMP';
					$config['max_size'] = '50000000000';												
					$config['encrypt_name']	= TRUE;
					$this->load->library('upload', $config);

					if ( ! $this->upload->do_upload('file'))
					{
						echo $this->upload->display_errors();
					}
					else
					{
						//Image Resizing

						$image_data =   $this->upload->data();
						$configer =  array(
			              'image_library'   => 'gd2',
			              'source_image'    =>  $image_data['full_path'],
			              'maintain_ratio'  =>  FALSE,
			              'width'           =>  250,
			              'height'          =>  250,
			            );
			            $this->image_lib->clear();
			            $this->image_lib->initialize($configer);
			            $this->image_lib->resize();
						return $image_data['full_path'];

						
					}    
			}
	
	public function addmanual($customerid = '')
	{
				$originalFilePath = '';
				$thumbFilePath = '';
				$originalImage = '';
				$thumbImage = '';
				$isupload = 0;

			    if (!empty($_FILES['file']['name'])) {

						$config['upload_path'] = 'upload/';						
						$config['allowed_types'] = 'jpg|png|gif|bmp|jpeg|PNG|JPG|JPEG|GIF|BMP';
						$config['max_size'] = '50000000000';												
						$config['encrypt_name']	= TRUE;
						$this->load->library('upload', $config);
						$this->upload->initialize($config);
						if ( ! $this->upload->do_upload('file'))
						{
							echo $this->upload->display_errors();
						}
						else{
								$img_data  = $this->upload->data();
								$fileName = $_FILES['file']['name'];

								$originalImage = $img_data['full_path'];
								$thumbImage = $this->_create_thumbnail($fileName);
						}

						 $originalFilePath = $this->s3_upload->upload_file($originalImage);
				         $thumbFilePath = $this->s3_upload->upload_file($thumbImage);
				         $isupload = 1;
			   
			   }
			  				

				$customerid = $this->input->post('Customer_Id');
				$tabletag = "tbl_tag";				
				$tableprimary = "tbl_primary_tag";
				$tablesecondry = "tbl_secondary_tag";
				$checkprimarytag = $this->Customer_Api_Model->check_primary_tag($tableprimary,$customerid,fpv($this->input->post('Primary_Tag')));
				$checksecondrytag = $this->check_secondry_tag($tablesecondry,$customerid,$this->input->post('Secondry_Tag'));
				$amount = fpv($this->input->post('Amount'));
				$description = fpv($this->input->post('Description'));
				$tagtype = fpv($this->input->post('Tag_Type'));
				$Sub_Description = fpv($this->input->post('Sub_Description'));
				$CGST = fpv($this->input->post('CGST'));
				$SGST = fpv($this->input->post('SGST'));
				$IGST = fpv($this->input->post('IGST'));
				$Invoice_Check_No = fpv($this->input->post('Invoice_Check_No'));
				$tagsenddate = fpv($this->input->post('Tag_Send_Date'));
				$tagstatus = fpv($this->input->post('Tag_Status'));				
				$mysqldate = date('Y-m-d', strtotime($tagsenddate));
				if($description == '')
				{
					$description = null;

				}
				else
				{
					$description = $description;

				}
				
				if($this->input->post('id') == '')
				{
					$data = array(
							'Customer_Id'=>$customerid,
							'Tag_Type'=>$tagtype,
							'Primary_Tag'=>$checkprimarytag,
							'Secondry_Tag'=>$checksecondrytag,
							'Amount'=>$amount,
							'Description'=>$description,
							'Sub_Description'=>$Sub_Description,
							'CGST'=>$CGST,
							'SGST'=>$SGST,
							'IGST'=>$IGST,
							'Invoice_Check_No'=>$Invoice_Check_No,						
							'Tag_Send_Date'=>$tagsenddate,
							'Tag_Status'=>$tagstatus,
							'Image_Url' => $originalFilePath,
							'Image_Url_Thumb' => $thumbFilePath,
							'Tag_Date'=>$mysqldate,
							'isUploaded'=>$isupload,
							'Tag_Date_int'=>strtotime($tagsenddate)
							);

					$insert_tag = $this->Entry_model->insert_tag($tabletag,$data);
				}
				else
				{
						   if($originalFilePath != '' && $thumbFilePath != '')
						   {
							   $data = array(
								'Customer_Id'=>$customerid,
								'Tag_Type'=>$tagtype,
								'Primary_Tag'=>$checkprimarytag,
								'Secondry_Tag'=>$checksecondrytag,
								'Amount'=>$amount,
								'Description'=>$description,
								'Sub_Description'=>$Sub_Description,
								'CGST'=>$CGST,
								'SGST'=>$SGST,
								'IGST'=>$IGST,
								'Image_Url' => $originalFilePath,
								'Image_Url_Thumb' => $thumbFilePath,
								'Invoice_Check_No'=>$Invoice_Check_No,						
								'Tag_Send_Date'=>$tagsenddate,
								'Tag_Status'=>$tagstatus,
								'Tag_Date'=>$mysqldate,
								'isUploaded'=>1,
								'Tag_Date_int'=>strtotime($tagsenddate)
								);
							 }
							 else
							 {
							 	$data = array(
								'Customer_Id'=>$customerid,
								'Tag_Type'=>$tagtype,
								'Primary_Tag'=>$checkprimarytag,
								'Secondry_Tag'=>$checksecondrytag,
								'Amount'=>$amount,
								'Description'=>$description,
								'Sub_Description'=>$Sub_Description,
								'CGST'=>$CGST,
								'SGST'=>$SGST,
								'IGST'=>$IGST,
								'Invoice_Check_No'=>$Invoice_Check_No,						
								'Tag_Send_Date'=>$tagsenddate,
								'Tag_Status'=>$tagstatus,
								'Tag_Date'=>$mysqldate,								
								'Tag_Date_int'=>strtotime($tagsenddate)
								);

							 }

							$condition = $this->input->post('id');							
							$update_tag = $this->Entry_model->update_tag($tabletag, $condition, $data);
							

					
				}
				redirect(base_url('entry/index/'.$customerid),'refresh');
		
	}
	
	public function check_secondry_tag($tablesecondry,$customerid,$Secondry_Tag)
	{
				  	
		if(empty($Secondry_Tag))
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
			
			  	$is_secondry = $this->db->get_where($tablesecondry, array('Customer_Id' => $customerid,'Secondary_Name' => $Secondry_Tag));


			  	if($is_secondry->num_rows() > 0)
				{
				     $secondryid = $is_secondry->row();	
				     $secondry_id = $secondryid->Id;		
				}
				else
				{
						   $data = array(
	       				       'Customer_Id'=>$customerid,
					       	   'Secondary_Name'=>$Secondry_Tag
	    				   );

		    			$this->db->insert($tablesecondry,$data);
						$secondry_id = $this->db->insert_id();   			

				}

			

			return $secondry_id;
			
		}
		
	}
	
	
	
	


    
}
