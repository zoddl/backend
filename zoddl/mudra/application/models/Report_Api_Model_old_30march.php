<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @package		CodeIgniter
 * @category	Model
 */
class Report_Api_Model extends CI_Model {
  
   public function get_customer_id($tablecustomer='',$Authtoken='')
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

 public function get_total_data_by_year($customerid='', $tableTag='')
 {
        $where = "Customer_Id = $customerid"; 
        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from("$tableTag");
        $this->db->where($where);
        $query = $this->db->get();
        return $query->row();  

 }



 public function get_total_data_by_year_universal($customerid = '', $value = '')
 {
          $temp = new stdClass();

          /* Image */
         
          $this->db->select('SUM(t.Amount) as Total_Amount, Count(t.Id) as No_Of_Entry');
          $this->db->from("tbl_tag t");         
          $this->db->where('t.Customer_Id = ', $customerid);
          if($value != '')
          {

            $this->db->where('t.Tag_Type = ', $value);
          }          
          $query = $this->db->get();
          $imagedata = $query->row();

          //echo $this->db->last_query(); die;

          /* Document */

          $this->db->select('SUM(dt.Amount) as Total_Amount, Count(dt.Id) as No_Of_Entry');
          $this->db->from("tbl_doc_tag dt");
          $this->db->where('dt.Customer_Id = ', $customerid);
          
          if($value != '')
          {

            $this->db->where('dt.Tag_Type = ', $value);
          }  

          $queryd = $this->db->get();
          $documentdata = $queryd->row();

           //echo $this->db->last_query(); die;

          $temp->Total_Amount = ($imagedata->Total_Amount + $documentdata->Total_Amount); 
          $temp->No_Of_Entry = ($imagedata->No_Of_Entry + $documentdata->No_Of_Entry); 
          //print_r($temp); die;
          return $temp;

 }

 public function get_total_data_universal($customerid, $value)
 {
        // Image

              
              $iyear = array();
              $dyear = array();
              $container = array();
              $data = array();

              $where = "Customer_Id = $customerid";

              if($value == 'year')
              {
                      $this->db->select('YEAR(Tag_Send_Date) as Year');

                      $this->db->from("tbl_tag");
                      $this->db->where($where);
                      $this->db->group_by('YEAR(Tag_Send_Date)');
                      $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                      $query = $this->db->get(); 
                      $imageyear =  $query->result();
                      foreach($imageyear as $iy)
                      {
                          $iyear[] = $iy->Year;

                      }


                      $this->db->select('YEAR(Tag_Send_Date) as Year');
                      $this->db->from("tbl_doc_tag");
                      $this->db->where($where);
                      $this->db->group_by('YEAR(Tag_Send_Date)');
                      $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                      $queryd = $this->db->get(); 
                      $documentyear =  $queryd->result();
                      foreach($documentyear as $dy)
                      {
                          $dyear[] = $dy->Year;

                      }
                      $container = array_merge($iyear,$dyear);
				  
                        $data = array_unique($container); 
                        return (object) $data;
              }
              else
              {
                      $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month');

                      $this->db->from("tbl_tag");
                      $this->db->where($where);
                      $this->db->group_by('Month(Tag_Send_Date)');
                      $this->db->order_by('Month(Tag_Send_Date)','ASC');
                      $query = $this->db->get(); 
                      $imageyear =  $query->result();
                      foreach($imageyear as $iy)
                      {
                          $iyear[] = $iy->Month;

                      }


                      $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month');
                      $this->db->from("tbl_doc_tag");
                      $this->db->where($where);
                      $this->db->group_by('Month(Tag_Send_Date)');
                      $this->db->order_by('Month(Tag_Send_Date)','ASC');
                      $queryd = $this->db->get(); 
                      $documentyear =  $queryd->result();
                      foreach($documentyear as $dy)
                      {
                          $dyear[] = $dy->Month;

                      }
                      $container = array_merge($iyear,$dyear);
             
                        $data = array_unique($container);

                        $output = array();

                        

                        foreach($data as $month) {
                            $m = date_parse($month);
                            $output[$m['month']] = $month;
                        }                     
                                   
                        
                        return (object) $output;

              }





 }

