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

class Companycustomer extends CI_Controller
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

    function Companycustomer()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("companycustomer_model");
        
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
    
    public function index()
        {
            $data = '';
            $data['searchkeyword'] = '';
    		$data['PageTitle'] = "Zoddl | companycustomer";
    		$this->load->view('admin/template/header2',$data);	
    		$this->load->view('admin/template/sidebar');		
    		$this->load->view('admin/companycustomer/index');
    		$this->load->view('admin/template/footer2');	
        }

     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <companycustomerdata>
    * @Description:     <this function will Company Customer listing data to admin panel>
    * @Parameters:      <searchkeyword = ''>
    * @Method:          <No>
    * @Returns:         <Yes>
    * @Return Type:     <JSON Encoded Data>
    */
    
    public function companycustomerdata($searchkeyword = '')
        {  
            
            $list = $this->companycustomer_model->get_datatables($searchkeyword);
            $data = array();
            $no = $_POST['start'];      
            foreach ($list as $companycustomerdata) {          
                $no++;
                $row = array();
                $row[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input type="checkbox" class="checkboxes" value='.$companycustomerdata->Id.' /><span></span></label>';
                $row[] = $companycustomerdata->Customer_Name;
                $row[] = $companycustomerdata->Email_Id;
                $row[] = $companycustomerdata->Phone_No;            
                $row[] = $companycustomerdata->Facebook_Profile;
                if($companycustomerdata->Status=='1'){
                 $row[] = '<span class="btn btn-sm green">Active</span>';   
                }
                elseif($companycustomerdata->Status=='2'){
                    $row[] = '<span class="btn btn-sm red">Inactive</span>';
                }
                elseif($companycustomerdata->Status=='3'){
                    $row[] = '<span class="btn btn-sm yellow">Verified</span>';
                }
                else {
                  $row[] = '<span class="btn btn-sm grey-cascade">Unverified</span>';
                 }
                $row[] = '<div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/changepassword/'.$companycustomerdata->User_Type.'/'.$companycustomerdata->Id).'" title="Change Password"> <i class="fa fa-key"></i></a> </div> <div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/edit/'.$companycustomerdata->User_Type.'/'.$companycustomerdata->Id).'" title="Edit"><i class="fa fa-pencil"></i> </a></div>  <div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/view/'.$companycustomerdata->User_Type.'/'.$companycustomerdata->Id).'" title="View"><i class="fa fa-eye"></i> </a></div><div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('gallery/customergallery/'.$companycustomerdata->User_Type.'/'.$companycustomerdata->Id).'" title="Gallery"> <i class="fa fa-file-image-o"></i> </a></div> <!--<div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('document/customerdocument/'.$companycustomerdata->User_Type.'/'.$companycustomerdata->Id).'" title="Document"> <i class="fa fa-file-o"></i> </a></div>--> <div class="fa-item col-md-2 col-sm-4"><a href="javascript:void();" title="Delete" onclick="delete_customer('."'".$companycustomerdata->Id."'".')"><i class="fa fa-trash"></i> </a></div><div class="btn-group " ><button data-toggle="dropdown" class="btn dropdown-toggle"><i class="fa fa-user"></i><span class="ace-icon fa fa-caret-down icon-on-right"></span></button><ul class="dropdown-menu dropdown-default" style="margin:2px -40px  !important; min-width:100px !important;"><li><a href="javascript:void()" onclick="status_customer('."'".$companycustomerdata->Id."'".','."'1'".')">Active</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$companycustomerdata->Id."'".','."'2'".')">Inactive</a></li></li><li><a href="javascript:void()" onclick="status_customer('."'".$companycustomerdata->Id."'".','."'3'".')">Verified</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$companycustomerdata->Id."'".','."'4'".')">Unverified</a></li></ul></div>';
                            
                $row[] = 'delete'.$companycustomerdata->Id;                    
                $data[] = $row;
            }       
         
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->companycustomer_model->count_all($searchkeyword),
                            "recordsFiltered" => $this->companycustomer_model->count_filtered($searchkeyword),
                            "data" => $data,
                    );      
            
            echo json_encode($output);
        }


    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <search>
    * @Description:     <this function will search company customer data to admin panel>
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
            $this->load->view('admin/companycustomer/index',$data);
            $this->load->view('admin/template/footer2');    
        }
    
    
}
