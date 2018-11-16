<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Appcustomer Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description App Type Customer Controller
* @link http://zoddl.com/appcustomer
*/


class Appcustomer extends CI_Controller
{
   /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Appcustomer>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Appcustomer()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("appcustomer_model");        
        
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
    * @Description:     <this function will App Customer listing view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */
    
    public function index()
    {
        $data = '';
        $data['searchkeyword'] = '';
		$data['PageTitle'] = "Zoddl | appcustomer";
		$this->load->view('admin/template/header2',$data);	
		$this->load->view('admin/template/sidebar');		
		$this->load->view('admin/appcustomer/index', $data);
		$this->load->view('admin/template/footer2');	
    }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <appcustomerdata>
    * @Description:     <this function will App Customer listing data to admin panel>
    * @Parameters:      <searchkeyword = ''>
    * @Method:          <No>
    * @Returns:         <Yes>
    * @Return Type:     <JSON Encoded Data>
    */
    
    public function appcustomerdata($searchkeyword = '')
    {
       
        
        $list = $this->appcustomer_model->get_datatables($searchkeyword);
        $data = array();
        $no = $_POST['start'];      
        foreach ($list as $appcustomerdata) {          
            $no++;
            $row = array();
            $row[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input type="checkbox" class="checkboxes" value='.$appcustomerdata->Id.' /><span></span></label>';
            $row[] = $appcustomerdata->Customer_Name;
            $row[] = $appcustomerdata->Email_Id;
            $row[] = $appcustomerdata->Phone_No;            
            $row[] = $appcustomerdata->Facebook_Profile;
            if($appcustomerdata->Status=='1'){
             $row[] = '<span class="btn btn-sm green">Active</span>';   
            }
            elseif($appcustomerdata->Status=='2'){
                $row[] = '<span class="btn btn-sm red">Inactive</span>';
            }
            elseif($appcustomerdata->Status=='3'){
                $row[] = '<span class="btn btn-sm yellow">Verified</span>';
            }
            else {
              $row[] = '<span class="btn btn-sm grey-cascade">Unverified</span>';
             }
            $row[] = '<div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/changepassword/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="Change Password"> <i class="fa fa-key"></i></a> </div> <div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/edit/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="Edit"><i class="fa fa-pencil"></i> </a></div>  <div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/view/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="View"><i class="fa fa-eye"></i> </a></div><div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('gallery/customergallery/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="Gallery"> <i class="fa fa-file-image-o"></i> </a></div> <!--<div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('document/customerdocument/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="Document"> <i class="fa fa-file-o"></i> </a></div>--> <div class="fa-item col-md-2 col-sm-4"><a href="javascript:void();" title="Delete" onclick="delete_customer('."'".$appcustomerdata->Id."'".')"><i class="fa fa-trash"></i> </a></div><div class="btn-group " ><button data-toggle="dropdown" class="btn dropdown-toggle"><i class="fa fa-user"></i><span class="ace-icon fa fa-caret-down icon-on-right"></span></button><ul class="dropdown-menu dropdown-default" style="margin:2px -40px  !important; min-width:100px !important;"><li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'1'".')">Active</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'2'".')">Inactive</a></li></li><li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'3'".')">Verified</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'4'".')">Unverified</a></li></ul></div>';
                        
            $row[] = 'delete'.$appcustomerdata->Id;                    
            $data[] = $row;
        }       
     
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->appcustomer_model->count_all($searchkeyword),
                        "recordsFiltered" => $this->appcustomer_model->count_filtered($searchkeyword),
                        "data" => $data,
                );      
        
        echo json_encode($output);
    }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <search>
    * @Description:     <this function will search app customer data to admin panel>
    * @Parameters:      <searchkeyword = ''>
    * @Method:          <No>
    * @Returns:         <No>
    * @Return Type:     <No>
    */

    public function search($searchkeyword = '')
    {
        $data = '';
        $data['searchkeyword'] = $searchkeyword;
        $data['PageTitle'] = "Zoddl | appcustomer";
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/appcustomer/index',$data);
        $this->load->view('admin/template/footer2');    
    }
    
}