 public function get_total_data($customerid='', $gellery_type='', $type='', $value='', $tableTag='', $tablePrimary='', $tableSecondary='')
 {
    if(($gellery_type == 'image gallery' || $gellery_type == 'document gallery') && ($type == 'primary tag'))
    {
          $this->db->select('SUM(t.Amount) as Total_Amount, Count(t.Id) as No_Of_Entry');
          $this->db->from("$tableTag t");
          $this->db->join("$tablePrimary pt",'t.Primary_Tag = pt.Id','left');
          $this->db->where('t.Customer_Id = ', $customerid);
          $this->db->where('t.Primary_Tag = ', $value);
          $query = $this->db->get();
          return $query->row();
    }
    else if(($gellery_type == 'image gallery' || $gellery_type == 'document gallery') && ($type == 'secondary tag'))
    {
          $query = $this->db->query("SELECT SUM(Amount) as Total_Amount, Count(Id) as No_Of_Entry FROM $tableTag WHERE Customer_Id = $customerid and FIND_IN_SET($value,Secondry_Tag)");
          return $query->row();
    }
    else if(($gellery_type == 'image gallery' || $gellery_type == 'document gallery')  && ($type == 'master'))
    {
          if($value == 'year')
          {
              $where = "Customer_Id = $customerid"; 
              $this->db->select('YEAR(Tag_Send_Date) as Year');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;
              return  $query->result();  

          }
          else if($value == 'month')
          {
            
              $where = "Customer_Id = $customerid"; 
              $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;
              return  $query->result();  
          }
          else if($value == 'cash+' || $value == 'cash-' || $value == 'bank+' || $value == 'bank-' || $value == 'other')
          {

            $this->db->select('SUM(Amount) as Total_Amount, Count(Id) as No_Of_Entry');
            $this->db->from("$tableTag");           
            $this->db->where('Customer_Id = ', $customerid);
            $this->db->where('Tag_Type = ', $value);
            $query = $this->db->get();
            //echo $this->db->last_query(); die; 
            return $query->row();
          }

    }

 }

public function get_total_data_columen2($customerid='', $tableTag='', $tablePrimary='', $tableSecondary='', $primaryTag='', $secondaryTag='')
 {
    
      $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag)';
      $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
      $this->db->from($tableTag);
      $this->db->where($where);
      //$this->db->join()
      
      $query = $this->db->get();
      //echo $this->db->last_query(); die;
      return $query->row();
 }

 
public function get_primaryName($tablePrimary='',$primaryTag='')
{
        $query = $this->db->get_where($tablePrimary,array("Id" =>$primaryTag));

        if($query->num_rows() > 0)
         {
            return $query->row()->Prime_Name;

         }
         else
         {

           return "NA";
         }


}

public function get_secondaryName($tableSecondary='',$secondaryTag='')
{
     
       $query = $this->db->get_where($tableSecondary,array("Id" =>$secondaryTag));

       if($query->num_rows() > 0)
       {
          return $query->row()->Secondary_Name;

       }
       else
       {

         return "NA";
       }
}

public function get_total_data_by_year2($customerid='',$tableTag='',$primaryTag='',$secondaryTag='')
 {
        
        if($secondaryTag != '' && $primaryTag != '')
        {

             $where = 'Customer_Id="'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag)';

        }
        else if($secondaryTag == '' && $primaryTag != '')
        {
          $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'"';

        }
        else if($secondaryTag != '' && $primaryTag == '')
        {
          $where = 'Customer_Id = "'.$customerid.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag)';
        }
        else
        {
          $where = 'Customer_Id = "'.$customerid.'"';
        }
        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from($tableTag);
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row();  

 }

 public function get_total_data_column2_year($customerid='', $value='', $tableTag='', $primaryTag='', $secondaryTag='', $tablePrimary='', $tableSecondary='')
 {
              
              if($secondaryTag != '' && $primaryTag != '')
              {

                   $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag)';
                   $primaryName = $this->get_primaryName($tablePrimary,$primaryTag);
                   $secondaryName = $this->get_secondaryName($tableSecondary,$secondaryTag);
              }
              else if($primaryTag == '')
              {
                
                $where = 'Customer_Id = "'.$customerid.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag)';
                $Name = $this->get_secondaryName($tableSecondary,$secondaryTag);

              }
              else
              {

                $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'"';
                $Name = $this->get_primaryName($tablePrimary,$primaryTag);

              } 
              $this->db->select('YEAR(Tag_Send_Date) as Year');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;

              return $query->result();
               

 }

 public function get_total_data_column2_month($customerid='', $value='', $tableTag='', $primaryTag='', $secondaryTag='', $tablePrimary='', $tableSecondary='')
 {
             
              if($secondaryTag != '' && $primaryTag != '')
              {

                   $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag)';
                   $primaryName = $this->get_primaryName($tablePrimary,$primaryTag);
                   $secondaryName = $this->get_secondaryName($tableSecondary,$secondaryTag);
              }
              else if($primaryTag == '')
              {
                
                $where = 'Customer_Id = "'.$customerid.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag)';
                $Name = $this->get_secondaryName($tableSecondary,$secondaryTag);

              }
              else
              {

                $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'" ';
                $Name = $this->get_primaryName($tablePrimary,$primaryTag);

              } 
              $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from($tableTag);
              $this->db->where($where);
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;
              return $query->result();           
              
               

 }

	
 public function get_total_data_column2_yearmonth_universal($customerid='', $master1='',$master2='',$master3='')
 {
	 $tagType = array("cash+","cash-","bank+", "bank-","other");
	 
	 //echo "customerid : ".$customerid."/ master1 : ".$master1."/ master2 : ".$master2."/ master3 : ".$master3;die;

              $where = "Customer_Id = $customerid";

        if( ($master1 == 'year' && $master2 == 'month' && $master3 == '') || ($master1 == 'month' && $master2 == 'year' && $master3 == '') )
            {
                 $sql = '(SELECT YEAR(t2.Tag_Send_Date) as Year, DATE_FORMAT(t2.Tag_Send_Date, "%b") as Month FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' GROUP BY YEAR(t2.Tag_Send_Date), MONTH(t2.Tag_Send_Date))
                 UNION
                 (SELECT YEAR(t1.Tag_Send_Date) as Year, DATE_FORMAT(t1.Tag_Send_Date, "%b") as Month FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' GROUP BY YEAR(t1.Tag_Send_Date), MONTH(t1.Tag_Send_Date))';

                $query = $this->db->query($sql);  

                //echo $this->db->last_query(); die;

                return $query->result();


            }
        else if( ($master1 == 'year' && $master2 == 'amount' && $master3 == '') || ($master1 == 'amount' && $master2 == 'year' && $master3 == '') )
            {

              
              $sql = '(SELECT YEAR(t2.Tag_Send_Date) as Year, SUM(t2.Amount) as Amount FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' GROUP BY YEAR(t2.Tag_Send_Date))
                 UNION
                 (SELECT YEAR(t1.Tag_Send_Date) as Year, SUM(t1.Amount) as Amount FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' GROUP BY YEAR(t1.Tag_Send_Date))';

                $query = $this->db->query($sql);

                //echo $this->db->last_query(); die;
				//print_r($query->result()); die;
				
                return $query->result();

            }
        else if( ($master1 == 'month' && $master2 == 'amount' && $master3 == '') || ($master1 == 'amount' && $master2 == 'month' && $master3 == '') )
            {

              $sql = '(SELECT DATE_FORMAT(t2.Tag_Send_Date, "%b") as Month, SUM(t2.Amount) as Amount FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' GROUP BY MONTH(t2.Tag_Send_Date) ORDER BY MONTH(t2.Tag_Send_Date))
                 UNION
                 (SELECT DATE_FORMAT(t1.Tag_Send_Date, "%b") as Month, SUM(t1.Amount) as Amount FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' GROUP BY MONTH(t1.Tag_Send_Date) ORDER BY MONTH(t1.Tag_Send_Date))';

                $query = $this->db->query($sql);  

                //echo $this->db->last_query(); die;

                return $query->result();

            }
	 	else if($master1 == 'month' && in_array($master2, $tagType))
		{
              $sql = '(SELECT DATE_FORMAT(t2.Tag_Send_Date, "%b") as Month, SUM(t2.Amount) as Amount FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' AND t2.Tag_Type = "'.$master2.'" GROUP BY MONTH(t2.Tag_Send_Date) ORDER BY MONTH(t2.Tag_Send_Date))
                 UNION
                 (SELECT DATE_FORMAT(t1.Tag_Send_Date, "%b") as Month, SUM(t1.Amount) as Amount FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' AND t1.Tag_Type = "'.$master2.'" GROUP BY MONTH(t1.Tag_Send_Date) ORDER BY MONTH(t1.Tag_Send_Date))';

                $query = $this->db->query($sql);  

                //echo $this->db->last_query(); die;

                return $query->result();			
		}
		 else if($master1 == 'year' && in_array($master2, $tagType))
	 	{
              $sql = '(SELECT YEAR(t2.Tag_Send_Date) as Year, SUM(t2.Amount) as Amount FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' AND t2.Tag_Type = "'.$master2.'" GROUP BY YEAR(t2.Tag_Send_Date))
                 UNION
                 (SELECT YEAR(t1.Tag_Send_Date) as Year, SUM(t1.Amount) as Amount FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' AND t1.Tag_Type = "'.$master2.'" GROUP BY YEAR(t1.Tag_Send_Date))';

                $query = $this->db->query($sql);

                //echo $this->db->last_query(); die;
				//print_r($query->result()); die;

                return $query->result();
	 }
      else
      {
        $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','asc');
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get();
        
      }
             
              //echo $this->db->last_query(); die; 

                      
              
 }

