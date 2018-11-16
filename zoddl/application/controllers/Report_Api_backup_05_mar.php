<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * @package     CodeIgniter
 * @subpackage  Rest Server
 * @category    Document_Api Controller 
 */



class Report_Api extends CI_Controller {

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
	public function report()
	{

		$datainsert = array(
					"data" => json_encode($_POST),
					"datetime" => date("Y-m-d H:i:s"),
					"other" => $_POST["Authtoken"]
			);
		$this->db->insert("tbl_log",$datainsert);
		//die;
			
		/* $reportjson = 	'[{
								"column1":{

											"Source_Type": "image gallery",
											"Tag_Type": "primary tag",			                            
											"Id": "335"
									  },
									  "column2":{

											"Source_Type": "image gallery",
											"Tag_Type": "secondary tag",			                            
											"Id": "165"
									  },
									  "column3":{

											"Source_Type": "image gallery",
											"Tag_Type": "master",			                            
											"Id": "year"
									  },
									  "column4":{

											"Source_Type": "image gallery",
											"Tag_Type": "master",			                            
											"Id": "month"
									  },
									  "column5":{

											"Source_Type": "image gallery",
											"Tag_Type": "master",			                            
											"Id": "amount"
									  }
								
						}]';

		$data = json_decode($reportjson);		 
		$payload = array();
		$customerid = 126;
		$response = array();
		$isPdf = 1; */



		
			$customerid = '';
			$payload = array();
			$response = array();	
			$customerauthtoken = $_POST["Authtoken"];

			//echo "Test".$customerauthtoken; die;
			
			$tablecustomer = "tbl_customer";
			$customerid = $this->Customer_Api_Model->get_customer_id($tablecustomer,$customerauthtoken);

			$data = json_decode($_POST["reportjson"]);
			$savereport = $_POST["savereport"];

			//print_r($_POST); die;

			//echo file_get_contents('php://input'); die;


			//print_r($this->input->post('reportjson')); die;



		if($customerid)
		{
			
			if(empty($data) || $data == '')
			{

				echo '<p style="text-align: center">You need to optimize your query.</p>';
			}
			else
			{
				if($savereport == 1)
				{
					$insertData = array(

								'Customer_Id' => $customerid,									
								'reportjson' => $this->input->post('reportjson'),
								'add_date' => strtotime(date("Y-m-d H:i:s"))
						);

					$insertRec= $this->db->insert('tbl_report',$insertData);
					echo '<p style="text-align: center">Report has been saved successfully.</p>';
					exit();
				}

		foreach($data as $data)
		 {

		 	$total = count((array)$data);

		 	
			 	if($total == 1)
			 	{
			 		
			 		if($data->column1->Source_Type == 'image gallery' || $data->column1->Source_Type == 'document gallery')
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
				 				$response['request'] = $this->Report_Api_Model->get_primaryName($tablePrimary,$value);
					 			$this->load->view('reportapi/column1/report-response-column1-primary-secondary',$response);
					 			
			 			}
			 			else if($data->column1->Tag_Type == 'secondary tag')
			 			{
				 				$gellery_type = strtolower($data->column1->Source_Type);
				 				$type = strtolower($data->column1->Tag_Type);
				 				$value = strtolower($data->column1->Id);
				 				
				 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary);
				 				$response['request'] = $this->Report_Api_Model->get_secondaryName($tableSecondary,$value);
				 				$this->load->view('reportapi/column1/report-response-column1-primary-secondary',$response);
				 				
				 			
			 			}
			 			else if($data->column1->Tag_Type == 'master')
			 			{
			 				$gellery_type = strtolower($data->column1->Source_Type);
			 				$type = strtolower($data->column1->Tag_Type);
			 				$value = strtolower($data->column1->Id);

			 				if($value == 'year')
			 				{

			 					$response['dataentry'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year($customerid,$tableTag);
			 					$response['dataentrytotal'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary);
			 					$response['request'] = $value;
			 					$this->load->view('reportapi/column1/report-response-column1-year-month',$response);

			 				}
			 				else if($value == 'month')
			 				{
			 					$response['dataentry'] = $gettotalentrybymonth = $this->Report_Api_Model->get_total_data_by_year($customerid,$tableTag);
			 					
			 					$response['dataentrytotal'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary);
			 					
			 					$response['request'] = $value;
			 					$this->load->view('reportapi/column1/report-response-column1-year-month',$response);
			 					
			 				}
			 				else if($value == 'amount')
			 				{
			 					$response['data'] = $getAmount = $this->Report_Api_Model->getamount($customerid,$tableTag);
			 					$response['request'] = $value;			 					
			 					$this->load->view('reportapi/column1/report-response-column1',$response);
			 				}
			 				else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
			 				{	
				 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $value, $tableTag, $tablePrimary, $tableSecondary);
				 				$response['request'] = $value;
			 					$this->load->view('reportapi/column1/report-response-column1',$response);
			 				}
			 				else
			 				{
			 					echo '<p style="text-align: center">You need to optimize your query.</p>';
			 				}

			 			}
			 			else
			 			{
			 				echo '<p style="text-align: center">You need to optimize your query.</p>';

			 			}

			 		}
			 		
			 		else
			 		{
			 			echo '<p style="text-align: center">You need to optimize your query.</p>';
			 		}

			 	}
			 	else if($total == 2)
			 	{
			 			if(	($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery') || ($data->column1->Source_Type == 'document gallery' && $data->column2->Source_Type == 'document gallery'))
			 			{
			 					if($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery')
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

			 							  $response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_columen2($customerid, $tableTag, $tablePrimary, $tableSecondary, $primaryTag, $secondaryTag);
			 							  $response['request1'] = $primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							 			  $response['request2'] = $secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);

							 			  $this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response);
							 			  
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
						 					$response['request1'] = $Name = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
						 					$response['request2'] = $value;
						 					$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
						 					$this->load->view('reportapi/column2/report-response-column2-year-month',$response);
   											
						 				}

						 				else if($value == 'month')
							 				{							 					
							 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
						 						$response['request1'] = $Name = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
							 					$response['request2'] = $value;
							 					$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							 					$this->load->view('reportapi/column2/report-response-column2-year-month',$response);
							 					
								 			}
								 		else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
							 				{			 					
								 				
								 				
								 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_tagtype($customerid, $tableTag, $value, $primaryTag, $secondaryTag);
								 				$response['request1'] = $primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								 				$response['request2'] = $value;

								 				$this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response);

							 				}
							 			else if($value == 'amount')
								 			{

								 				$gellery_type = $data->column1->Source_Type;
								 				$type = "primary tag";
								 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $primaryTag, $tableTag, $tablePrimary, $tableSecondary);
								 				$response['request1'] = $primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								 				$response['request2'] = $value;
								 				$this->load->view('reportapi/column2/report-response-column2-primary-secondary-amount',$response);

                                                   
								 			}
						 				else
							 				{
							 					echo '<p style="text-align: center">You need to optimize your query.</p>';
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
							 					$response['request1'] = $Name = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							 					$response['request2'] = $value;
							 					$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							 					$this->load->view('reportapi/column2/report-response-column2-year-month',$response);
   											
							 				}
						 				else if($value == 'month')
							 				{

							 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
						 						$response['request1'] = $Name = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
							 					$response['request2'] = $value;
							 					$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $value, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
							 					$this->load->view('reportapi/column2/report-response-column2-year-month',$response);

								 			}
								 		else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
							 				{			 					
								 				
								 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_tagtype($customerid, $tableTag, $value, $primaryTag, $secondaryTag);
								 				$response['request1'] = $secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
								 				$response['request2'] = $value;
								 				$this->load->view('reportapi/column2/report-response-column2-primary-secondary',$response);


							 				}
							 			 else if($value == 'amount')
								 			{

								 				$gellery_type = $data->column1->Source_Type;								 				
								 				$type = "secondary tag";								 				
								 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $secondaryTag, $tableTag, $tablePrimary, $tableSecondary);
								 				$response['request1'] = $secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
								 				$response['request2'] = $value;
								 				$this->load->view('reportapi/column2/report-response-column2-primary-secondary-amount',$response);

                                                   
								 			}
							 				else
							 				{
							 					echo '<p style="text-align: center">You need to optimize your query.</p>';
							 					

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
				 								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);
							 					$response['request1'] = "Year";
							 					$response['request2'] = "Month";
							 					$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth($customerid, $tableTag, $master1, $master2);
							 					$this->load->view('reportapi/column2/report-response-column2-year-month-amount',$response);	

				 							}
				 							else if( ($master1 == 'year' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'year') )
				 							{
				 								
				 								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);
							 					$response['request1'] = "Year";
							 					$response['request2'] = "Amount";
							 					$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth($customerid, $tableTag, $master1, $master2);
							 					$this->load->view('reportapi/column2/report-response-column2-year-month-amount',$response);	


				 							}
				 							else if( ($master1 == 'month' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'month') )
				 							{
				 								$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag, $secondaryTag);
							 					$response['request1'] = "Month";
							 					$response['request2'] = "Amount";
							 					$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth($customerid, $tableTag, $master1, $master2);
							 					$this->load->view('reportapi/column2/report-response-column2-year-month-amount',$response);

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
			 									$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_monthyeartagtype($customerid,$tableTag,$tagtype);
						 						$response['request1'] = $tagtype;
						 						$response['request2'] = $value;
						 						$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonthtagtype($customerid, $tableTag, $tagtype, $value);
						 						$this->load->view('reportapi/column2/report-response-column2-year-month-amount',$response);


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
				 								
				 									$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_monthyeartagtype($customerid,$tableTag,$tagtype);
							 						$response['request1'] = $tagtype;
							 						$response['request2'] = $value;
							 						$response['request3'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonthtagtype($customerid, $tableTag, $tagtype, $value);
							 						$this->load->view('reportapi/column2/report-response-column2-year-month',$response);
				 								
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
									 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data($customerid, $gellery_type, $type, $tagtype, $tableTag, $tablePrimary, $tableSecondary);
									 				$response['request1'] = $tagtype;
									 				$response['request2'] = "Amount";
									 				$this->load->view('reportapi/column2/report-response-column2-primary-secondary-amount',$response);

				 								
				 							}
				 							else
				 							{
				 								echo '<p style="text-align: center">You need to optimize your query.</p>';
				 							}

				 					}

			 					else
			 						{
			 							echo '<p style="text-align: center">You need to optimize your query.</p>';

			 						}
			 			}			 			
			 			else
			 			{
			 				echo '<p style="text-align: center">You need to optimize your query.</p>';

			 			}

			 	}

			 	else if($total == 3)
			 	{
			 			if(	($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery' && $data->column3->Source_Type == 'image gallery') || ($data->column1->Source_Type == 'document gallery' && $data->column2->Source_Type == 'document gallery' && $data->column3->Source_Type == 'document gallery'))
			 			{
			 					if($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery')
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

							 	if(($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master' ) || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master'))
							 	{
							 		if($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master')
							 		{
							 			$primaryTag = $data->column1->Id;
                                        $master1 = $data->column2->Id;
                                        $master2 = $data->column3->Id;
                                        $secondaryTag = '';

							 		}
							 		else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag')
							 		{

							 			$master1 = $data->column1->Id;
                                        $master2 = $data->column2->Id;
                                        $primaryTag = $data->column3->Id;
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
							 					

							 					if(($master1 == 'year' && $master2 == 'month') || ($master2 == 'year' && $master1 == 'month'))
							 					{
							 						
							 						$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);										 								 					
								 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								 					$response['request1'] = $primeryName;
								 					$response['request2'] = "Year";
								 					$response['request3'] = "Month";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response);
												}
												else if(($master1 == 'year' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'year'))
												{
													$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);										 								 					
								 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								 					$response['request1'] = $primeryName;
								 					$response['request2'] = "Year";
								 					$response['request3'] = "Amount";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-year-month-amount',$response);

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
								 					$response['request1'] = $primeryName;
								 					$response['request2'] = $tagtype;
								 					$response['request3'] = "Year";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);

												}
												else
												{

													echo 'You need to optimize your query.';
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
								 					$response['request1'] = $primeryName;
								 					$response['request2'] = $tagtype;
								 					$response['request3'] = "Month";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);

												}
												else if(($master1 == 'month' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'month'))
												{
													$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);										 								 					
								 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								 					$response['request1'] = $primeryName;
								 					$response['request2'] = "Month";
								 					$response['request3'] = "Amount";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-year-month-amount',$response);

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

							 					$response['request1'] = $primeryName;
							 					$response['request2'] = $tagtype;
							 					$response['request3'] = "Amount";							 					
							 					$this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response);



							 				}
							 		else
									 	{

									 		echo '<p style="text-align: center">You need to optimize your query.</p>';
									 	}
							 		
							 	}
							 	else if(($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master' ) || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master'))
							 	{


							 		
							 		if($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'master')
							 		{
							 			$secondaryTag = $data->column1->Id;
                                        $master1 = $data->column2->Id;
                                        $master2 = $data->column3->Id;
                                        $primaryTag = '';

							 		}
							 		else if($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag')
							 		{

							 			$master1 = $data->column1->Id;
                                        $master2 = $data->column2->Id;
                                        $secondaryTag = $data->column3->Id;
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
							 					

							 					if(($master1 == 'year' && $master2 == 'month') || ($master2 == 'year' && $master1 == 'month'))
							 					{
							 						
							 						$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								 					$response['request1'] = $secondaryName;
								 					$response['request2'] = "Year";
								 					$response['request3'] = "Month";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response);
												}
												else if(($master1 == 'year' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'year'))
												{
													$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								 					$response['request1'] = $secondaryName;
								 					$response['request2'] = "Year";
								 					$response['request3'] = "Amount";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-year-month-amount',$response);

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
								 					$response['request1'] = $secondaryName;
								 					$response['request2'] = $tagtype;
								 					$response['request3'] = "Year";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);

												}
												else
												{

													echo '<p style="text-align: center">You need to optimize your query.</p>';
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
								 					$response['request1'] = $secondaryName;
								 					$response['request2'] = $tagtype;
								 					$response['request3'] = "Month";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_year($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);

												}
												else if(($master1 == 'month' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'month'))
												{
													$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);										 								 					
								 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_yearmonth3($customerid,$tableTag,$primaryTag,$secondaryTag,$master1,$master2);
								 					$response['request1'] = $secondaryName;
								 					$response['request2'] = "Month";
								 					$response['request3'] = "Amount";
								 					$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonth($customerid, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary,$master1,$master2);
								 					$this->load->view('reportapi/column3/report-response-column3-primary-year-month-amount',$response);

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

							 					$response['request1'] = $secondaryName;
							 					$response['request2'] = $tagtype;
							 					$response['request3'] = "Amount";							 					
							 					$this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response);



							 				}
							 		  else
									 	{

									 		echo '<p style="text-align: center">You need to optimize your query.</p>';
									 	}

							 	}

							 	else if(($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'master' ) || ($data->column1->Tag_Type == 'primary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'secondary tag') || ($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'master') || ($data->column1->Tag_Type == 'secondary tag' && $data->column2->Tag_Type == 'master' && $data->column3->Tag_Type == 'primary tag') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'secondary tag' && $data->column3->Tag_Type == 'primary tag') || ($data->column1->Tag_Type == 'master' && $data->column2->Tag_Type == 'primary tag' && $data->column3->Tag_Type == 'secondary tag'))
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
						 					$response['request1'] = $primeryName;
						 					$response['request2'] = $secondaryName;
						 					$response['request3'] = "Year";
						 					$response['request4'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_year($customerid, $master, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
						 					$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);

						 				}
						 				else if($master == 'month')
							 				{
							 					$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								 				$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);	
							 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primaryTag,$secondaryTag);
							 					$response['request1'] = $primeryName;
						 						$response['request2'] = $secondaryName;
						 						$response['request3'] = "Month";
						 						$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_month($customerid, $master, $tableTag, $primaryTag, $secondaryTag, $tablePrimary, $tableSecondary);
						 						$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);

								 			}
								 		else if($master == 'cash+' || $master == 'cash-' || $master == 'bank+' || $master == 'bank-' || $master == 'other')
							 				{	

							 					
								 				$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								 				$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
								 				
								 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_tagtype($customerid, $tableTag, $master, $primaryTag, $secondaryTag);
							 					$response['request1'] = $primeryName;
						 						$response['request2'] = $secondaryName;
						 						$response['request3'] = $master;						 						
						 						$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);


							 				}
							 			else if($master == 'amount')
							 				{	
							 					
								 				$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primaryTag);
								 				$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondaryTag);
								 				$response['data'] = $gettotaldata = $this->Report_Api_Model->get_total_data_by_year2($customerid, $tableTag,$primaryTag, $secondaryTag);
							 					$response['request1'] = $primeryName;
						 						$response['request2'] = $secondaryName;
						 						$response['request3'] =	"Amount";						 						
						 						$this->load->view('reportapi/column3/report-response-column3-primary-secondary-master',$response);

							 				}
							 				else
										 	{

										 		echo '<p style="text-align: center">You need to optimize your query.</p>';
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
												
									if((in_array($data->column1->Id, $tagType) && $data->column2->Id == 'year' && $data->column3->Id == 'month') || (in_array($data->column1->Id, $tagType) && $data->column2->Id == 'month' && $data->column3->Id == 'year') || ($data->column1->Id == 'month' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'year') || ($data->column1->Id == 'month' && $data->column2->Id == 'year' && in_array($data->column3->Id, $tagType)) || ($data->column1->Id == 'year' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'month') || ($data->column1->Id == 'year' && $data->column2->Id == 'month' && in_array($data->column3->Id, $tagType)) )
									{
										
										$master1 = 'year';
										$master2 = 'month';
								 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_monthyeartagtype($customerid,$tableTag,$tag);
								 		
										$response['request1'] = $tag;
								 		$response['request2'] = "Year";
								 		$response['request3'] = "Month";
								 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonthtagtype($customerid, $tableTag, $tag, $master1, $master2);
								 		//echo $this->db->last_query(); die;
										$this->load->view('reportapi/column3/report-response-column3-primary-master-master',$response);
										
									}
									
									else if((in_array($data->column1->Id, $tagType) && $data->column2->Id == 'amount' && $data->column3->Id == 'year') || (in_array($data->column1->Id, $tagType) && $data->column2->Id == 'year' && $data->column3->Id == 'amount') || ($data->column1->Id == 'year' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'amount') || ($data->column1->Id == 'year' && $data->column2->Id == 'amount' && in_array($data->column3->Id, $tagType)) || ($data->column1->Id == 'amount' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'year') || ($data->column1->Id == 'amount' && $data->column2->Id == 'year' && in_array($data->column3->Id, $tagType)) )
									{
										
										$master1 = 'year';
										$master2 = 'amount';
								 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_monthyeartagtype($customerid,$tableTag,$tag);
								 		
										$response['request1'] = $tag;
								 		$response['request2'] = "Year";
								 		$response['request3'] = "Amount";
								 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonthtagtype($customerid, $tableTag, $tag, $master1, $master2);
								 		//echo $this->db->last_query(); die;
										$this->load->view('reportapi/column3/report-response-column3-primary-year-month-amount',$response);
										
									}
									else if((in_array($data->column1->Id, $tagType) && $data->column2->Id == 'amount' && $data->column3->Id == 'month') || (in_array($data->column1->Id, $tagType) && $data->column2->Id == 'month' && $data->column3->Id == 'amount') || ($data->column1->Id == 'month' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'amount') || ($data->column1->Id == 'month' && $data->column2->Id == 'amount' && in_array($data->column3->Id, $tagType)) || ($data->column1->Id == 'amount' && in_array($data->column2->Id, $tagType) && $data->column3->Id == 'month') || ($data->column1->Id == 'amount' && $data->column2->Id == 'month' && in_array($data->column3->Id, $tagType)) )
									{
										
										$master1 = 'month';
										$master2 = 'amount';
								 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_monthyeartagtype($customerid,$tableTag,$tag);
								 		
										$response['request1'] = $tag;
								 		$response['request2'] = "Month";
								 		$response['request3'] = "Amount";
								 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column3_yearmonthtagtype($customerid, $tableTag, $tag, $master1, $master2);
								 		//echo $this->db->last_query(); die;
										$this->load->view('reportapi/column3/report-response-column3-primary-year-month-amount',$response);
										
									}
									else if( ($data->column1->Id == 'year' && $data->column2->Id == 'amount' && $data->column3->Id == 'month') || ($data->column1->Id == 'year' && $data->column2->Id == 'month' && $data->column3->Id == 'amount') || ($data->column1->Id == 'month' && $data->column2->Id == 'year' && $data->column3->Id == 'amount') || ($data->column1->Id == 'month' && $data->column2->Id == 'amount' && $data->column3->Id == 'year') || ($data->column1->Id == 'amount' && $data->column2->Id == 'year' && $data->column3->Id == 'month') || ($data->column1->Id == 'amount' && $data->column2->Id == 'month' && $data->column3->Id == 'year') )
									{
										$master1 = 'year';
										$master2 = 'month';
										$master3 = 'amount';
								 		$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year($customerid,$tableTag);
								 		//echo $this->db->last_query(); die;
										$response['request1'] = "Year";
								 		$response['request2'] = "Month";
								 		$response['request3'] = "Amount";
								 		$response['request4'] = $gettotaldata = $this->Report_Api_Model->get_total_data_column2_yearmonth($customerid, $tableTag, $tag, $master1, $master2,$master3);
								 		//echo $this->db->last_query(); die;
										$this->load->view('reportapi/column3/report-response-column3-primary-year-month-amount',$response);
										
									}
									
				 							 

				 							 
								}
							 	else
							 	{

							 		echo '<p style="text-align: center">You need to optimize your query.</p>';
							 	}

					 			
					 	}
					 	else
					 	{
					 		echo '<p style="text-align: center">You need to optimize your query.</p>';

					 	}

			 	}
			 	else if($total == 4)
			 	{
						if(	($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery' && $data->column3->Source_Type == 'image gallery' && $data->column4->Source_Type == 'image gallery') || ($data->column1->Source_Type == 'document gallery' && $data->column2->Source_Type == 'document gallery' && $data->column3->Source_Type == 'document gallery' && $data->column3->Source_Type == 'document gallery'))
			 			{
			 					if($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery')
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
								$tagtype = array('cash+','cash-','bank+','bank-','other');
								$primary = '';
								$secondary = '';
								$year = '';
								$month = '';
								$amount = '';
								$tag = '';
								
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
									
								}

							if($primary != '' && $secondary != '' &&  $year != '' &&  $tag != '')
							   {
							   				
							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $secondaryName;
					 					$response['request3'] = $tag;
					 					$response['request4'] = "Year";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response);

							   }
							   else if($primary != '' && $secondary != '' &&  $year != '' &&  $month != '')
							   {
							   			
							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);
					 					
					 					//echo $this->db->last_query(); die;
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $secondaryName;
					 					$response['request3'] = "Year";
					 					$response['request4'] = "Month";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die;
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-year-month',$response);

							   }
							   else if($primary != '' && $secondary != '' &&  $year != '' &&  $amount != '')
							   {
							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);
					 					
					 					//echo $this->db->last_query(); die;
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $secondaryName;
					 					$response['request3'] = "Year";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die;
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-year-month',$response);

							   }
							   else if($primary != '' && $secondary != '' &&  $month != '' &&  $amount != '')
							   {
							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);
					 					
					 					//echo $this->db->last_query(); die;
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $secondaryName;
					 					$response['request3'] = "Month";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die;
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-year-month',$response);

							   }
							   else if($primary != '' && $secondary != ''  &&  $month != '' &&  $tag != '')
							   {

							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $secondaryName;
					 					$response['request3'] = $tag;
					 					$response['request4'] = "Month";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response);


							   }
							   else if($primary != '' && $secondary != ''  &&  $amount != '' &&  $tag != '')
							   {

							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $secondaryName;
					 					$response['request3'] = $tag;
					 					$response['request4'] = "Amount";					 					
					 					//$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-master',$response);

							   }
							   else if($primary != '' && $year != ''  &&  $month != '' &&  $amount != '')
							   {
							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 			
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag, $primary, $secondary);
					 									 					
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = "Year";
					 					$response['request3'] = "Month";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die;
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-year-month-amount',$response);

							   }
							   else if($primary != '' && $year != ''  &&  $month != '' &&  $tag != '')
							   {
							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 									 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					//echo $this->db->last_query(); die; 
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $tag;
					 					$response['request3'] = "Year";
					 					$response['request4'] = "Month";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-tagtype',$response);

							   }
							   else if($primary != '' && $year != ''  &&  $amount != '' &&  $tag != '')
							   {

							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 									 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					//echo $this->db->last_query(); die; 
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $tag;
					 					$response['request3'] = "Year";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-tagtype',$response);


							   }
							   else if($primary != '' && $month != ''  &&  $amount != '' &&  $tag != '')
							   {

							   			$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
							 									 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					//echo $this->db->last_query(); die; 
					 					$response['request1'] = $primeryName;
					 					$response['request2'] = $tag;
					 					$response['request3'] = "Month";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-tagtype',$response);

							   }
							   else if($secondary != '' && $year != ''  &&  $month != '' &&  $tag != '')
							   {

										$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
							 									 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					//echo $this->db->last_query(); die; 
					 					$response['request1'] = $secondaryName;
					 					$response['request2'] = $tag;
					 					$response['request3'] = "Year";
					 					$response['request4'] = "Month";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-tagtype',$response);


							   }
							   else if($secondary != '' && $year != ''  &&  $amount != '' &&  $tag != '')
							   {
							   			
							   			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
							 									 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					//echo $this->db->last_query(); die; 
					 					$response['request1'] = $secondaryName;
					 					$response['request2'] = $tag;
					 					$response['request3'] = "Year";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-tagtype',$response);

							   }
							   else if($secondary != '' && $month != ''  &&  $amount != '' &&  $tag != '')
							   {

							   			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
							 									 					
					 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					//echo $this->db->last_query(); die; 
					 					$response['request1'] = $secondaryName;
					 					$response['request2'] = $tag;
					 					$response['request3'] = "Month";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-tagtype',$response);


							   }
							   else if($year != '' && $month != ''  &&  $amount != '' &&  $tag != '')
							   {

							   									 					
							 			$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
					 					//echo $this->db->last_query(); die; 
					 					$response['request1'] = $tag;
					 					$response['request2'] = "Year";
					 					$response['request3'] = "Month";
					 					$response['request4'] = "Amount";					 					
					 					$response['request5'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column4_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
					 					//echo $this->db->last_query(); die; 
					 					$this->load->view('reportapi/column4/report-response-column4-primary-secondary-year-month-amount',$response);



							   }
							   else
							   {
							   		echo '<p style="text-align: center">You need to optimize your query.</p>';

							   }
								
								
						}
						else
					 	{
					 		echo '<p style="text-align: center">You need to optimize your query.</p>';

					 	}

			 	}
			 	else if($total == 5)
			 	{
						if(	($data->column1->Source_Type == 'image gallery' && $data->column2->Source_Type == 'image gallery' && $data->column3->Source_Type == 'image gallery' && $data->column4->Source_Type == 'image gallery' && $data->column5->Source_Type == 'image gallery') || ($data->column1->Source_Type == 'document gallery' && $data->column2->Source_Type == 'document gallery' && $data->column3->Source_Type == 'document gallery' && $data->column3->Source_Type == 'document gallery' && $data->column5->Source_Type == 'document gallery'))
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
								
								$pt = 'primary tag';
								$st = 'secondary tag';
								$y = 'year';
								$m = 'month';
								$a = 'amount';
								$tagtype = array('cash+','cash-','bank+','bank-','other');
								$primary = '';
								$secondary = '';
								$year = '';
								$month = '';
								$amount = '';
								$tag = '';
								
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
									
								}

							   if( $primary != '' && $secondary != '' &&  $year != '' && $month != '' &&  $amount != '')
							   {
							   				
							   				
							   				$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
								 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_year2($customerid,$tableTag,$primary, $secondary);
						 					$response['request1'] = $primeryName;
						 					$response['request2'] = $secondaryName;
						 					$response['request3'] = "Year";
						 					$response['request4'] = "Month";
						 					$response['request5'] = "Amount";
						 					$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						 					$this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response);
						 					

							   }

							   else if( $primary != '' && $secondary != '' && $month != '' &&  $amount != '' && $tag != '')
							   {
							   				


							   				$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
								 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						 					$response['request1'] = $primeryName;
						 					$response['request2'] = $secondaryName;
						 					$response['request3'] = $tag;
						 					$response['request4'] = "Month";
						 					$response['request5'] = "Amount";
						 					$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						 					$this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response);

							   }
							   else if( $primary != '' && $secondary != '' && $year != '' &&  $amount != '' && $tag != '')
							   {
							   				


							   				$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
								 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						 					$response['request1'] = $primeryName;
						 					$response['request2'] = $secondaryName;
						 					$response['request3'] = $tag;
						 					$response['request4'] = "Year";
						 					$response['request5'] = "Amount";
						 					$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						 					$this->load->view('reportapi/column5/report-response-column5-primary-secondary-master',$response);

							   }

							   else if( $primary != '' && $secondary != '' && $year != '' &&  $month != '' && $tag != '')
							   {

							   				$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
								 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						 					

						 					$response['request1'] = $primeryName;
						 					$response['request2'] = $secondaryName;
						 					$response['request3'] = $tag;
						 					$response['request4'] = "Year";
						 					$response['request5'] = "Month";
						 					$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						 					//echo $this->db->last_query(); die;
						 					$this->load->view('reportapi/column5/report-response-column5-primary-secondary-tag',$response);

							   }

							   else if( $primary != ''  && $year != '' &&  $month != '' && $tag != '' && $amount != '')
							   {

							   				$primeryName = $this->Report_Api_Model->get_primaryName($tablePrimary,$primary);
								 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);						 					
						 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						 					
						 					$response['request1'] = $primeryName;
						 					$response['request2'] = $tag;
						 					$response['request3'] = "Year";
						 					$response['request4'] = "Month";
						 					$response['request5'] = "Amount";
						 					$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						 					//echo $this->db->last_query(); die;
						 					$this->load->view('reportapi/column5/report-response-column5-primary-secondary',$response);

							   }
							   else if( $secondary != ''  && $year != '' &&  $month != '' && $tag != '' && $amount != '')
							   {

							   				
								 			$secondaryName = $this->Report_Api_Model->get_secondaryName($tableSecondary,$secondary);
								 			//echo $this->db->last_query(); die;						 					
						 					$response['data'] = $gettotalentrybyyear = $this->Report_Api_Model->get_total_data_by_tagtype($customerid,$tableTag, $tag, $primary, $secondary);
						 					
						 					$response['request1'] = $secondaryName;
						 					$response['request2'] = $tag;
						 					$response['request3'] = "Year";
						 					$response['request4'] = "Month";
						 					$response['request5'] = "Amount";
						 					$response['request6'] = $gettotaldata = $gettotaldata = $this->Report_Api_Model->get_total_data_column5_year($customerid, $tableTag, $primary, $secondary, $year, $month, $amount, $tag);
						 					//echo $this->db->last_query(); die;
						 					$this->load->view('reportapi/column5/report-response-column5-primary-secondary',$response);

							   }
							   else
							   {
							   	  echo '<p style="text-align: center">You need to optimize your query.</p>';
							   }
								
								
								
								
								
								
						}
						else
					 	{
					 		echo '<p style="text-align: center">You need to optimize your query.</p>';

					 	}

			 	}
			 	else
			 	{
			 		echo '<p style="text-align: center">You need to optimize your query.</p>';

			 	}


		 	
		 } 
		}
	}
	else
	{
		echo '<p style="text-align: center">Authtoken mismatch.</p>';

	}
 }

     
      
}
