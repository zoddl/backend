<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Report Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description Report Controller
* @link http://zoddl.com/report
*/

class Report extends CI_Controller
{
    
    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Report>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Report()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("report_model");
        $this->load->model('Customer_Api_Model');
        
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
    * @Description:     <this function will Customer report listing view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    public function genratereport($usertype, $custmerid)
        {
                $data = '';
                $data['PageTitle'] = "Zoddl | Customer report";
                $data['custmerid'] = $custmerid;
                $data['usertypeid'] = $usertype;

                if($usertype == 1)
                {

                    $data['usertype'] = "staffcustomer";
                }
                elseif($usertype == 2)
                {
                    $data['usertype'] = "appcustomer";

                }else
                {
                    $data['usertype'] = "companycustomer";

                }

            //$data['CustomerPrimaryTag'] = $CustomerPrimaryTag = $this->report_model->get_customerprimarytag($custmerid);
            //$data['CustomerSecondaryTag'] = $CustomerSecondaryTag = $this->report_model->get_customersecondarytag($custmerid);
            
            if($custmerid){
        
                $tableprimetag = "tbl_primary_tag";
                $tabledocprimetag = "tbl_doc_primary_tag";
                $getcustomerprimetag = $this->Customer_Api_Model->get_customer_prime_tag($tableprimetag, $custmerid);
                $getdocprimetag = $this->Customer_Api_Model->get_customer_prime_tag($tabledocprimetag, $custmerid);
                $primarytaglist = array();
                $docprimarytaglist = array();
                $primary = array();
        
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

                $primary = array_merge($primarytaglist,$docprimarytaglist);               
            }

            if($custmerid){
        
                $tablesecondtag = "tbl_secondary_tag";
                $tabledocsecondtag = "tbl_doc_secondary_tag";
                $getcustomersecondtag = $this->Customer_Api_Model->get_customer_second_tag($tablesecondtag, $custmerid);
                $getcustomerdocsecondtag = $this->Customer_Api_Model->get_customer_second_tag($tabledocsecondtag, $custmerid);
                $secondtaglist = array();
                $docsecondtaglist = array();
                $secondary = array();                
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

                $secondary = array_merge($secondtaglist,$docsecondtaglist);
                
            }

            //pr($primary);
            //pr($secondary);
             //die;

            $data['CustomerPrimaryTag'] = $primary;
            $data['CustomerSecondaryTag'] = $secondary;
            $data['Authtoken'] = $this->db->get_where('tbl_customer',array('Id' => $custmerid))->row()->Auth_Token;
            //pr($data); die;
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/report/index',$data);
            $this->load->view('admin/template/footer2');

        }
    
    
}
