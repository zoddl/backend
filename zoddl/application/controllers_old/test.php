<?php

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

					foreach($data as $key => $value) {
						
						if(is_numeric($value->Id))
							$aa[] = $value->Tag_Type;
						else
							$aa[] = $value->Id;
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
						$responseapp['ResponseMessage'] = 'You need to optimize your query.';
						$responseapp['Payload'] = $htmldata;
						$this->response($responseapp);
						}
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
						$responseapp['ResponseMessage'] = 'You need to optimize your query.';
						$responseapp['Payload'] = $htmldata;
						$this->response($responseapp);
					}
			 	}
			