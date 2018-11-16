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
                $row[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input type="checkbox" class="checkboxes" value='.$companycustomerdata->id.' /><span></span></label>';
                $row[] = $companycustomerdata->name;
                $row[] = $companycustomerdata->email;
                $row[] = $companycustomerdata->phone_number;            
                $row[] = $companycustomerdata->facebook_profile;

                
                if($companycustomerdata->status=='1'){
                 $row[] = '<span class="btn btn-sm green">Active</span>';   
                }
                else{
                    $row[] = '<span class="btn btn-sm red">Inactive</span>';
                }
                
                $row[] = '<div class="btn-group "> <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="fa fa-user"></i><span class="ace-icon fa fa-caret-down icon-on-right"></span></button> <ul class="dropdown-menu dropdown-default" style="margin:2px -40px !important; min-width:100px !important;"> <li><a href="javascript:void()" onclick="status_customer('." '".$companycustomerdata->id."' ".','." '1' ".')">Active</a></li><li><a href="javascript:void()" onclick="status_customer('." '".$companycustomerdata->id."' ".','." '2' ".')">Inactive</a></li><li><a href="'.base_url('customer/useredit/'.$companycustomerdata->user_type.'/'.$companycustomerdata->id).'" title="Edit"> Edit </a></li><li><a href="'.base_url('customer/userview/'.$companycustomerdata->user_type.'/'.$companycustomerdata->id).'" title="View">View </a></li><li><a href="javascript:void();" title="Delete" onclick="delete_customer('." '".$companycustomerdata->id."' ".')">Delete</a></li><li><a href="'.base_url('customer/userchangepassword/'.$companycustomerdata->user_type.'/'.$companycustomerdata->id).'" title="Change Password"> Change Password</a></li></ul></div>';
                            
                $row[] = 'delete'.$companycustomerdata->id;                    
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
            $data['PageTitle'] = "Zoddl | companyuser";
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/companycustomer/index',$data);
            $this->load->view('admin/template/footer2');    
        }
    
    
}