public function get_total_data_column3_yearmonth_universal($customerid='', $master1='',$master2='',$tag='')
{
	$tagType = array("cash+","cash-","bank+", "bank-","other");
	$returndata = array();

	//echo "customerid : ".$customerid."/ master1 : ".$master1."/ master2 : ".$master2."/ master3 : ".$master3;die;

	$where = "Customer_Id = $customerid";

	if( ($master1 == 'year' && $master2 == 'month' && in_array($tag, $tagType)) || ($master1 == 'month' && $master2 == 'year' && in_array($tag, $tagType)) )
	{
		$sql = '(SELECT YEAR(t2.Tag_Send_Date) as Year, DATE_FORMAT(t2.Tag_Send_Date, "%b") as Month FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' AND t2.Tag_Type = "'.$tag.'" GROUP BY YEAR(t2.Tag_Send_Date), MONTH(t2.Tag_Send_Date))
		UNION
		(SELECT YEAR(t1.Tag_Send_Date) as Year, DATE_FORMAT(t1.Tag_Send_Date, "%b") as Month FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' AND t1.Tag_Type = "'.$tag.'" GROUP BY YEAR(t1.Tag_Send_Date), MONTH(t1.Tag_Send_Date))';

		$query = $this->db->query($sql);  

		//echo $this->db->last_query(); die;

		return $query->result();
	}
	else if( ($master1 == 'year' && $master2 == 'amount' && in_array($tag, $tagType)) || ($master1 == 'amount' && $master2 == 'year' && in_array($tag, $tagType)) )
	{
		$sql = '(SELECT YEAR(t2.Tag_Send_Date) as Year FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' AND t2.Tag_Type = "'.$tag.'" GROUP BY YEAR(t2.Tag_Send_Date))
		UNION
		(SELECT YEAR(t1.Tag_Send_Date) as Year FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' AND t1.Tag_Type = "'.$tag.'" GROUP BY YEAR(t1.Tag_Send_Date))';

		$query = $this->db->query($sql);

		foreach( $query->result() as $data)
		{
			$data->Amount = $this->_total_sum_amount_year_tag($data->Year, $tag, $customerid);
			array_push($returndata,$data);
			
		}
		return (object)$returndata;
	}
	else if( ($master1 == 'month' && $master2 == 'amount' && in_array($tag, $tagType)) || ($master1 == 'amount' && $master2 == 'month' && in_array($tag, $tagType)) )
	{
		$sql = '(SELECT DATE_FORMAT(t2.Tag_Send_Date, "%b") as Month FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' AND t2.Tag_Type = "'.$tag.'" GROUP BY MONTH(t2.Tag_Send_Date) ORDER BY MONTH(t2.Tag_Send_Date))
		UNION
		(SELECT DATE_FORMAT(t1.Tag_Send_Date, "%b") as Month FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' AND t1.Tag_Type = "'.$tag.'" GROUP BY MONTH(t1.Tag_Send_Date) ORDER BY MONTH(t1.Tag_Send_Date))';

		$query = $this->db->query($sql);

		foreach( $query->result() as $data)
		{
			$data->Amount = $this->_total_sum_amount_month_tag($data->Month, $tag, $customerid);
			array_push($returndata,$data);
			
		}
		return (object)$returndata;
	}
	else if( ($master1 == 'year' && $master2 == 'month' && $tag == 'amount') || ($master1 == 'year' && $master2 == 'month' && $tag == 'amount') )
	{
		
		$sql = '(SELECT YEAR(t2.Tag_Send_Date) as Year, DATE_FORMAT(t2.Tag_Send_Date, "%b") as Month FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' GROUP BY MONTH(t2.Tag_Send_Date) ORDER BY MONTH(t2.Tag_Send_Date))
		UNION
		(SELECT YEAR(t1.Tag_Send_Date) as Year, DATE_FORMAT(t1.Tag_Send_Date, "%b") as Month FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' GROUP BY MONTH(t1.Tag_Send_Date) ORDER BY MONTH(t1.Tag_Send_Date))';

		$query = $this->db->query($sql);

		//echo $this->db->last_query(); die;
		
		foreach( $query->result() as $data)
		{
			$data->Amount = $this->_total_sum_amount($data->Year, $data->Month, $customerid);
			array_push($returndata,$data);
			
		}
		return (object)$returndata;
	}
	
}

