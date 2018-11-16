<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Document_Api Controller 
 */

require APPPATH . '/libraries/REST_Controller.php';

class Report_Api extends REST_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('Report_Api_Model');
        $this->load->model('Customer_Api_Model');
        $this->load->helper('html');
		$this->load->helper('pdf_helper'); 

    }
	
	
	public function genrate_pdf($html)
	{
		//echo $_SERVER['DOCUMENT_ROOT']; die;
		
		$html = mb_convert_encoding($html, 'UTF-8', 'UTF-8');

		$filenamepath = "PDF".time();

		$mpdf=new mpDF('c','A4','','',20,20,20,25,10,10); 
		$mpdf->Setprotection(array('print'));
		$mpdf->SetTitle("Zoddl. - Report");
		$mpdf->SetAuthor("Zoddl.");
		$mpdf->watermark_font = 'DejaVuSansCondensed';
		$mpdf->watermarkTextAlpha = 0.1;
		$mpdf->SetdisplayMode('fullpage');
		$mpdf->WriteHTML($html);
		$mpdf->Output($_SERVER['DOCUMENT_ROOT'].'/zoddlstaging/upload/'.$filenamepath . '.pdf', 'F');
		$mpdf->Output(); 

		
		return $filenamepath;

	}

	public function report_post($cid = '', $reportjson = '', $returnstatus = '') {
		/* $reportjson = 	'[{
			  "column1":{
			      "Source_Type": "image gallery",
			      "Tag_Type": "primary tag",
			      "Id": "371"
			   },
			   "column2":{
			      "Source_Type": "universal",
			      "Tag_Type": "master",
			      "Id": "month"
			   },
			  "column3":{
			      "Source_Type": "universal",
			      "Tag_Type": "master",
			      "Id": "amount"
			   },
			"column4":{
			      "Source_Type": "universal",
			      "Tag_Type": "master",
			      "Id": "cash+"
			   },
			"column5":{
			      "Source_Type": "universal",
			      "Tag_Type": "master",
			      "Id": "year"
			   }
			}]';

		$data = json_decode($reportjson);		 
		$payload = array();
		$customerid = 126;
		$response = array();
		$isPdf = 1; */

        
		/******************************** Api Log Generate History Start ********************************/
		/* $this->load->helper('log_helper');
		$Table_Name = "tbl_log";
		$Api_Date = date("Y-m-d H:m:s");
		$dataJson = !empty($this->post('reportjson')) ? $this->post('reportjson') : "";

		Api_Log_Generate($Table_Name, $dataJson, $Api_Date); */
		/******************************** Api Log Generate History End ********************************/

		if($cid == '' || $reportjson == '' || $returnstatus == ''){
			$customerid = '';
			$payload = array();
			$response = array();
			$htmldata = array();
			$html = array();	
			$customerauthtoken = $this->post('Authtoken');
			$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
			$data = json_decode($this->post('reportjson'));
			$savereport = $this->post('savereport');
			$returnstatus = '';
		} else {
			$customerid = '';
			$payload = array();
			$response = array();
			$htmldata = array();
			$html = array();
			$customerid = $cid;
			$data = json_decode($reportjson);
			$returnstatus = 1;
		}

		//pr($data); die;


		if($customerid) {
			if(empty($data) || $data == '') {
				$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
				$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

				if($returnstatus == 1) {
					return $view;
				} else {
					array_push($htmldata ,$html);					 			
					$responseapp['ResponseCode'] = "400";
					$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
					$responseapp['Payload'] = $htmldata;
					$this->response($responseapp);
				}
			} else {
				if($savereport == 1) {
					$insertData = array(
						'Customer_Id' => $customerid,									
						'reportjson' => $this->input->post('reportjson'),
						'add_date' => strtotime(date("Y-m-d H:i:s"))
					);

					$insertRec= $this->db->insert('tbl_report',$insertData);

					$response['errormessage'] = "Report has been saved successfully.";
					$html['html'] = $this->load->view('reportapi/error-message',$response, true);
					array_push($htmldata ,$html);					 			
					$responseapp['ResponseCode'] = "200";
					$responseapp['ResponseMessage'] = 'Report has been saved successfully.';
					$responseapp['Payload'] = $htmldata;
					$this->response($responseapp);					
					exit();
				}

				foreach($data as $data) {

				$total = count((array)$data);
		 	
				if($total == 1) // Total column count reportJson Input
				{
		 			if($data->column1->Source_Type == 'image gallery')
		 			{
		 				$tableTag = "tbl_tag";
		 				$tablePrimary = "tbl_primary_tag";
		 				$tableSecondary = "tbl_secondary_tag";
		 			}
		 			else
		 			{
		 				$tableTag = "tbl_doc_tag";
		 				$tablePrimary = "tbl_doc_primary_tag";
		 				$tableSecondary = "tbl_doc_secondary_tag";

		 			}

		 			if($data->column1->Tag_Type == 'primary tag')
		 			{
	 					$gellery_type = strtolower($data->column1->Source_Type);
		 				$type = strtolower($data->column1->Tag_Type);
		 				$value = strtolower($data->column1->Id);
	 					
	 					$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary);

	 					//echo $this->db->last_query(); die;

		 				$response['request'] = $this->Report_Api_Model->get_primaryName($tablePrimary,$value);
		 				$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1-primary-secondary',$response,true);
			 			
			 			//print_r($view); exit;
			 			
			 			if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
				 			array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}

		 			}
		 			else if($data->column1->Tag_Type == 'secondary tag')
		 			{
		 				$gellery_type = strtolower($data->column1->Source_Type);
		 				$type = strtolower($data->column1->Tag_Type);
		 				$value = strtolower($data->column1->Id);
		 				
		 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary);

		 				//echo $this->db->last_query(); die;

		 				$response['request'] = $this->Report_Api_Model->get_secondaryName($tableSecondary,$value);
		 				$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1-primary-secondary',$response, true);

		 				//print_r($view); exit;

		 				if($returnstatus == 1)
						{

							return $view;
						}
						else
						{
			 				array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
			 			
		 			}
		 			else if($data->column1->Tag_Type == 'master')
		 			{
		 				$gellery_type = strtolower($data->column1->Source_Type);
		 				$type = strtolower($data->column1->Tag_Type);
		 				$value = strtolower($data->column1->Id);

		 				if($value == 'year')
		 				{

		 					$response['dataentry'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
		 					$response['dataentrytotal'] = $gettotaldata = $this->Report_Api_Model->get_total_data_universal($customerid, $value);
		 					$response['request'] = $value;
		 					$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1-year-month',$response, true);

		 					//echo ($view); exit;

		 					if($returnstatus == 1)
							{

								return $view;
							}
							else
							{
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}

		 				}
		 				else if($value == 'month')
		 				{
		 					$response['dataentry'] = $gettotalentrybymonth = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
		 					
		 					$response['dataentrytotal'] = $gettotaldata = $this->Report_Api_Model->get_total_data_universal($customerid, $value);
		 					
		 					$response['request'] = $value;
		 					$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1-year-month',$response, true);

		 					//print_r($view); exit;

		 					if($returnstatus == 1)
							{

								return $view;
							}
							else
							{
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
		 					
		 				}
		 				else if($value == 'amount')
		 				{
		 					$response['data'] = $getAmount = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
		 					$response['request'] = $value;			 					
		 					$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1',$response, true);

		 					//print_r($view); exit;

		 					if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}

		 				}
		 				else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
		 				{	
			 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_year_universal($customerid,$value);
			 				$response['request'] = $value;
		 					$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1',$response, true);

		 					//print_r($view); exit;
		 					if($returnstatus == 1)
							{

								return $view;
							}
							else
							{
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
		 				}

		 				else if($value == 'all')
		 				{
							
							$response['dataentry'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
		 					$response['dataentrytotal'] = $gettotaldata = $this->Report_Api_Model->get_total_data_universal($customerid, $value);
		 					$response['request'] = $value;
		 					$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1-year-month',$response, true);

		 					//print_r($gettotaldata); die;

		 					//echo ($view); exit;

		 					if($returnstatus == 1)
							{

								return $view;
							}
							else
							{
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}


		 				}
		 				else if($value == 'count')
		 				{

		 					$response['data'] = $getAmount = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
		 					$response['request'] = $value;			 					
		 					$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1',$response, true);

		 					//print_r($view); exit;
		 					if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}


		 				}
		 				else if($value == 'day')
		 				{
							
							$response['dataentry'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
		 					$response['dataentrytotal'] = $gettotaldata = $this->Report_Api_Model->get_total_data_universal($customerid, $value);
		 					$response['request'] = $value;
		 					$html['html'] = $view = $this->load->view('reportapi/column1/report-response-column1-year-month',$response, true);

		 					//print_r($gettotaldata); die;

		 					//echo ($view); exit;

		 					if($returnstatus == 1)
							{

								return $view;
							}
							else
							{
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}


		 				}

		 				else
		 				{
		 					
		 					$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
							$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

							if($returnstatus == 1)
							{

								return $view;
							}
							else
							{

								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "400";
								$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
		 				}

		 			}
		 			else
		 			{
	 					$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
						$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

						if($returnstatus == 1)
						{

							return $view;
						}
						else
						{

							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "400";
							$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
		 			}
			 	}
			 	else if($total == 2) // Total column count reportJson Input
			 	{	
				
					foreach($data as $key => $value ) {
							if(is_numeric($value->Id))
							$data1[$key] = $value->Tag_Type;
						else
							$data1[$key] = $value->Id;
						
					}
					ksort($data1);

			 		foreach($data1 as $key => $value) {
						
						if(is_numeric($value))
							$aa[] = $value;
						else
							$aa[] = $value;
					}

					if($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery')
					{
	 				$tableTag = "tbl_tag";
	 				$tablePrimary = "tbl_primary_tag";
	 				$tableSecondary = "tbl_secondary_tag";
	 				}
		 			elseif( ($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'universal')  || ($data->column1->Source_Type == 'universal' && $data->column2->Source_Type == 'image gallery') )
					{
		 				$tableTag = "tbl_tag";
		 				$tablePrimary = "tbl_primary_tag";
		 				$tableSecondary = "tbl_secondary_tag";
	 				}
		 			elseif( ($data->column1->Source_Type == 'document gallery' && $data->column2->Source_Type == 'universal')  || ($data->column1->Source_Type == 'universal' && $data->column2->Source_Type == 'document gallery') )
					{
		 				$tableTag = "tbl_doc_tag";
		 				$tablePrimary = "tbl_doc_primary_tag";
		 				$tableSecondary = "tbl_doc_secondary_tag";
	 				}
		 			else
		 			{
		 				$tableTag = "tbl_doc_tag";
		 				$tablePrimary = "tbl_doc_primary_tag";
		 				$tableSecondary = "tbl_doc_secondary_tag";

		 			}

	 				if(($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'secondary tag') || ($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'primary tag'))
					{
						if($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'secondary tag')
						{
							$primaryTag = $data->column1->Id;
							$secondaryTag = $data->column2->Id;
						}
						else
						{
							$secondaryTag = $data->column1->Id;
							$primaryTag = $data->column2->Id;
						} 
						//echo "primaryTag: ".$primaryTag."/ secondaryTag:".$secondaryTag ;

						$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_columen2($customerid, $tableTag, $tablePrimary, $tableSecondary, $primaryTag, $secondaryTag);

						$response['Primary_tag'] = $primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
						$response['Secondary_tag'] = $secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
						$response['request4'] = array('primary_tag' => $primeryName, 'secondary_tag' => $secondaryName);
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);
						
						//echo ($view); exit;
						
						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
 					}
					else if(($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag'))
					{
						if($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master')
						{
							$secondaryTag = '';
							$primaryTag = $data->column1->Id;
							$value = $data->column2->Id;
						}
						else
						{	 
							$secondaryTag = '';
							$value = $data->column1->Id;
							$primaryTag = $data->column2->Id;
						}

		 				if($value == 'year')
		 				{

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);

							$response['Primary_tag'] = $Name = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);

							$response['request2'] = $value;
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
								
		 				}
		 				else if($value == 'all')
		 				{

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);

							$response['Primary_tag'] = $Name = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);

							$response['request2'] = $value;
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
								
		 				}
		 				else if($value == 'day')
		 				{

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);

							$response['Primary_tag'] = $Name = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);

							$response['request2'] = $value;
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
								
		 				}

		 				else if($value == 'count')
		 				{

							
							if($data->column1->Source_Type == 'image gallery' || $data->column1->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column1->Source_Type;
							}
							else if($data->column2->Source_Type == 'image gallery' || $data->column2->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column2->Source_Type;
							}

							$type = "primary tag";
							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $primaryTag, $tableTag, $tablePrimary, $tableSecondary);
							$response['Primary_tag'] = $primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$response['request2'] = $value;
							$response['request4'] = array('primary_tag' => $primeryName, 'Count' => $gettotaldata->No_Of_Entry);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);

							//print_r($response);
							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
								
		 				}
		 				else if($value == 'month')
			 			{
		 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
	 						$response['Primary_tag'] = $Name = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
		 					$response['request2'] = $value;
		 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
		 					$response['request7'] = $aa;
		 					$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;
		 					if($returnstatus == 1)
						     {
							   return $view;
						      }
						    else
						     {
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);

							 }
		 					
			 			}
				 		else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
			 			{
							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_tagtype($customerid, $tableTag, $value, $primaryTag, $secondaryTag);
							$response['Primary_tag'] = $primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$response['request2'] = $value;
							$response['request4'] = array('primary_tag' => $primeryName, 'tag_type' => $value);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);
							
							//print_r($view); exit;
							
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
			 			else if($value == 'amount')
				 		{
							if($data->column1->Source_Type == 'image gallery' || $data->column1->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column1->Source_Type;
							}
							else if($data->column2->Source_Type == 'image gallery' || $data->column2->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column2->Source_Type;
							}

							$type = "primary tag";
							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $primaryTag, $tableTag, $tablePrimary, $tableSecondary);
							$response['Primary_tag'] = $primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$response['request2'] = $value;
							$response['request4'] = array('primary_tag' => $primeryName, 'Amount' => $gettotaldata->Total_Amount);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);

							//print_r($response);
							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
		 				else
						{
							$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
							$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "400";
								$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
 					}
 					else if(($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag'))
 					{
						if($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master')
						{
							$primaryTag = '';
							$secondaryTag = $data->column1->Id;
							$value = $data->column2->Id;
						}
						else
						{
							$primaryTag = '';
							$value = $data->column1->Id;
							$secondaryTag = $data->column2->Id;
						}

						if($value == 'year')
						{
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);
							$response['Secondary_tag'] = $Name = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['request2'] = $value;
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if($value == 'all')
		 				{

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);
							$response['Secondary_tag'] = $Name = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['request2'] = $value;
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
								
		 				}
		 				else if($value == 'day')
		 				{

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);
							$response['Secondary_tag'] = $Name = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['request2'] = $value;
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
								
		 				}

		 				else if($value == 'count')
		 				{

							
							if($data->column1->Source_Type == 'image gallery' || $data->column1->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column1->Source_Type;
							}
							else if($data->column2->Source_Type == 'image gallery' || $data->column2->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column2->Source_Type;
							}

							$type = "secondary tag";								 				
							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $secondaryTag, $tableTag, $tablePrimary, $tableSecondary);
							$response['Secondary_tag'] = $secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['request2'] = $value;
							$response['request4'] = array('secondary_tag' => $secondaryName, 'Count' => $gettotaldata->No_Of_Entry);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);


							//print_r($response); 
							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
								
		 				}
		 				else if($value == 'month')
						{
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
							$response['Secondary_tag'] = $Name = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['request2'] = $value;
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
				 		else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
			 			{

							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_tagtype($customerid, $tableTag, $value, $primaryTag, $secondaryTag);
							$response['Secondary_tag'] = $secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['request2'] = $value;
							$response['request4'] = array('secondary_tag' => $secondaryName, 'tag_type' => $value);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
			 			}
			 			else if($value == 'amount')
						{

							if($data->column1->Source_Type == 'image gallery' || $data->column1->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column1->Source_Type;
							}
							else if($data->column2->Source_Type == 'image gallery' || $data->column2->Source_Type == 'document gallery')
							{
								$gellery_type = $data->column2->Source_Type;
							}

							$type = "secondary tag";								 				
							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $secondaryTag, $tableTag, $tablePrimary, $tableSecondary);
							$response['Secondary_tag'] = $secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['request2'] = $value;
							$response['request4'] = array('secondary_tag' => $secondaryName, 'Amount' => $gettotaldata->Total_Amount);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
			 			else
			 			{
							$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
							$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "400";
								$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
					}
					else if(($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master') || ($data->column2->Tag_Type == 'master' && $data->column1->Tag_Type == 'master'))
 					{
						if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master')
						{
							$primaryTag = '';
							$secondaryTag = '';
							$master1 = $data->column1->Id;
							$master2 = $data->column2->Id;
						}
						else
						{
							$primaryTag = '';
							$secondaryTag = '';
							$master2 = $data->column1->Id;
							$master1 = $data->column2->Id;
						}

						$tagType = array("cash+","cash-","bank+", "bank-","other");

						if( ($master1 == 'year' && $master2 == 'month') || ($master1 == 'month' && $master2 == 'year') )
						{
							

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							
							$response['request1'] = "Year";
							$response['request2'] = "Month";
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);
							$response['request7'] = $aa;
							$html['html'] =  $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);
							
							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
 						else if( ($master1 == 'year' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'year') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "Year";
							$response['request2'] = "Amount";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);												

							$sumArray = array();
							$arrObject = new stdClass();
							foreach($gettotaldata as $k=>$subArray){
								$sumArray[$subArray->Year]+=$subArray->Amount;
							}

							if(count($sumArray)>0){
								$i=0;
								foreach($sumArray as $year=>$amount) {
									$arrObject->$i->Year = $year;
									$arrObject->$i->Amount = $amount;
									$i++;
								}
							}

							$response['request4'] = $arrObject;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

						else if( ($master1 == 'year' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'year') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "Year";
							$response['request2'] = "Count";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);												

							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'year' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'year') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);

 							//echo $this->db->last_query(); die;
							$response['request1'] = "Year";
							$response['request2'] = "Day";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);	

							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

						else if( ($master1 == 'year' && $master2 == 'all') || ($master1 == 'all' && $master2 == 'year') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);

 							//echo $this->db->last_query(); die;
							$response['request1'] = "Year";
							$response['request2'] = "All";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);	

							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
 						else if( ($master1 == 'month' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'month') )
 						{
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "Month";
							$response['request2'] = "Amount";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);

							$sumArray = array();
							$arrObject = new stdClass();
							foreach($gettotaldata as $k=>$subArray){
								$sumArray[$subArray->Month]+=$subArray->Amount;
							}

							if(count($sumArray)>0){
								$i=0;
								foreach($sumArray as $month=>$amount) {
									$arrObject->$i->Month = $month;
									$arrObject->$i->Amount = $amount;
									$i++;
								}
							}

							$response['request4'] = $arrObject;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

						else if( ($master1 == 'month' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'month') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "Month";
							$response['request2'] = "Count";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);												

							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'month' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'month') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);

 							//echo $this->db->last_query(); die;
							$response['request1'] = "Month";
							$response['request2'] = "Day";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);	

							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

						else if( ($master1 == 'month' && $master2 == 'all') || ($master1 == 'all' && $master2 == 'month') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);

 							//echo $this->db->last_query(); die;
							$response['request1'] = "Month";
							$response['request2'] = "All";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);	

							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
 						else if( ($master1 == 'month' && in_array($master2, $tagType)) || (in_array($master1, $tagType)  && $master2 == 'month') )
 						{
							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$response['tagtype'] = $tagType;
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tagtype);
							$response['request1'] = $tagtype;
							$response['request2'] = $value;
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $value, $tagtype);

							$sumArray = array();
							$arrObject = new stdClass();
							foreach($gettotaldata as $k=>$subArray){
								$sumArray[$subArray->Month]+=$subArray->Amount;
							}

							if(count($sumArray)>0){
								$i=0;
								foreach($sumArray as $month=>$amount) {
									$arrObject->$i->Month = $month;
									$i++;
								}
							}

							$response['request4'] = $arrObject;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//echo $view; die;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'year' && in_array($master2, $tagType)) || ($master2 == 'year' && in_array($master1, $tagType)) )
						{
							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tagtype);
							$response['request1'] = $tagtype;
							$response['request2'] = $value;
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $value, $tagtype);

							$sumArray = array();
							$arrObject = new stdClass();
							foreach($gettotaldata as $k=>$subArray){
								$sumArray[$subArray->Year]+=$subArray->Amount;
							}

							if(count($sumArray)>0){
								$i=0;
								foreach($sumArray as $year=>$amount) {
									$arrObject->$i->Year = $year;
									$i++;
								}
							}

							$response['request4'] = $arrObject;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);
							
							
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
 						else if( ($master1 == 'amount' && in_array($master2, $tagType)) || ($master2 == 'amount' && in_array($master1, $tagType)) )
 						{
							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$gellery_type = $data->column1->Source_Type;								 				
							$type = "master";

							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tagtype);

							
							$response['request1'] = $tagtype;
							$response['request2'] = "Amount";
							$response['request4'] = array('tag_type' => $tagtype, 'Amount' => $gettotaldata->Total_Amount);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							} 								
 						}
 						else if( ($master1 == 'count' && in_array($master2, $tagType)) || ($master2 == 'count' && in_array($master1, $tagType)) )
 						{
							
							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tagtype);
							$response['request1'] = $tagtype;
							$response['request2'] = $value;
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $value, $tagtype);
							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);
							
							//print_r($view); exit;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'day' && in_array($master2, $tagType)) || ($master2 == 'day' && in_array($master1, $tagType)) )
 						{
							
 							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tagtype);
							$response['request1'] = $tagtype;
							$response['request2'] = $value;
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $value, $tagtype);
							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);
							
							//print_r($view); exit;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

						/* else if( ($master1 == 'all' && in_array($master2, $tagType)) || ($master2 == 'all' && in_array($master1, $tagType)) )
 						{
							
 							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tagtype);
							$response['request1'] = $tagtype;
							$response['request2'] = $value;
							//$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $value, $tagtype);
							$response['request4'] = array('Tag_Type' => $tagtype, 'All' => $gettotalentrybyyear->No_Of_Entry);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);
							
							print_r($view); exit;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						} */
						else if( ($master1 == 'all' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'all') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "All";
							$response['request2'] = "Amount";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);												

							$sumArray = array();
							$arrObject = new stdClass();
							foreach($gettotaldata as $k=>$subArray){
								$sumArray[$subArray->Tag_Type]+=$subArray->Amount;
							}

							if(count($sumArray)>0){
								$i=0;
								foreach($sumArray as $tagtype=>$amount) {
									$arrObject->$i->Tag_Type = $tagtype;
									$arrObject->$i->Amount = $amount;
									$i++;
								}
							}

							$response['request4'] = $arrObject;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'day' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'day') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "Day";
							$response['request2'] = "Amount";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);												

							$sumArray = array();
							$arrObject = new stdClass();
							foreach($gettotaldata as $k=>$subArray){
								$sumArray[$subArray->Date]+=$subArray->Amount;
							}

							if(count($sumArray)>0){
								$i=0;
								foreach($sumArray as $date=>$amount) {
									$arrObject->$i->Date = $date;
									$arrObject->$i->Amount = $amount;
									$i++;
								}
							}

							$response['request4'] = $arrObject;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'count' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'count') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "Count";
							$response['request2'] = "Amount";

							$response['request4'] = array('Count' =>  $gettotalentrybyyear->No_Of_Entry, 'Amount' => $gettotalentrybyyear->Total_Amount);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'all' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'all') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "All";
							$response['request2'] = "Day";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);
							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'all' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'all') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "All";
							$response['request2'] = "Count";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);
							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'day' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'day') )
 						{
							
 							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
							$response['request1'] = "Day";
							$response['request2'] = "Count";
							$gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth_universal($customerid, $master1, $master2);
							$response['request4'] = $gettotaldata;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column2/report-response-column2-year-month',$response, true);

							//print_r($view); exit;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
 						else
 						{
							$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
							$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "400";
								$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
 					}
					else
					{
						$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
						$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "400";
							$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}
			 	}
			 	else if($total == 3) // Total column count reportJson Input
			 	{
			 			
					if($data->column1->Source_Type == 'image gallery' || $data->column2->Source_Type == 'image gallery' || $data->column3->Source_Type == 'image gallery')
					{
						$tableTag = "tbl_tag";
						$tablePrimary = "tbl_primary_tag";
						$tableSecondary = "tbl_secondary_tag";
					}
					else
					{
						$tableTag = "tbl_doc_tag";
						$tablePrimary = "tbl_doc_primary_tag";
						$tableSecondary = "tbl_doc_secondary_tag";
					}

					$tagType = array("cash+","cash-","bank+", "bank-","other");
					
					
					foreach($data as $key => $value ) {
							if(is_numeric($value->Id))
							$data1[$key] = $value->Tag_Type;
						else
							$data1[$key] = $value->Id;
						
					}
					ksort($data1);

					foreach($data1 as $key => $value) {
						
						if(is_numeric($value))
							$aa[] = $value;
						else
							$aa[] = $value;
					}



					if(($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master' ) || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'primary tag') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'master'))
					{
						if($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master')
						{
							$primaryTag = $data->column1->Id;
							$master1 = $data->column2->Id;
							$master2 = $data->column3->Id;
							$secondaryTag = '';
						}
						else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'primary tag')
						{
							$master1 = $data->column1->Id;
							$master2 = $data->column2->Id;
							$primaryTag = $data->column3->Id;
							$secondaryTag = '';
						}
						else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'master')
						{
							$master1 = $data->column1->Id;
							$primaryTag = $data->column2->Id;
							$master2 = $data->column3->Id;
							$secondaryTag = '';
						}
						else
						{
							$master1 = $data->column1->Id;
							$primaryTag = $data->column2->Id;
							$master2 = $data->column3->Id;
							$secondaryTag = '';
						}

						if($master1 == 'year' || $master2 == 'year')
						{
							if(($master1 == 'year' && $master2 == 'month') || ($master1 == 'month' && $master2 == 'year'))
							{

								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Year";
								$response['request3'] = "Month";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;

								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//print_r($response);
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'year'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Year";
								$response['request3'] = "Amount";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if( ($master1 == 'year' && in_array($master2, $tagType)) || ( in_array($master1, $tagType) && $master2 == 'year') )
							{

								if(in_array($master2, $tagType))
								{
									$tagtype = $master2;
									$value = $master1;
								}
								else
								{
									$tagtype = $master1;
									$value = $master2;
								}											

								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = $tagtype;
								$response['request3'] = "Year";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'all') || ($master1 == 'all' && $master2 == 'year'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Year";
								$response['request3'] = "All";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'year'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Year";
								$response['request3'] = "Day";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'year'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Year";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else
							{
								$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
								$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "400";
									$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
						}
						else if($master1 == 'month' || $master2 == 'month')
						{
							if( ($master1 == 'month' && in_array($master2, $tagType)) || ( in_array($master1, $tagType) && $master2 == 'month') )
							{
								if(in_array($master2, $tagType))
								{
									$tagtype = $master2;
									$value = $master1;
								}
								else
								{
									$tagtype = $master1;
									$value = $master2;
								}											

								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = $tagtype;
								$response['request3'] = "Month";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'month' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'month'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Month";
								$response['request3'] = "Amount";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'month' && $master2 == 'all') || ($master1 == 'all' && $master2 == 'month'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Month";
								$response['request3'] = "All";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'month' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'month'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Month";
								$response['request3'] = "Day";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'month' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'month'))
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Month";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}

						}
						else if( ($master1 == 'amount' && in_array($master2, $tagType)) || ( in_array($master1, $tagType) && $master2 == 'amount') )
						{
							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);	
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2, $tagtype);

							//echo $this->db->last_query(); die;							 					

							$response['Primary_tag'] = $primeryName;
							$response['request2'] = $tagtype;
							$response['request4'] = array('Amount' => $gettotalentrybyyear->Total_Amount);
							$response['request7'] = $aa;							 					
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'all' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'all'))
						{

								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "All";
								$response['request3'] = "Day";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							

						}
						else if( ($master1 == 'all' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'all'))
						{

								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "All";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}

						}
						else if( ($master1 == 'day' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'day'))
						{

								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Primary_tag'] = $primeryName;
								$response['request2'] = "Day";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}

						}
						else
						{
							$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
							$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "400";
								$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

					}
					else if(($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master' ) || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'secondary tag') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'master'))
					{
						if($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master')
						{
							$secondaryTag = $data->column1->Id;
							$master1 = $data->column2->Id;
							$master2 = $data->column3->Id;
							$primaryTag = '';
						}
						else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'secondary tag')
						{
							$master1 = $data->column1->Id;
							$master2 = $data->column2->Id;
							$secondaryTag = $data->column3->Id;
							$primaryTag = '';
						}
						else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'master')
						{
							$master1 = $data->column1->Id;
							$secondaryTag = $data->column2->Id;
							$master2 = $data->column3->Id;
							$primaryTag = '';
						}
						else
						{
							$master1 = $data->column1->Id;
							$secondaryTag = $data->column2->Id;
							$master2 = $data->column3->Id;
							$primaryTag = '';
						}

						if($master1 == 'year' || $master2 == 'year')
						{
							if(($master1 == 'year' && $master2 == 'month') || ($master1 == 'month' && $master2 == 'year'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Year";
								$response['request3'] = "Month";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'year'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Year";
								$response['request3'] = "Amount";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if( ($master1 == 'year' && in_array($master2, $tagType)) || ( in_array($master1, $tagType) && $master2 == 'year') )
							{
								if(in_array($master2, $tagType))
								{
									$tagtype = $master2;
									$value = $master1;
								}
								else
								{
									$tagtype = $master1;
									$value = $master2;
								}											

								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);									 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = $tagtype;
								$response['request3'] = "Year";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'all') || ($master1 == 'all' && $master2 == 'year'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Year";
								$response['request3'] = "All";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'year'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Year";
								$response['request3'] = "Day";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'year' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'year'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Year";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else
							{
								$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
								$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "400";
									$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
						}
						else if($master1 == 'month' || $master2 == 'month')
						{
							if( ($master1 == 'month' && in_array($master2, $tagType)) || ( in_array($master1, $tagType) && $master2 == 'month') )
							{
								if(in_array($master2, $tagType))
								{
									$tagtype = $master2;
									$value = $master1;
								}
								else
								{
									$tagtype = $master1;
									$value = $master2;
								}

								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = $tagtype;
								$response['request3'] = "Month";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'month' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'month'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Month";
								$response['request3'] = "Amount";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;

								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
						   else if(($master1 == 'month' && $master2 == 'all') || ($master1 == 'all' && $master2 == 'month'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Month";
								$response['request3'] = "All";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'month' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'month'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Month";
								$response['request3'] = "Day";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
							else if(($master1 == 'month' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'month'))
							{
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Month";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
								
								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
						}
						else if( ($master1 == 'amount' && in_array($master2, $tagType)) || ( in_array($master1, $tagType) && $master2 == 'amount') )
						{
							if(in_array($master2, $tagType))
							{
								$tagtype = $master2;
								$value = $master1;
							}
							else
							{
								$tagtype = $master1;
								$value = $master2;
							}

							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2, $tagtype);

							//echo $this->db->last_query(); die;

							$response['Secondary_tag'] = $secondaryName;
							$response['request2'] = $tagtype;
							$response['request4'] = array('Amount' => $gettotalentrybyyear->Total_Amount);
							$response['request7'] = $aa;							 					
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if( ($master1 == 'all' && $master2 == 'day') || ($master1 == 'day' && $master2 == 'all'))
						{

								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "All";
								$response['request3'] = "Day";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							

						}
						else if( ($master1 == 'all' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'all'))
						{

								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "All";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}

						}
						else if( ($master1 == 'day' && $master2 == 'count') || ($master1 == 'count' && $master2 == 'day'))
						{

								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								$response['Secondary_tag'] = $secondaryName;
								$response['request2'] = "Day";
								$response['request3'] = "Count";
								$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}

						}
						else
						{
							$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
							$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "400";
								$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

					}

					else if(
						($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'master' ) || 
						($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'secondary tag') || 
						($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'master') || 
						($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'primary tag') || 
						($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'primary tag') || 
						($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'secondary tag'))
					{
						if( $data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'master' )
						{
							$primaryTag= $data->column1->Id;
							$secondaryTag = $data->column2->Id;
							$master = $data->column3->Id;
						}
						else if($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'secondary tag')
						{
							$primaryTag = $data->column1->Id;
							$master = $data->column2->Id;
							$secondaryTag = $data->column3->Id;
						}
						else if($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'master')
						{
							$secondaryTag = $data->column1->Id;
							$primaryTag = $data->column2->Id;
							$master = $data->column3->Id;
						}
						else if($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'primary tag')
						{
							$secondaryTag = $data->column1->Id;
							$master = $data->column2->Id;
							$primaryTag = $data->column3->Id;
						}
						else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'primary tag')
						{
							$master = $data->column1->Id;
							$secondaryTag = $data->column2->Id;
							$primaryTag = $data->column3->Id;
						}
						else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'secondary tag')
						{
							$master = $data->column1->Id;
							$primaryTag = $data->column2->Id;
							$secondaryTag = $data->column3->Id;
						}
						else
						{
							$master = $data->column1->Id;
							$primaryTag = $data->column2->Id;
							$secondaryTag = $data->column3->Id;
						}

						if($master == 'year')
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);						 					
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;
							$response['request3'] = "Year";
							$response['request4'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $master, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if($master == 'month')
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;
							$response['request3'] = "Month";
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $master, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if($master == 'cash+' || $master == 'cash-' || $master == 'bank+' || $master == 'bank-' || $master == 'other')
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);

							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_tagtype($customerid, $tableTag, $master, $primaryTag, $secondaryTag);
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;
							$response['request4'] = array($master);
							$response['request7'] = $aa;						 						
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
							//echo $view; die;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if($master == 'amount')
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);								 				
							$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_year2($customerid, $tableTag,$primaryTag, $secondaryTag);
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;
							$response['request4'] =	 array('Amount' => $gettotaldata->Total_Amount);						 						
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if($master == 'all')
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;
							$response['request3'] = "All";
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $master, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if($master == 'day')
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;
							$response['request3'] = "Month";
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $master, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else if($master == 'count')
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;
							$response['request3'] = "Month";
							$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $master, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}
						else
						{
							$response['errormessage'] = "You need to optimize your query.";
							$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "400";
								$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}


					}
					else if(($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master' ) )
					{
						$tagType = array("cash+","cash-","bank+", "bank-","other");

						if(in_array($data->column1->Id, $tagType))
						{
							$tag = $data->column1->Id;
						}
						else if(in_array($data->column2->Id, $tagType))
						{
							$tag = $data->column2->Id;
						}
						else
						{
							$tag = $data->column3->Id;
						}



						if((in_array($data->column1->Id, $tagType) && $data->column2->Id == 'year' && $data->column3->Id == 'month') || 
						   (in_array($data->column1->Id, $tagType) && $data->column2->Id == 'month' && $data->column3->Id == 'year') || 
						   ($data->column1->Id == 'month' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'year') || 
						   ($data->column1->Id == 'month' && $data->column2->Id == 'year' && in_array($data->column3->Id, $tagType)) || 
						   ($data->column1->Id == 'year' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'month') || 
						   ($data->column1->Id == 'year' && $data->column2->Id == 'month' && in_array($data->column3->Id, $tagType)) )
						{

							$master1 = 'year';
							$master2 = 'month';
					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid,$tag);

							$response['request1'] = $tag;
					 		$response['request2'] = "Year";
					 		$response['request3'] = "Month";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $tag);
					 		//echo $this->db->last_query(); die;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {

								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}
						else if((in_array($data->column1->Id, $tagType) && $data->column2->Id == 'amount' && $data->column3->Id == 'year') || 
								(in_array($data->column1->Id, $tagType) && $data->column2->Id == 'year' && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'year' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'amount' && in_array($data->column3->Id, $tagType)) || 
								($data->column1->Id == 'amount' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'year') || 
								($data->column1->Id == 'amount' && $data->column2->Id == 'year' && in_array($data->column3->Id, $tagType)) )
						{
							
							$master1 = 'year';
							$master2 = 'amount';
					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid,$tag);

							$response['request1'] = $tag;
					 		$response['request2'] = "Year";
					 		$response['request3'] = "Amount";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $tag);
					 		//echo $this->db->last_query(); die;
							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);
							
							//echo $view; die;

							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}
						else if((in_array($data->column1->Id, $tagType) && $data->column2->Id == 'amount' && $data->column3->Id == 'month') || 
								(in_array($data->column1->Id, $tagType) && $data->column2->Id == 'month' && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'month' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'amount' && in_array($data->column3->Id, $tagType)) || 
								($data->column1->Id == 'amount' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'month') || 
								($data->column1->Id == 'amount' && $data->column2->Id == 'month' && in_array($data->column3->Id, $tagType)) )
						{
							
							$master1 = 'month';
							$master2 = 'amount';
					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid,$tag);
					 		
							$response['request1'] = $tag;
					 		$response['request2'] = "Month";
					 		$response['request3'] = "Amount";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $tag);
					 		//echo $this->db->last_query(); die;
					 		$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}
						else if( ($data->column1->Id == 'year' && $data->column2->Id == 'amount' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'month' && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'year' && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'amount' && $data->column3->Id == 'year') || 
								($data->column1->Id == 'amount' && $data->column2->Id == 'year' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'amount' && $data->column2->Id == 'month' && $data->column3->Id == 'year') )
						{

							$master1 = 'year';
							$master2 = 'month';
							$master3 = 'amount';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Year";
					 		$response['request2'] = "Month";
					 		$response['request3'] = "Amount";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( ($data->column1->Id == 'year' && $data->column2->Id == 'all' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'day' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'year' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'all' && $data->column3->Id == 'year') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'year' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'day' && $data->column3->Id == 'year') )
						{
							//echo "test"; die;
							$master1 = 'year';
							$master2 = 'day';
							$master3 = 'all';
							
							

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Year";
					 		$response['request2'] = "Day";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( ($data->column1->Id == 'year' && $data->column2->Id == 'all' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'count' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'year' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'all' && $data->column3->Id == 'year') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'year' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'count' && $data->column3->Id == 'year') )
						{

							$master1 = 'year';
							$master2 = 'count';
							$master3 = 'all';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Year";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( ($data->column1->Id == 'year' && $data->column2->Id == 'day' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'count' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'year' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'day' && $data->column3->Id == 'year') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'year' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'count' && $data->column3->Id == 'year') )
						{

							$master1 = 'year';
							$master2 = 'count';
							$master3 = 'day';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Year";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						/* start */

							else if( ($data->column1->Id == 'month' && $data->column2->Id == 'all' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'day' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'month' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'all' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'month' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'day' && $data->column3->Id == 'month') )
						{

							$master1 = 'month';
							$master2 = 'day';
							$master3 = 'all';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Month";
					 		$response['request2'] = "Day";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( ($data->column1->Id == 'month' && $data->column2->Id == 'all' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'count' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'month' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'all' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'month' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'count' && $data->column3->Id == 'month') )
						{

							$master1 = 'month';
							$master2 = 'count';
							$master3 = 'all';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Month";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( ($data->column1->Id == 'month' && $data->column2->Id == 'day' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'count' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'month' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'day' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'month' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'count' && $data->column3->Id == 'month') )
						{

							$master1 = 'month';
							$master2 = 'count';
							$master3 = 'day';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Month";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						/* end */


						/* start Amount Type */

							else if( ($data->column1->Id == 'amount' && $data->column2->Id == 'all' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'amount' && $data->column2->Id == 'day' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'amount' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'all' && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'amount' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'day' && $data->column3->Id == 'amount') )
						{

							$master1 = 'amount';
							$master2 = 'all';
							$master3 = 'day';



					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Amount";
					 		$response['request2'] = "Day";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( ($data->column1->Id == 'amount' && $data->column2->Id == 'all' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'amount' && $data->column2->Id == 'count' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'amount' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'all' && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'amount' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'count' && $data->column3->Id == 'amount') )
						{

							$master1 = 'amount';
							$master2 = 'count';
							$master3 = 'all';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Amount";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "All";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( ($data->column1->Id == 'amount' && $data->column2->Id == 'day' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'amount' && $data->column2->Id == 'count' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'amount' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'day' && $data->column3->Id == 'amount') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'amount' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'count' && $data->column3->Id == 'amount') )
						{

							$master1 = 'amount';
							$master2 = 'count';
							$master3 = 'day';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Amount";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "Day";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}

						else if( (in_array($data->column1->Id, $tagType) && $data->column2->Id == 'day' && $data->column3->Id == 'count') || 
								(in_array($data->column1->Id, $tagType) && $data->column2->Id == 'count' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'day') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'day' && in_array($data->column3->Id, $tagType)) || 
								($data->column1->Id == 'day' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'count') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'count' && in_array($data->column3->Id, $tagType)) )
						{
							
							$master1 = 'day';
							$master2 = 'count';

					 		$response['data'] = $this->Report_Api_Model->get_total_data_by_year_universal($customerid,$tag);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = $tag;
					 		$response['request2'] = "Day";
					 		$response['request3'] = "Count";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $tag);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}
						
						else if( ($data->column1->Id == 'year' && $data->column2->Id == 'month' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'day' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'year' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'day' && $data->column2->Id == 'month' && $data->column3->Id == 'year') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'year' && $data->column3->Id == 'day') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'day' && $data->column3->Id == 'year') )
						{

							$master1 = 'year';
							$master2 = 'month';
							$master3 = 'day';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Amount";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "Day";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}
						
						else if( ($data->column1->Id == 'year' && $data->column2->Id == 'month' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'count' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'year' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'count' && $data->column2->Id == 'month' && $data->column3->Id == 'year') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'year' && $data->column3->Id == 'count') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'count' && $data->column3->Id == 'year') )
						{

							$master1 = 'year';
							$master2 = 'month';
							$master3 = 'count';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Amount";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "Day";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}
						
						else if( ($data->column1->Id == 'year' && $data->column2->Id == 'month' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'year' && $data->column2->Id == 'all' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'year' && $data->column3->Id == 'month') || 
								($data->column1->Id == 'all' && $data->column2->Id == 'month' && $data->column3->Id == 'year') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'year' && $data->column3->Id == 'all') || 
								($data->column1->Id == 'month' && $data->column2->Id == 'all' && $data->column3->Id == 'year') )
						{

							$master1 = 'year';
							$master2 = 'month';
							$master3 = 'all';

					 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
					 		//echo $this->db->last_query(); die;
							$response['request1'] = "Amount";
					 		$response['request2'] = "Count";
					 		$response['request3'] = "Day";
					 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth_universal($customerid, $master1, $master2, $master3);
					 		//echo $this->db->last_query(); die;
							
						   //print_r($response['request4']);

							$response['request7'] = $aa;
							$html['html'] = $view = $this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response, true);

							//echo $view; die;
							if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
								array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }
							
						}
						
						//print_r($aa);


						/* End */ 		 	 
					}
					else
					{

						$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
						$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "400";
							$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}
			 	}
			 	else if($total == 4) // Total column count reportJson Input
			 	{
						
 					if($data->column1->Source_Type == 'image gallery' || $data->column2->Source_Type == 'image gallery' || $data->column3->Source_Type == 'image gallery' || $data->column4->Source_Type == 'image gallery')
 					{
		 				$tableTag = "tbl_tag"; // Used for image gallary / Primary Tag
		 				$tablePrimary = "tbl_primary_tag";
		 				$tableSecondary = "tbl_secondary_tag";
		 			}
		 			else
		 			{
		 				$tableTag = "tbl_doc_tag"; // Used for image gallary / Secondary Tag
		 				$tablePrimary = "tbl_doc_primary_tag";
		 				$tableSecondary = "tbl_doc_secondary_tag";

		 			}

		 			$pt = 'primary tag';
					$st = 'secondary tag';
					$y = 'year';
					$m = 'month';
					$a = 'amount';	
					$al = 'all';
					$da = 'day';
					$co = 'count';				
					$tagtype = array('cash+','cash-','bank+','bank-','other');
					$primary = '';
					$secondary = '';
					$year = '';
					$month = '';
					$amount = '';
					$tag = '';
					$all = '';
					$day = '';
					$count = '';					


					
					foreach($data as $key => $value) {
						if ($pt == $value->Tag_Type) {
							 $primary = $data->$key->Id;
							
						}
						if ($st == $value->Tag_Type) {
							$secondary = $data->$key->Id;
							
						}
						if ($y == $value->Id) {
							 $year = $value->Id;
							
						}
						if ($m == $value->Id) {
							$month = $value->Id;
							
						}
						if (in_array($value->Id, $tagtype)) {
							 $tag = $value->Id;
							
						}
						if ($a == $value->Id) {
							$amount = $value->Id;
							
						}

						if ($al == $value->Id) {
							$all = $value->Id;
							
						}

						if ($da == $value->Id) {
							$day = $value->Id;
							
						}

						if ($co == $value->Id) {
							$count = $value->Id;
							
						}
						
					}
					
					//$data1 = ksort($data);
					
					//print_r($data1);
					
					//die;
					
					foreach($data as $key => $value ) {
							if(is_numeric($value->Id))
							$data1[$key] = $value->Tag_Type;
						else
							$data1[$key] = $value->Id;
						
					}
					ksort($data1);
					
					

					foreach($data1 as $key => $value) {
						
						if(is_numeric($value))
							$aa[] = $value;
						else
							$aa[] = $value;
					}
					
					if($primary != '' && $secondary != '' &&  $year != '' &&  $tag != '')
					{
						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = $tag;
						$response['request4'] = "Year";					 					
						$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						$response['request7'] = $aa;

						$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

						//echo $view; die;

						if($returnstatus == 1) {
							return $view;
						} else {
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}

					}
					else if($primary != '' && $secondary != '' &&  $year != '' &&  $month != '')
					{
						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);

						//echo $this->db->last_query(); die;
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = "Year";
						$response['request4'] = "Month";					 					
						$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						$response['request7'] = $aa;
						//echo $this->db->last_query(); die;
						$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}

					else if(

								( ($primary != '' && $secondary != '' &&  $year != '' &&  $all != '') || ($primary != '' && $secondary != '' &&  $month != '' &&  $all != '') || ($primary != '' && $secondary != '' &&  $amount != '' &&  $all != '') ) ||
								( ($primary != '' && $secondary != '' &&  $year != '' &&  $day != '') || ($primary != '' && $secondary != '' &&  $month != '' &&  $day != '') || ($primary != '' && $secondary != '' &&  $amount != '' &&  $day != '') ) ||
								( ($primary != '' && $secondary != '' &&  $year != '' &&  $count != '') || ($primary != '' && $secondary != '' &&  $month != '' &&  $count != '') || ($primary != '' && $secondary != '' &&  $amount != '' &&  $count != '') ) 

							)
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);

							//echo $this->db->last_query(); die;
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;

							if($year != '')
							{
								$response['request3'] = "Year";

							}
							if($month != '')
							{
								$response['request3'] = "Month";
								
							}

							if($amount != '')
							{
								$response['request3'] = "Amount";
								
							}
							

							if($all != '')
							{
								$response['request4'] = "All";
							}

							if($day != '')
							{
								$response['request4'] = "Day";
							}

							if($count != '')
							{
								$response['request4'] = "Count";
							}


							$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
							$response['request7'] = $aa;
							//echo $this->db->last_query(); die;
							$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

							//echo $view; die;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

						else if(

								( ($primary != '' && $secondary != '' &&  $all != '' &&  $day != '') ) ||
								( ($primary != '' && $secondary != '' &&  $all != '' &&  $count != '') ) ||
								( ($primary != '' && $secondary != '' &&  $day != '' &&  $count != '') ) 

							)
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);

							//echo $this->db->last_query(); die;
							$response['Primary_tag'] = $primeryName;
							$response['Secondary_tag'] = $secondaryName;

														

							if($all != '')
							{
								$response['request3'] = "All";

							}

							if($day != '')
							{
								$response['request3'] = "Day";
								$response['request4'] = "Day";
							}

							if($count != '')
							{
								$response['request4'] = "Count";
							}


							$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
							$response['request7'] = $aa;
							//echo $this->db->last_query(); die;
							$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

							//echo $view; die;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

					else if(

								( ($primary != '' && $year != '' &&  $all != '' &&  $day != '') || ($secondary != '' && $year != '' &&  $all != '' &&  $day != '') ) ||
								( ($primary != '' && $year != '' &&  $all != '' &&  $count != '')  || ($secondary != '' && $year != '' &&  $all != '' &&  $count != '')) ||
								( ($primary != '' && $year != '' &&  $day != '' &&  $count != '') || ($secondary != '' && $year != '' &&  $day != '' &&  $count != '') ) ||
								( ($primary != '' && $year != '' &&  $month != '' &&  $all != '') || ($secondary != '' && $year != '' &&  $month != '' &&  $all != '') ) ||
								( ($primary != '' && $year != '' &&  $month != '' &&  $day != '') || ($secondary != '' && $year != '' &&  $month != '' &&  $day != '')) ||
								( ($primary != '' && $year != '' &&  $month != '' &&  $count != '') || ($secondary != '' && $year != '' &&  $month != '' &&  $count != '') ) ||
								( ($primary != '' && $year != '' &&  $amount != '' &&  $all != '') || ($secondary != '' && $year != '' &&  $amount != '' &&  $all != '') ) ||
								( ($primary != '' && $year != '' &&  $amount != '' &&  $day != '') || ($secondary != '' && $year != '' &&  $amount != '' &&  $day != '') ) ||
								( ($primary != '' && $year != '' &&  $amount != '' &&  $count != '') || ($secondary != '' && $year != '' &&  $amount != '' &&  $count != '') ) ||
								( ($primary != '' && $year != '' &&  $day != '' &&  $count != '') || ($secondary != '' && $year != '' &&  $day != '' &&  $count != '') ) ||
								( ($primary != '' && $month != '' &&  $all != '' &&  $day != '') || ($secondary != '' && $month != '' &&  $all != '' &&  $day != '') ) ||
								( ($primary != '' && $month != '' &&  $all != '' &&  $count != '') || ($secondary != '' && $month != '' &&  $all != '' &&  $count != '') ) ||
								( ($primary != '' && $month != '' &&  $day != '' &&  $count != '') || ($secondary != '' && $month != '' &&  $day != '' &&  $count != '') ) ||
								( ($primary != '' && $month != '' &&  $amount != '' &&  $all != '') || ($secondary != '' && $month != '' &&  $amount != '' &&  $all != '') ) ||
								( ($primary != '' && $month != '' &&  $amount != '' &&  $day != '') || ($secondary != '' && $month != '' &&  $amount != '' &&  $day != '') ) ||
								( ($primary != '' && $month != '' &&  $amount != '' &&  $count != '') || ($secondary != '' && $month != '' &&  $amount != '' &&  $count != '') ) ||
								( ($primary != '' && $all != '' &&  $day != '' &&  $count != '') || ($secondary != '' && $all != '' &&  $day != '' &&  $count != '') )

							)
						{
							$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
							$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);

							//echo $this->db->last_query(); die;
							if($primary != '')
							{
								$response['Primary_tag'] = $primeryName;

							}
							else
							{
								$response['Secondary_tag'] = $secondaryName;

							}

							if($year != '')
							{
								$response['request2'] = 'Year';

							}
							if($month != '')
							{
								$response['request2'] = 'Month';
								$response['request3'] = "Month";

							}

							if($amount != '')
							{								
								$response['request3'] = "Amount";
							}

							if($all != '')
							{								
								$response['request3'] = "All";
								$response['request4'] = "All";
							}

							if($day != '')
							{								
								$response['request3'] = "Day";
								$response['request4'] = "Day";
							}

							if($count != '')
							{	
								$response['request4'] = "Count";
							}


							$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
							$response['request7'] = $aa;
							//echo $this->db->last_query(); die;
							$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

							//echo $view; die;

							if($returnstatus == 1)
							{
								return $view;
							}
							else
							{
								array_push($htmldata ,$html);					 			
								$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							}
						}

					else if($primary != '' && $secondary != '' &&  $year != '' &&  $amount != '')
					{
						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);

						//echo $this->db->last_query(); die;
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = "Year";
						$response['request4'] = "Amount";					 					
						$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						$response['request7'] = $aa;
						//echo $this->db->last_query(); die;
						$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}
				   else if($primary != '' && $secondary != '' &&  $month != '' &&  $amount != '')
				   {
				   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
				 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
		 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);
		 					
		 					//echo $this->db->last_query(); die;
		 					$response['Primary_tag'] = $primeryName;
		 					$response['Secondary_tag'] = $secondaryName;
		 					$response['request3'] = "Month";
		 					$response['request4'] = "Amount";					 					
		 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
		 					$response['request7'] = $aa;
		 					//echo $this->db->last_query(); die;
		 					$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

		 					//echo $view; die;

		 					if($returnstatus == 1)
						     {
							    return $view;
						     }
						    else
						     {
			 					array_push($htmldata ,$html);					 			
					 			$responseapp['ResponseCode'] = "200";
								$responseapp['ResponseMessage'] = 'Report fetched successfully.';
								$responseapp['Payload'] = $htmldata;
								$this->response($responseapp);
							 }

				    }
				    else if($primary != '' && $secondary != ''  &&  $month != '' &&  $tag != '')
				    {
						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);

						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = $tag;
						$response['request4'] = "Month";					 					
						$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						$response['request7'] = $aa;
						//echo $this->db->last_query(); die;
						//print_r($response); die; 
						$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
				    }
				    else if($primary != '' && $secondary != ''  &&  $amount != '' &&  $tag != '')
				    {

			   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
			 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
	 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
	 					$response['Primary_tag'] = $primeryName;
	 					$response['Secondary_tag'] = $secondaryName;
	 					$response['request3'] = $tag;
	 					$response['request4'] = "Amount";
	 					$response['request5'] = array('Amount' => $gettotalentrybyyear->Total_Amount);
	 					$response['request7'] = $aa;

	 					 				 					
	 					//$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
	 					//echo $this->db->last_query(); die; 
	 					$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

	 				   	//echo $view; die;

	 					if($returnstatus == 1)
					     {
						    return $view;
					     }
					    else
					     {
		 					array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						 }

				   	}
				   	else if($primary != '' && $year != ''  &&  $month != '' &&  $amount != '')
				   	{
			   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
			 			
	 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);
	 									 					
	 					$response['Primary_tag'] = $primeryName;
	 					$response['request2'] = "Year";
	 					$response['request3'] = "Month";
	 					$response['request4'] = "Amount";					 					
	 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
	 					//echo $this->db->last_query(); die;
	 					$response['request7'] = $aa;
	 					$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

	 					//print_r($aa);
	 					//print_r($response);  
	 					//echo $view; die;

	 					if($returnstatus == 1)
					     {
						    return $view;
					     }
					    else
					     {

		 					array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						 }
				   	}
				   	else if($primary != '' && $year != ''  &&  $month != '' &&  $tag != '')
				   	{
			   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
			 									 					
	 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
	 					//echo $this->db->last_query(); die; 
	 					$response['Primary_tag'] = $primeryName;
	 					$response['request2'] = $tag;
	 					$response['request3'] = "Year";
	 					$response['request4'] = "Month";					 					
	 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
	 					//echo $this->db->last_query(); die; 
	 					$response['request7'] = $aa;
	 					$html['html'] =  $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

	 					if($returnstatus == 1)
					    {
						    return $view;
					    }
					    else
					     {
		 					array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						 }
				   	}
				   	else if($primary != '' && $year != ''  &&  $amount != '' &&  $tag != '')
				   	{
			   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
			 									 					
	 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
	 					//echo $this->db->last_query(); die; 
	 					$response['Primary_tag'] = $primeryName;
	 					$response['request2'] = $tag;
	 					$response['request3'] = "Year";
	 					$response['request4'] = "Amount";					 					
	 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
	 					//echo $this->db->last_query(); die;
	 					$response['request7'] = $aa; 
	 					$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

	 					//echo $view; die;

	 					if($returnstatus == 1)
					    {
						    return $view;
					    }
					    else
					    {
		 					array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						 }
				   	}
				    else if($primary != '' && $month != ''  &&  $amount != '' &&  $tag != '')
				    {
			   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
			 									 					
	 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
	 					//echo $this->db->last_query(); die; 
	 					$response['Primary_tag'] = $primeryName;
	 					$response['request2'] = $tag;
	 					$response['request3'] = "Month";
	 					$response['request4'] = "Amount";					 					
	 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
	 					//echo $this->db->last_query(); die; 
	 					$response['request7'] = $aa;
	 					$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

	 					//echo $view; die;

	 					if($returnstatus == 1)
					    {
						    return $view;
					    }
					    else
					    {
		 					array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
				    }
				    else if($secondary != '' && $year != ''  &&  $month != '' &&  $tag != '')
				    {
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);
	 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
	 					//echo $this->db->last_query(); die; 
	 					$response['Secondary_tag'] = $secondaryName;
	 					$response['request2'] = $tag;
	 					$response['request3'] = "Year";
	 					$response['request4'] = "Month";					 					
	 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
	 					//echo $this->db->last_query(); die;
	 					$response['request7'] = $aa;

	 					$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

	 					//echo $view; die;

	 					if($returnstatus == 1)
					     {
						    return $view;
					     }
					    else
					     {

		 					array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						 }
				   	}
				   	else if($secondary != '' && $year != ''  &&  $amount != '' &&  $tag != '')
				   	{
				   			
			   			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
			 									 					
	 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
	 					//echo $this->db->last_query(); die; 
	 					$response['Secondary_tag'] = $secondaryName;
	 					$response['request2'] = $tag;
	 					$response['request3'] = "Year";
	 					$response['request4'] = "Amount";					 					
	 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
	 					//echo $this->db->last_query(); die;

	 					$response['request7'] = $aa;

	 					$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

	 					//echo $view; die;

						if($returnstatus == 1)
					     {
						    return $view;
					     }
					    else
					     {

		 					array_push($htmldata ,$html);					 			
				 			$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						 }
				   	}
					else if($secondary != '' && $month != ''  &&  $amount != '' &&  $tag != '')
					{

						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					

						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						//echo $this->db->last_query(); die; 
						$response['Secondary_tag'] = $secondaryName;
						$response['request2'] = $tag;
						$response['request3'] = "Month";
						$response['request4'] = "Amount";					 					
						$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						//echo $this->db->last_query(); die;
						$response['request7'] = $aa; 
						$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}
					else if($year != '' && $month != ''  &&  $amount != '' &&  $tag != '')
					{
						$master1 = 'year';
						$master2 = 'month';
						$master3 = 'amount';

						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tag);
						$response['request1'] = $tag;
						$response['request2'] = "Year";
						$response['request3'] = "Month";
						$response['request4'] = "Amount";
						$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_yearmonth_universal($customerid, $master1, $master2, $master3, $tag);
						

						$response['request7'] = $aa;

						$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}

					else if(
								($year != '' && $month != ''  &&  $all != '' &&  $day != '') ||
								($year != '' && $month != ''  &&  $all != '' &&  $count != '') ||
								($year != '' && $month != ''  &&  $day != '' &&  $count != '') ||
								($year != '' && $amount != ''  &&  $all != '' &&  $day != '') ||
								($year != '' && $amount != ''  &&  $all != '' &&  $count != '') ||
								($year != '' && $amount != ''  &&  $day != '' &&  $count != '') ||
								($month != '' && $amount != ''  &&  $all != '' &&  $day != '') ||
								($month != '' && $amount != ''  &&  $all != '' &&  $count != '') ||
								($month != '' && $amount != ''  &&  $day != '' &&  $count != '') ||
								($year != '' && $all != ''  &&  $day != '' &&  $count != '') ||
								($month != '' && $all != ''  &&  $day != '' &&  $count != '') ||
								($amount != '' && $all != ''  &&  $day != '' &&  $count != '') 

							)
							{
								

								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
								
								$response['request1'] = $tag;

								$response['request2'] = "Year";

								$response['request3'] = "Month";

								$response['request4'] = "Amount";

								$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_yearmonth_universal_new($customerid, $all, $day, $count, $year, $month, $amount);
								

								$response['request7'] = $aa;

								$html['html'] = $view = $this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response, true);

								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
					else
					{
						$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
						$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "400";
							$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}
			 	}
			 	else if($total == 5) // Total column count reportJson Input
			 	{
 					if(($data->column1->Source_Type == 'image gallery') || ($data->column2->Source_Type == 'image gallery') || 
					   ($data->column3->Source_Type == 'image gallery') || ($data->column4->Source_Type == 'image gallery') || 
					   ($data->column5->Source_Type == 'image gallery'))
 					{
		 				$tableTag = "tbl_tag";
		 				$tablePrimary = "tbl_primary_tag";
		 				$tableSecondary = "tbl_secondary_tag";
		 			}
		 			else
		 			{
		 				$tableTag = "tbl_doc_tag";
		 				$tablePrimary = "tbl_doc_primary_tag";
		 				$tableSecondary = "tbl_doc_secondary_tag";
		 			}

					$pt = 'primary tag';
					$st = 'secondary tag';
					$y = 'year';
					$m = 'month';
					$a = 'amount';
					$al = 'all';
					$da = 'day';
					$co = 'count';
					$tagtype = array('cash+','cash-','bank+','bank-','other');
					$primary = '';
					$secondary = '';
					$year = '';
					$month = '';
					$amount = '';
					$tag = '';
					$all = '';
					$day = '';
					$count = '';					
					
					
					foreach($data as $key => $value) {
						
						if ($pt == $value->Tag_Type) {
							 $primary = $data->$key->Id;
							
						}
						if ($st == $value->Tag_Type) {
							$secondary = $data->$key->Id;
							
						}
						if ($y == $value->Id) {
							 $year = $value->Id;
							
						}
						if ($m == $value->Id) {
							$month = $value->Id;
							
						}
						if (in_array($value->Id, $tagtype)) {
							 $tag = $value->Id;
							
						}
						if ($a == $value->Id) {
							$amount = $value->Id;
							
						}
						if ($al == $value->Id) {
							$all = $value->Id;
							
						}

						if ($da == $value->Id) {
							$day = $value->Id;
							
						}

						if ($co == $value->Id) {
							$count = $value->Id;
							
						}
						
					}
					
					foreach($data as $key => $value ) {
							if(is_numeric($value->Id))
							$data1[$key] = $value->Tag_Type;
						else
							$data1[$key] = $value->Id;
						
					}
					ksort($data1);
					
					

					foreach($data1 as $key => $value) {
						
						if(is_numeric($value))
							$aa[] = $value;
						else
							$aa[] = $value;
					}


					if( $primary != '' && $secondary != '' &&  $year != '' && $month != '' &&  $amount != '')
					{

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = "Year";
						$response['request4'] = "Month";
						$response['request5'] = "Amount";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						$response['request7'] = $aa;
						//print_r($response);
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);
						//echo $view; die;
						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}


					}
					else if( $primary != '' && $secondary != '' && $month != '' &&  $amount != '' && $tag != '')
					{
						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = $tag;
						$response['request4'] = "Month";
						$response['request5'] = "Amount";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

						//print_r($response);

						//echo $view; die; 

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}
					else if( $primary != '' && $secondary != '' && $year != '' &&  $amount != '' && $tag != '')
					{
						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = $tag;
						$response['request4'] = "Year";
						$response['request5'] = "Amount";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

						//print_r($response);

						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}

					else if( $primary != '' && $secondary != '' && $year != '' &&  $month != '' && $tag != '')
					{

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);


						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = $tag;
						$response['request4'] = "Year";
						$response['request5'] = "Month";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						//echo $this->db->last_query(); die;
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

						//print_r($response);
						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}

					}
					else if( $primary != '' && $secondary != '' &&  $all != '' && $day != '' &&  $count != '')
					{

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = "All";
						$response['request4'] = "Day";
						$response['request5'] = "Count";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
						$response['request7'] = $aa;
						//print_r($response);
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);
						//echo $view; die;
						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}


					}
					else if(				
							($primary != '' && $secondary != '' &&  $all != '' && $day != '' &&  $year != '') ||							
							($primary != '' && $secondary != '' &&  $all != '' && $day != '' &&  $month != '') ||							
							($primary != '' && $secondary != '' &&  $all != '' && $day != '' &&  $amount != '')						
						   )
					{

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						
						$response['request3'] = "All";
						$response['request4'] = "Day";
						if($year != '')
						{
							$response['request5'] = "Year";
						}
						
						if($month != '')
						{
							$response['request5'] = "Month";
						}
						
						if($amount != '')
						{
							$response['request5'] = "Amount";
						}
						
						
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
						$response['request7'] = $aa;
						//print_r($response);
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);
						//echo $view; die;
						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}


					}
					else if( 
					
								($primary != '' && $secondary != '' &&  $all != '' && $count != '' &&  $year != '') ||
								($primary != '' && $secondary != '' &&  $all != '' && $count != '' &&  $month != '') ||
								($primary != '' && $secondary != '' &&  $all != '' && $count != '' &&  $amount != '')
								
							)
					{

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = "All";
						$response['request4'] = "Count";
						
						if($year != '')
						{
							$response['request5'] = "Year";
						}
						
						if($month != '')
						{
							$response['request5'] = "Month";
						}
						
						if($amount != '')
						{
							$response['request5'] = "Amount";
						}
						
						
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
						$response['request7'] = $aa;
						//print_r($response);
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);
						//echo $view; die;
						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}


					}
					else if( 
					
								($primary != '' && $secondary != '' &&  $day != '' && $count != '' &&  $year != '') ||
								($primary != '' && $secondary != '' &&  $day != '' && $count != '' &&  $month != '') ||
								($primary != '' && $secondary != '' &&  $day != '' && $count != '' &&  $amount != '') 
								
							)
					{

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primary, $secondary);
						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = "Day";
						$response['request4'] = "Count";
						
						if($year != '')
						{
							$response['request5'] = "Year";
						}
						
						if($month != '')
						{
							$response['request5'] = "Month";
						}
						
						if($amount != '')
						{
							$response['request5'] = "Amount";
						}
						
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
						$response['request7'] = $aa;
						//print_r($response);
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);
						//echo $view; die;
						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}


					}

					else if($primary != '' && $secondary != '' &&  $day != '' && $count != '' &&  $tag != '')
					{

						//echo $tag; die;

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);

						//echo $this->db->last_query(); die;


						$response['Primary_tag'] = $primeryName;
						$response['Secondary_tag'] = $secondaryName;
						$response['request3'] = $tag;
						$response['request4'] = "All";
						$response['request5'] = "Day";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count );
						//echo $this->db->last_query(); die;
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

						//print_r($response);
						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}


					}

					else if(

								( ($primary != '' && $year != '' &&  $day != '' && $count != '' &&  $tag != '') || ($secondary != '' && $year != '' &&  $day != '' && $count != '' &&  $tag != '') ) ||

								( ($primary != '' && $month != '' &&  $day != '' && $count != '' &&  $tag != '') || ($secondary != '' && $month != '' &&  $day != '' && $count != '' &&  $tag != '') ) ||

								( ($primary != '' && $amount != '' &&  $day != '' && $count != '' &&  $tag != '') || ($secondary != '' && $amount != '' &&  $day != '' && $count != '' &&  $tag != '') )

						)
					{

						//echo $tag; die;

						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);

						//echo $this->db->last_query(); die;

						if($primary != '')
						{
							$response['Primary_tag'] = $primeryName;

						}

						if($secondary != '')
						{
							$response['Secondary_tag'] = $secondaryName;
							
						}
						
						
						$response['request3'] = $tag;
						$response['request4'] = "All";
						$response['request5'] = "Day";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count );
						//echo $this->db->last_query(); die;
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

						//print_r($response);
						//echo $view; die;

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}


					}
					
					else if(
								( ($primary != ''  && $year != '' &&  $all != '' && $day != '' && $count != '') ||($secondary != ''  && $year != '' &&  $all != '' && $day != '' && $count != '')  )||
								( ($primary != ''  && $year != '' &&  $month != '' && $all != '' && $day != '') ||($secondary != ''  && $year != '' &&  $month != '' && $all != '' && $day != '')  )||
								( ($primary != ''  && $year != '' &&  $month != '' && $all != '' && $count != '') ||($secondary != ''  && $year != '' && $month != '' && $all != '' && $count != '')  )||
								( ($primary != ''  && $year != '' &&  $month != '' && $day != '' && $count != '') ||($secondary != ''  && $year != '' && $month != '' && $day != '' && $count != '')  )||
								( ($primary != ''  && $year != '' &&  $amount != '' && $all != '' && $day != '') ||($secondary != ''  && $year != '' &&  $amount != '' && $all != '' && $day != '')  )||
								( ($primary != ''  && $year != '' &&  $amount != '' && $all != '' && $count != '') ||($secondary != ''  && $year != '' && $amount != '' && $all != '' && $count != '')  )||
								( ($primary != ''  && $year != '' &&  $amount != '' && $day != '' && $count != '') ||($secondary != ''  && $year != '' && $amount != '' && $day != '' && $count != '')  )||
								( ($primary != ''  && $month != '' &&  $amount != '' && $all != '' && $day != '') ||($secondary != ''  && $month != '' &&  $amount != '' && $all != '' && $day != '')  )||
								( ($primary != ''  && $month != '' &&  $amount != '' && $all != '' && $count != '') ||($secondary != ''  && $month != '' && $amount != '' && $all != '' && $count != '')  )||
								( ($primary != ''  && $month != '' &&  $amount != '' && $day != '' && $count != '') ||($secondary != ''  && $month != '' && $amount != '' && $day != '' && $count != '')  )||
								( ($primary != ''  && $month != '' &&  $all != '' && $day != '' && $count != '') ||($secondary != ''  && $month != '' &&  $all != '' && $day != '' && $count != '')  )||
								( ($primary != ''  && $amount != '' &&  $all != '' && $day != '' && $count != '') ||($secondary != ''  && $amount != '' &&  $all != '' && $day != '' && $count != '')  )
							)
							{
								$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
								$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);
								
								//echo $this->db->last_query(); die;
								if($primary != '')
								{
									$response['Primary_tag'] = $primeryName;
								}
								else
								{
									$response['Secondary_tag'] = $secondaryName;
									
								}
								
								if($year != '')
								{
									$response['request2'] = ucfirst($year);
									
								}
								
								if($month != '')
								{
									$response['request2'] = ucfirst($month);
									$response['request3'] = ucfirst($month);
									
								}
								
								if($amount != '')
								{
									$response['request2'] = ucfirst($amount);
									$response['request3'] = ucfirst($amount);
									
								}
								if($all != '')
								{
								  $response['request3'] = ucfirst($all);
								  $response['request4'] = ucfirst($all);
									
								}
								if($day != '')
								{								  
								  $response['request4'] = ucfirst($day);
								  $response['request5'] = ucfirst($day);
									
								}
								
								if($count != '')
								{ 
								  $response['request5'] = ucfirst($count);
									
								}								
								
								$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag, $all, $day, $count);
								//echo $this->db->last_query(); 
								$response['request7'] = $aa;
								$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);
								//print_r($response); 
								//echo $view; die;
								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
								
							}
					else if( $primary != ''  && $year != '' &&  $month != '' && $tag != '' && $amount != '')
					{
						
						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);

						$response['Primary_tag'] = $primeryName;
						$response['request2'] = $tag;
						$response['request3'] = "Year";
						$response['request4'] = "Month";
						$response['request5'] = "Amount";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						//echo $this->db->last_query(); die;
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

						//echo $view; die;
						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}
					else if( $secondary != ''  && $year != '' &&  $month != '' && $tag != '' && $amount != '')
					{
						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);
						//echo $this->db->last_query(); die;						 					
						$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);

						$response['Secondary_tag'] = $secondaryName;
						$response['request2'] = $tag;
						$response['request3'] = "Year";
						$response['request4'] = "Month";
						$response['request5'] = "Amount";
						$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						//echo $this->db->last_query(); die;
						$response['request7'] = $aa;
						$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

						//echo $view; die; 

						if($returnstatus == 1)
						{
							return $view;
						}
						else
						{
							array_push($htmldata ,$html);					 			
							$responseapp['ResponseCode'] = "200";
							$responseapp['ResponseMessage'] = 'Report fetched successfully.';
							$responseapp['Payload'] = $htmldata;
							$this->response($responseapp);
						}
					}

					else if(
								($year != '' && $month != ''  &&  $all != '' &&  $day != '' && $count != '') ||
								($year != '' && $month != ''  &&  $all != '' &&  $day != '' && $amount != '') ||
								($year != '' && $month != ''  &&  $all != '' &&  $count != '' && $amount != '') ||
								($year != '' && $month != ''  &&  $day != '' &&  $count != '' && $amount != '') ||
								($year != '' && $amount != ''  &&  $all != '' &&  $day != '' && $count != '') ||
								($month != '' && $amount != ''  &&  $all != '' &&  $day != '' && $count != '') 
							)
							{
								

								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid);
								
								$response['request1'] = $tag;

								$response['request2'] = "Year";

								$response['request3'] = "Month";

								$response['request4'] = "Amount";

								$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_yearmonth_universal_new($customerid, $all, $day, $count, $year, $month, $amount);
								
								$response['request7'] = $aa;

								$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

								//echo $view; die;

								if($returnstatus == 1)
								{
									return $view;
								}
								else
								{
									array_push($htmldata ,$html);					 			
									$responseapp['ResponseCode'] = "200";
									$responseapp['ResponseMessage'] = 'Report fetched successfully.';
									$responseapp['Payload'] = $htmldata;
									$this->response($responseapp);
								}
							}
					  else if(
								($year != '' && $month != ''  &&  $tag != '' &&  $day != '' && $count != '') ||
								($year != '' && $month != ''  &&  $tag != '' &&  $amount != '' && $day != '') ||
								($year != '' && $month != ''  &&  $amount != '' &&  $tag != '' && $count != '') ||
								($year != '' && $amount != ''  &&  $tag != '' &&  $count != '' && $day != '') ||
								($month != '' && $amount != ''  &&  $tag != '' &&  $count != '' && $day != '')
							)
							{
								
														 					
									$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year_universal($customerid, $tag);
									
									$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_yearmonth_universal_tag($customerid, $tag, $all, $day, $count, $year, $month, $amount);
									//echo $this->db->last_query(); die;
									
									$response['request7'] = $aa;
									$html['html'] = $view = $this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response, true);

									//print_r($response);
									//echo $view; die;

									if($returnstatus == 1)
									{
										return $view;
									}
									else
									{
										array_push($htmldata ,$html);					 			
										$responseapp['ResponseCode'] = "200";
										$responseapp['ResponseMessage'] = 'Report fetched successfully.';
										$responseapp['Payload'] = $htmldata;
										$this->response($responseapp);
									}
							}
					else
					{
						$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
						$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

						if($returnstatus == 1)
						{
						return $view;
						}
						else
						{

						array_push($htmldata ,$html);					 			
						$responseapp['ResponseCode'] = "400";
						$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
						$responseapp['Payload'] = $htmldata;
						$this->response($responseapp);
						}
					}
			 	}
			 	else
			 	{
					$response['errormessage'] = "Cannot generate a report for selected tags. Please retry with a different combination.";
					$html['html'] = $view = $this->load->view('reportapi/error-message',$response, true);

					if($returnstatus == 1)
					{
						return $view;
					}
					else
					{
						array_push($htmldata ,$html);					 			
						$responseapp['ResponseCode'] = "400";
						$responseapp['ResponseMessage'] = 'Cannot generate a report for selected tags. Please retry with a different combination.';
						$responseapp['Payload'] = $htmldata;
						$this->response($responseapp);
					}
			 	}
			} 
		}
	}
	else
	{
		$response['errormessage'] = "Authtoken mismatch.";
		$html['html'] = $this->load->view('reportapi/error-message',$response, true);
		array_push($htmldata ,$html);					 			
		$responseapp['ResponseCode'] = "400";
		$responseapp['ResponseMessage'] = 'Authtoken mismatch.';
		$responseapp['Payload'] = $htmldata;
		$this->response($responseapp);

	}
 }

 public function reportdetail_post()
	{

		$customerauthtoken = $this->post('Authtoken');
		$tablecustomer = "tbl_customer";
		$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);
		$payload = array();
		$html = array();
		$htmldata = array();
		$ptag = array();
		$stag = array();
		$master = array();
		$getprimarydata = array();
		$getsecondarydata = array();
		$getmasterdata = array();
		$tableprimetag = "tbl_primary_tag";
		$tabledocprimetag = "tbl_doc_primary_tag";
		$tablesecondtag = "tbl_secondary_tag";
		$tabledocsecondtag = "tbl_doc_secondary_tag";
		if($customerid)
		{
				if (empty($this->post('Id')) || $this->post('Id') == '')
					{
		                $response_data['ResponseCode'] = "400";
					    $response_data['ResponseMessage'] = 'Fields are required.';
					    $this->response($response_data);
					}
					else
					{

						$reportid = $this->post('Id');

						$getReportDetails = $this->Report_Api_Model->get_report_details($customerid, $reportid);

						if($getReportDetails == false)
						{
							$response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'No Record Found.';				
							$response['Payload'] = $htmldata;
							$this->response($response);

						}
						else
						{

								$reportjson = $getReportDetails->reportjson;

								/* Start code */

								$pt = 'primary tag';
								$st = 'secondary tag';
								$ms = 'master';
								
								
								
								$data = json_decode($reportjson);

								foreach($data as $data) {
									
									foreach($data as $key => $value) {
										
										if ($pt == $value->Tag_Type) {
											 $ptag['Id'] = $data->$key->Id;
											 $sourceType = $data->$key->Source_Type;
   											 if($sourceType == 'image gallery')
   											 {
   											 	$pn = $this->Report_Api_Model->get_primetag_name($tableprimetag, $data->$key->Id);
   											 	
   											 	if(empty($pn))
   											 	{
   											 		$ptag['Prime_Name'] = '';

   											 	}
   											 	else
   											 	{
   											 		$ptag['Prime_Name'] = $pn;
   											 	}

   											 }
   											 else{

   											 		$dpn = $this->Report_Api_Model->get_primetag_name($tabledocprimetag, $data->$key->Id); 

   											 		if(empty($dpn))
	   											 	{
	   											 		$ptag['Prime_Name'] = '';

	   											 	}
	   											 	else
	   											 	{
	   											 		$ptag['Prime_Name'] = $dpn;
	   											 	} 
   											 }

   											 array_push($getprimarydata ,$ptag);	
											 
										}

										if ($st == $value->Tag_Type) {
											$stag['Id'] = $data->$key->Id;
											$sourceType = $data->$key->Source_Type;

											if($sourceType == 'image gallery')
   											 {
   											 	$sn = $this->Report_Api_Model->get_secondaryag_name($tablesecondtag, $data->$key->Id); 
   											 	
   											 	if(empty($sn))
   											 	{
   											 		$stag['Secondary_Name'] = '';
   											 	}
   											 	else
   											 	{
   											 		$stag['Secondary_Name'] = $sn;

   											 	}
   											 	
   											 }
   											 else{

   											 	$dsn = $this->Report_Api_Model->get_secondaryag_name($tabledocsecondtag, $data->$key->Id);

	   											 	if(empty($dsn))
	   											 	{
	   											 		$stag['Secondary_Name'] = '';
	   											 	}
	   											 	else
	   											 	{
	   											 		$stag['Secondary_Name'] = $dsn;

	   											 	}   
   											 }

   											 array_push($getsecondarydata ,$stag);	
											
										}
										
										if ($ms == $value->Tag_Type) {

											 $master['Id'] = $value->Id;
											array_push($getmasterdata ,$master);
										}

										
										
										
										
										
									}
								}
								//echo "</pre>";

							    //print_r($getsecondarydata); die;

								/* End code */

							$cid =  $getReportDetails->Customer_Id;

							//$reportjson = $getReportDetails->reportjson;

							$returnstatus = 1;

							

	                        $html['html'] = $reurnhtml = $this->report_post($cid, $reportjson, $returnstatus);

	                        $html['Primary_Tag'] = $getprimarydata;

	                        $html['Secondary_Tag'] = $getsecondarydata;

	                        $html['Master'] = $getmasterdata;

	                        array_push($htmldata ,$html);                        
	                       
	                        $response['ResponseCode'] = "200";
							$response['ResponseMessage'] = 'Details Fetched successfully.';				
							$response['Payload'] = $htmldata;
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