function _total_sum_amount($year, $month, $cid)
{
	  $mn = date('m', strtotime($month));
	  $where = "YEAR(Tag_Send_Date) = $year AND MONTH(Tag_Send_Date) = $mn AND Customer_Id = $cid";
	 $this->db->select('SUM(Amount) as Amount1');
	 $this->db->from("tbl_tag");
	  $this->db->where($where);
	  $query = $this->db->get()->row(); 
	
	  $am1 = $query->Amount1;
	  

	  $this->db->select('SUM(Amount) as Amount2');
	  $this->db->from("tbl_doc_tag");
	  $this->db->where($where);	  
	  $queryd = $this->db->get()->row(); 
	  $am2 = $queryd->Amount2;
		return ($am1 + $am2);
	
}

function _total_sum_amount_year_tag($year, $tag, $cid)
{
	  //$mn = date('m', strtotime($month));
	  $where = "Tag_Type = '$tag' AND YEAR(Tag_Send_Date) = $year  AND Customer_Id = $cid";
	 $this->db->select('SUM(Amount) as Amount1');
	 $this->db->from("tbl_tag");
	  $this->db->where($where);
	  $query = $this->db->get()->row(); 
	
	  $am1 = $query->Amount1;
	  

	  $this->db->select('SUM(Amount) as Amount2');
	  $this->db->from("tbl_doc_tag");
	  $this->db->where($where);	  
	  $queryd = $this->db->get()->row(); 
	  $am2 = $queryd->Amount2;
		return ($am1 + $am2);
	
}

function _total_sum_amount_month_tag($month, $tag, $cid)
{
	  $mn = date('m', strtotime($month));
	  $where = "Tag_Type = '$tag' AND MONTH(Tag_Send_Date) = $mn AND Customer_Id = $cid";
	 $this->db->select('SUM(Amount) as Amount1');
	 $this->db->from("tbl_tag");
	  $this->db->where($where);
	  $query = $this->db->get()->row(); 
	
	  $am1 = $query->Amount1;
	  

	  $this->db->select('SUM(Amount) as Amount2');
	  $this->db->from("tbl_doc_tag");
	  $this->db->where($where);	  
	  $queryd = $this->db->get()->row(); 
	  $am2 = $queryd->Amount2;
		return ($am1 + $am2);
	
}

public function get_total_data_column4_yearmonth_universal($customerid='', $master1='',$master2='',$master3='', $tag = '')
{
	$tagType = array("cash+","cash-","bank+", "bank-","other");

	//echo "customerid : ".$customerid."/ master1 : ".$master1."/ master2 : ".$master2."/ master3 : ".$master3;die;

	$where = "Customer_Id = $customerid";
	
	$returndata = array();
	
	if( ($master1 == 'year' && $master2 == 'month' && $master3 == 'amount') || ($master1 == 'year' && $master2 == 'month' && $master3 == 'amount') )
	{
		$sql = '(SELECT YEAR(t2.Tag_Send_Date) as Year, DATE_FORMAT(t2.Tag_Send_Date, "%b") as Month FROM `tbl_doc_tag` t2 WHERE t2.Customer_Id='.$customerid.' AND t2.Tag_Type = "'.$tag.'" GROUP BY MONTH(t2.Tag_Send_Date) ORDER BY MONTH(t2.Tag_Send_Date))
		UNION
		(SELECT YEAR(t1.Tag_Send_Date) as Year, DATE_FORMAT(t1.Tag_Send_Date, "%b") as Month FROM `tbl_tag` t1 WHERE t1.Customer_Id='.$customerid.' AND t1.Tag_Type = "'.$tag.'" GROUP BY MONTH(t1.Tag_Send_Date) ORDER BY MONTH(t1.Tag_Send_Date))';

		$query = $this->db->query($sql);

		foreach( $query->result() as $data)
		{
			$data->Amount = $this->_total_sum_amount_col4($data->Year, $data->Month, $tag, $customerid);
			array_push($returndata,$data);
			
		}
		return (object)$returndata;
	}
}

function _total_sum_amount_col4($year, $month, $tag, $cid)
{
	  $mn = date('m', strtotime($month));
	  $where = "YEAR(Tag_Send_Date) = $year AND MONTH(Tag_Send_Date) = $mn AND Customer_Id = $cid AND Tag_Type = '$tag'";
	 $this->db->select('SUM(Amount) as Amount1');
	 $this->db->from("tbl_tag");
	  $this->db->where($where);
	  $query = $this->db->get()->row(); 
	
	  $am1 = $query->Amount1;
	  

	  $this->db->select('SUM(Amount) as Amount2');
	  $this->db->from("tbl_doc_tag");
	  $this->db->where($where);	  
	  $queryd = $this->db->get()->row(); 
	  $am2 = $queryd->Amount2;
		return ($am1 + $am2);
	
}

	
	
 public function get_total_data_column2_yearmonth($customerid='', $tableTag='',$master1='',$master2='',$master3='')
 {

              $where = "Customer_Id = $customerid";

        if( ($master1 == 'year' && $master2 == 'month' && $master3 == '') || ($master1 == 'month' && $master2 == 'year' && $master3 == '') )
            {
              $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','asc');
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get(); 
            }
        else if( ($master1 == 'year' && $master2 == 'amount' && $master3 == '') || ($master1 == 'amount' && $master2 == 'year' && $master3 == '') )
            {

              $this->db->select('YEAR(Tag_Send_Date) as Year, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');              
              $this->db->order_by('YEAR(Tag_Send_Date)','asc');
              $query = $this->db->get(); 

            }
        else if( ($master1 == 'month' && $master2 == 'amount' && $master3 == '') || ($master1 == 'amount' && $master2 == 'month' && $master3 == '') )
            {

              $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('MONTH(Tag_Send_Date)');             
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get(); 

            }
			else
			{
			  $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','asc');
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get();
				
			}
             
              //echo $this->db->last_query(); die; 

              return $query->result();            
              
 }

 public function get_total_data_by_monthyeartagtype($customerid='',$tableTag='',$tagtype='')
 {

        $where = "Customer_Id = $customerid AND Tag_Type = '$tagtype'";
        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from("$tableTag");
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row();  

 }

 public function get_total_data_column2_yearmonthtagtype($customerid='', $tableTag='',$tagtype='', $value='')
 {

              $where = "Customer_Id = $customerid AND Tag_Type = '$tagtype'";

         if( ($value == 'month'))
            {
              $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month');
              $this->db->from("$tableTag");
              $this->db->where($where);              
              $this->db->group_by('MONTH(Tag_Send_Date)');             
              $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
              $query = $this->db->get(); 
            }
          else
            {

              $this->db->select('YEAR(Tag_Send_Date) as Year');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('YEAR(Tag_Send_Date)');
              $this->db->order_by('YEAR(Tag_Send_Date)','asc');
              $query = $this->db->get(); 

            }
       
              //echo $this->db->last_query(); die; 

              return $query->result();            
              
 }
 public function get_total_data_by_tagtype($customerid='', $tableTag='', $value='', $primaryTag='', $secondaryTag='')
 {      
        if($secondaryTag != '' && $primaryTag != '')
        {

             $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag) AND Tag_Type = "'.$value.'" ';
             
        }
        else if($primaryTag == '' && $secondaryTag != '')
        {
          $where = 'Customer_Id = "'.$customerid.'" AND FIND_IN_SET("'.$secondaryTag.'",Secondry_Tag) AND Tag_Type = "'.$value.'" ';
        }
        else if($primaryTag != '' && $secondaryTag == '')
        {
          $where = 'Customer_Id = "'.$customerid.'" AND Primary_Tag = "'.$primaryTag.'" AND Tag_Type = "'.$value.'" ';
        }
        else{

          $where = 'Customer_Id = "'.$customerid.'" AND Tag_Type = "'.$value.'" ';
        } 
        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from($tableTag);
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row();  

 }

 public function get_total_data_by_year3($customerid='',$tableTag='',$primaryTag='',$secondaryTag='',$master1='',$master2='')
 {
        if(($master1 == 'year' && $master2 != 'year' && $master2 != 'month') || ($master1 == 'month' && $master2 != 'month' && $master2 != 'year'))
        {
            $Tag_Type = $master2;

        }
        else if(($master1 != 'year' && $master2 == 'year' && $master1 != 'month') || ($master1 != 'month' && $master2 == 'month' && $master1 != 'year'))
        {

           $Tag_Type = $master1;

        }
        
        if($secondaryTag == '')
        {
          $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag AND Tag_Type = '$Tag_Type'";

        }
        else
        {
          $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag) AND Tag_Type = '$Tag_Type'";
        }

        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from("$tableTag");
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row();  

 }

 public function get_total_data_column3_year($customerid='', $tableTag='', $primaryTag='', $secondaryTag='', $tablePrimary='', $tableSecondary='', $master1='', $master2='')
 {
              
              if($master1 == 'year' && $master2 != 'year' && $master2 != 'month')
              {
                  $Tag_Type = $master2;

              }
              else if($master1 != 'year' && $master2 == 'year' && $master1 != 'month')
              {

                 $Tag_Type = $master1;

              }
              else if($master1 == 'month' && $master2 != 'month' && $master2 != 'year')
              {
                $Tag_Type = $master2;

              }
              else if($master1 != 'month' && $master2 == 'month' && $master1 != 'year')
              {
                $Tag_Type = $master1;

              }
              

              if($primaryTag == '')
              {
                
                $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag) AND Tag_Type = '$Tag_Type'";
                $Name = $this->get_secondaryName($tableSecondary,$secondaryTag);

              }
              else
              {

                $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag AND Tag_Type = '$Tag_Type'";
                $Name = $this->get_primaryName($tablePrimary,$primaryTag);

              }

              if( $master1 == 'year' || $master2 == 'year')
              {
                $this->db->select('YEAR(Tag_Send_Date) as Year');
                $this->db->from("$tableTag");
                $this->db->where($where);
                $this->db->group_by('YEAR(Tag_Send_Date)');
                $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                $query = $this->db->get(); 
              }
              else if($master1 == 'month' || $master2 == 'month')
              {
                $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month');
                $this->db->from("$tableTag");
                $this->db->where($where);              
                $this->db->group_by('MONTH(Tag_Send_Date)');             
                $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                $query = $this->db->get(); 

              }
              

              //echo $this->db->last_query(); die;
              

              return  $query->result();  

 }

 public function get_total_data_column3_month($customerid='', $tableTag='', $primaryTag='', $secondaryTag='', $tablePrimary='', $tableSecondary='', $master1='', $master2='')
 {
             
              if($master1 == 'month' && $master2 != 'month' && $master2 != 'year')
              {
                  $Tag_Type = $master2;

              }
              else if($master1 != 'month' && $master2 == 'month' && $master1 != 'year')
              {

                 $Tag_Type = $master1;

              }

              if($primaryTag == '')
              {
                
                $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag) AND Tag_Type = '$Tag_Type'";
                $Name = $this->get_secondaryName($tableSecondary,$secondaryTag);

              }
              else
              {

                $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag AND Tag_Type = '$Tag_Type'";
                $Name = $this->get_primaryName($tablePrimary,$primaryTag);

              } 
              $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
              $this->db->from("$tableTag");
              $this->db->where($where);
              $this->db->group_by('MONTH(Tag_Send_Date)');
              $this->db->order_by('MONTH(Tag_Send_Date)','asc');
              $query = $this->db->get(); 
              //echo $this->db->last_query(); die;             
              $returndata = array();
                       
                foreach($query->result() as $data)
                {
                    $temp = new stdClass();
                    $temp->col1 = $data->Month == null ? '' : $data->Month;
                    $temp->col2 = $Name == null ? '' : $Name;
                    $temp->col3 = $Tag_Type == null ? '' : $Tag_Type;
                    $temp->col4 = '';
                    $temp->col5 = $data->Amount == null ? '0' : $data->Amount;
                    array_push($returndata,$temp);
                }
                
              return  $returndata;   

 }

 public function get_total_data_by_yearmonth3($customerid='',$tableTag='',$primaryTag='',$secondaryTag='',$master1='',$master2='', $tagtype='')
 {

        
      if($tagtype == '')
      {
          if($secondaryTag == '')
          {
            $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag";

          }
          else
          {
            $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag)";
          }

      }
      else
      {

          if($secondaryTag == '')
          {
            $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag AND Tag_Type = '$tagtype'";

          }
          else
          {
            $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag) AND Tag_Type = '$tagtype'";
          }


      }

        $this->db->select('SUM(Amount) as Total_Amount, COUNT(Id) as No_Of_Entry');
        $this->db->from("$tableTag");
        $this->db->where($where);
        $query = $this->db->get();
        //echo $this->db->last_query(); die;
        return $query->row();  

 }
 
 public function get_total_data_column3_yearmonth($customerid='', $tableTag='', $primaryTag='', $secondaryTag='', $tablePrimary='', $tableSecondary='', $master1='', $master2='')
 {

              if($primaryTag == '')
              { 
                $where = "Customer_Id = $customerid AND FIND_IN_SET($secondaryTag,Secondry_Tag)";
              }
              else
              {
                $where = "Customer_Id = $customerid AND Primary_Tag = $primaryTag";
              }

              if(($master1 == 'year' && $master2 == 'month') || ($master2 == 'year' && $master1 == 'month'))
              {

                $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month');
                $this->db->from("$tableTag");
                $this->db->where($where);
                $this->db->group_by('YEAR(Tag_Send_Date)');
                $this->db->group_by('MONTH(Tag_Send_Date)');
                $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                $query = $this->db->get();
              }
              else if(($master1 == 'year' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'year'))
              {

                $this->db->select('YEAR(Tag_Send_Date) as Year, SUM(Amount) as Amount');
                $this->db->from("$tableTag");
                $this->db->where($where);
                $this->db->group_by('YEAR(Tag_Send_Date)');                
                $this->db->order_by('YEAR(Tag_Send_Date)','ASC');                
                $query = $this->db->get();
              }
               else if(($master1 == 'month' && $master2 == 'amount') || ($master1 == 'amount' && $master2 == 'month'))
              {

                $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
                $this->db->from("$tableTag");
                $this->db->where($where);
                $this->db->group_by('MONTH(Tag_Send_Date)');                
                $this->db->order_by('MONTH(Tag_Send_Date)','ASC');               
                $query = $this->db->get();
              } 
                //echo $this->db->last_query(); die;             
             
              return  $query->result();   

 }
 
  public function get_total_data_column3_yearmonthtagtype($customerid='', $tableTag='', $tagtype='', $master1='', $master2='', $master3='')
 {
                $where = "Customer_Id = $customerid AND Tag_Type = '$tagtype'";
				
				if($master1 == 'year' && $master2 == 'month' && $master3 == '')
				{
					$this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month');
					$this->db->from("$tableTag");
					$this->db->where($where);
					$this->db->group_by('YEAR(Tag_Send_Date)');
					$this->db->group_by('MONTH(Tag_Send_Date)');
					$this->db->order_by('YEAR(Tag_Send_Date)','ASC');
					$this->db->order_by('MONTH(Tag_Send_Date)','ASC');
					$query = $this->db->get();
				}
				
				else if($master1 == 'year' && $master2 == 'amount' && $master3 == '')
				{              
					$this->db->select('YEAR(Tag_Send_Date) as Year, SUM(Amount) as Amount');
					$this->db->from("$tableTag");
					$this->db->where($where); 
					$this->db->group_by('YEAR(Tag_Send_Date)');					
					$this->db->order_by('YEAR(Tag_Send_Date)','ASC');					
					$query = $this->db->get();
				}

				else if($master1 == 'month' && $master2 == 'amount' && $master3 == '')
				{
					$this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
					$this->db->from("$tableTag");
					$this->db->where($where);
					$this->db->group_by('MONTH(Tag_Send_Date)');
					$this->db->order_by('MONTH(Tag_Send_Date)','ASC');
					$query = $this->db->get();
				}
				else{
					$this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
					$this->db->from("$tableTag");
					$this->db->where($where);
					$this->db->group_by('YEAR(Tag_Send_Date)');
					$this->db->group_by('MONTH(Tag_Send_Date)');
					$this->db->order_by('YEAR(Tag_Send_Date)','ASC');
					$this->db->order_by('MONTH(Tag_Send_Date)','ASC');
					$query = $this->db->get();
				}
				
				return  $query->result();   

 }

   public function getamount($customerid='',$tableTag='')
   {
          $this->db->select('SUM(Amount) as Total_Amount, Count(Id) as No_Of_Entry');
          $this->db->from("$tableTag");          
          $this->db->where('Customer_Id = ', $customerid);          
          $query = $this->db->get();
          //echo $this->db->last_query(); die;
          return $query->row();

   }

   public function get_total_data_column5_year($customerid = '', $tableTag = '', $primary = '', $secondary = '', $year = '', $month = '', $amount = '', $tag = '')
    {
              
              if($secondary != '' && $primary != '' && $tag != '')
              {

                   $where = "Customer_Id = $customerid AND Primary_Tag = $primary AND FIND_IN_SET($secondary,Secondry_Tag) AND Tag_Type = '$tag'";
                   
              }
              else if($secondary != '' && $primary != '' && $tag == '')
              {

                   $where = "Customer_Id = $customerid AND Primary_Tag = $primary AND FIND_IN_SET($secondary,Secondry_Tag)";
                   
              }
              else if($secondary != '' && $primary == '' && $tag != '')
              {

                   $where = "Customer_Id = $customerid AND FIND_IN_SET($secondary,Secondry_Tag) AND Tag_Type = '$tag'";
                   
              }
              else if($secondary == '' && $primary != '' && $tag != '')
              {

                   $where = "Customer_Id = $customerid AND Primary_Tag = $primary AND Tag_Type = '$tag'";
                   
              }              
              else
              {

                $where = "Customer_Id = $customerid";
                

              }

              if( $year != '' && $month != '' && $amount != '') 
              {
                  $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
                  $this->db->from("$tableTag");
                  $this->db->where($where);
                  $this->db->group_by('YEAR(Tag_Send_Date)');
                  $this->db->group_by('MONTH(Tag_Send_Date)');
                  $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                  $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                  $query = $this->db->get();
              }
              else if($year == '' && $month != '' && $amount != '')
              {
                  $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
                  $this->db->from("$tableTag");
                  $this->db->where($where);                 
                  $this->db->group_by('MONTH(Tag_Send_Date)');                  
                  $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                  $query = $this->db->get();

              } 
              else if($year != '' && $month == '' && $amount != '')
              {
                  $this->db->select('YEAR(Tag_Send_Date) as Year, SUM(Amount) as Amount');
                  $this->db->from("$tableTag");
                  $this->db->where($where);                 
                  $this->db->group_by('YEAR(Tag_Send_Date)');                  
                  $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                  $query = $this->db->get();

              }

              else if( $year != '' && $month != '' && $amount == '') 
              {
                  $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month');
                  $this->db->from("$tableTag");
                  $this->db->where($where);
                  $this->db->group_by('YEAR(Tag_Send_Date)');
                  $this->db->group_by('MONTH(Tag_Send_Date)');
                  $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                  $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                  $query = $this->db->get();
              } 
              //echo $this->db->last_query(); die;

              return $query->result();
               

    }

    public function get_total_data_column4_year($customerid = '', $tableTag = '', $primary = '', $secondary = '', $year = '', $month = '', $amount = '', $tag = '')
    {
              
              if($secondary != '' && $primary != '' && $tag != '')
              {

                   $where = "Customer_Id = $customerid AND Primary_Tag = $primary AND FIND_IN_SET($secondary,Secondry_Tag) AND Tag_Type = '$tag'";
                   
              }
              else if($secondary != '' && $primary != '' && $tag == '')
              {

                   $where = "Customer_Id = $customerid AND Primary_Tag = $primary AND FIND_IN_SET($secondary,Secondry_Tag)";
                   
              }
              else if($secondary != '' && $primary == '' && $tag != '')
              {

                   $where = "Customer_Id = $customerid AND FIND_IN_SET($secondary,Secondry_Tag) AND Tag_Type = '$tag'";
                   
              }
              else if($secondary == '' && $primary != '' && $tag != '')
              {

                   $where = "Customer_Id = $customerid AND Primary_Tag = $primary AND Tag_Type = '$tag'";
                   
              }              
              else if($secondary == '' && $primary != '' && $tag == '')
              {

                $where = "Customer_Id = $customerid AND Primary_Tag = $primary";
                

              }
              else if($secondary == '' && $primary == '' && $tag != '')
              {

                $where = "Customer_Id = $customerid AND Tag_Type = '$tag'";
                

              }

              if( $year != '' && $month != '' && $amount != '') 
              {
                  $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
                  $this->db->from("$tableTag");
                  $this->db->where($where);
                  $this->db->group_by('YEAR(Tag_Send_Date)');
                  $this->db->group_by('MONTH(Tag_Send_Date)');
                  $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                  $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                  $query = $this->db->get();
              }
              else if($year == '' && $month != '' && $amount != '')
              {
                  $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month, SUM(Amount) as Amount');
                  $this->db->from("$tableTag");
                  $this->db->where($where);                 
                  $this->db->group_by('MONTH(Tag_Send_Date)');                  
                  $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                  $query = $this->db->get();

              } 
              else if($year != '' && $month == '' && $amount != '')
              {
                  $this->db->select('YEAR(Tag_Send_Date) as Year, SUM(Amount) as Amount');
                  $this->db->from("$tableTag");
                  $this->db->where($where);                 
                  $this->db->group_by('YEAR(Tag_Send_Date)');                  
                  $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                  $query = $this->db->get();

              }

              else if( $year != '' && $month != '' && $amount == '') 
              {
                  $this->db->select('YEAR(Tag_Send_Date) as Year, DATE_FORMAT(Tag_Send_Date,"%b") as Month');
                  $this->db->from("$tableTag");
                  $this->db->where($where);
                  $this->db->group_by('YEAR(Tag_Send_Date)');
                  $this->db->group_by('MONTH(Tag_Send_Date)');
                  $this->db->order_by('YEAR(Tag_Send_Date)','ASC');
                  $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                  $query = $this->db->get();
              }
              else if( $year != '' && $month == '' && $amount == '') 
              {
                  $this->db->select('YEAR(Tag_Send_Date) as Year');
                  $this->db->from("$tableTag");
                  $this->db->where($where);
                  $this->db->group_by('YEAR(Tag_Send_Date)');                  
                  $this->db->order_by('YEAR(Tag_Send_Date)','ASC');                  
                  $query = $this->db->get();
              }
              else if($year == '' && $month != '' && $amount == '')
              {
                  $this->db->select('DATE_FORMAT(Tag_Send_Date,"%b") as Month');
                  $this->db->from("$tableTag");
                  $this->db->where($where);                 
                  $this->db->group_by('MONTH(Tag_Send_Date)');                  
                  $this->db->order_by('MONTH(Tag_Send_Date)','ASC');
                  $query = $this->db->get();

              } 

              //echo $this->db->last_query(); die;

              return $query->result();
               

    }

    public function get_report_details($customerid, $reportid)
    {
        $query = $this->db->get_where('tbl_report',array('Id' => $reportid, 'Customer_Id' => $customerid));

         if($query->num_rows() > 0)
         {

           return $query->row();
         }
         else
         {
             return false;

         }

    }

    public function get_primetag_name($table, $id)
    {
        return $this->db->get_where($table, array('Id' => $id))->row()->Prime_Name; 
    }

    public function get_secondaryag_name($table, $id)
    {
        return $this->db->get_where($table, array('Id' => $id))->row()->Secondary_Name; 
    }

}
