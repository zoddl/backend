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
            $row[] = '<a href="mailto:'.$appcustomerdata->Email_Id.'" title="Email">'.$appcustomerdata->Email_Id.' </a>';
            $row[] = $appcustomerdata->Phone_No;            
            $row[] = $appcustomerdata->Facebook_Profile;

            if($appcustomerdata->permission_g == 1)
            {
              $imagegallery = '<div class="fa-item col-xs-1"><a href="'.base_url('gallery/customergallery/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="Gallery"> <i class="fa fa-file-image-o"></i> </a></div>';
            }
            else
            {
              $imagegallery = '';

            }

            if($appcustomerdata->permission_d == 1)
            {

                $docgallery = '<div class="fa-item col-xs-1"><a href="'.base_url('document/customerdocument/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="Document"> <i class="fa fa-file-o"></i> </a></div>';
            }
            else
            {
                $docgallery = '';

            }
            if($appcustomerdata->permission_r == 1)
            {

                $report = '<div class="fa-item col-xs-1"><a href="'.base_url('report/genratereport/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" title="Report"> <i class="fa fa-line-chart"></i> </a></div>';
            }
            else
            {
                $report = '';

            }

            if($this->session->userdata('user_type') == 1)
             {

                $delete = '<li><a href="javascript:void();" title="Delete" onclick="delete_customer('." '".$appcustomerdata->Id."' ".')">Delete</a></li>';
             }
             else
             {

                $delete = '';
             }


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
            $row[] = '<div class="fa-item col-xs-1"><a href="#responsive" data-toggle="modal" title="Send verification Mail" onclick="sendverificationmail('.$appcustomerdata->Id.');"> <i class="fa fa-send-o"></i> </a></div>'.$imagegallery.' '.$docgallery.' '.$report.'<div class="fa-item col-xs-1"><a href="'.base_url('entry/index/'.$appcustomerdata->Id).'" title="Add Image Entry"> <i class="fa fa-plus"></i> I </a></div><div class="fa-item col-xs-1"><a href="'.base_url('entrydocument/index/'.$appcustomerdata->Id).'" title="Add Document Entry"> <i class="fa fa-plus"></i> D </a></div><div class="col-xs-1"> <div class="btn-group " > <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="fa fa-user"></i><span class="ace-icon fa fa-caret-down icon-on-right"></span></button> <ul class="dropdown-menu dropdown-default" style="margin:2px -40px !important; min-width:100px !important;"> <li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'1'".')">Active</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'2'".')">Inactive</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'3'".')">Verified</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$appcustomerdata->Id."'".','."'4'".')">Unverified</a></li><li><a href="'.base_url('customer/edit/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" >Edit</a></li><li><a href="'.base_url('customer/view/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'">View</a></li><li><a href="'.base_url('customer/changepassword/'.$appcustomerdata->User_Type.'/'.$appcustomerdata->Id).'" >Change Password</a></li>'.$delete.'</ul> </div></div>';
                        
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

    public function sendverificationmail()
    {
            
            $email = '';

            $customerId = $this->input->post('customerid');
            $message = $this->input->post('message');

            $isEmail = $this->db->get_where('tbl_customer', array('Id'=>$customerId));

            if($isEmail->num_rows() > 0)
            {

                $email = $isEmail->row()->Email_Id;
            }




            if($email != '')
            {

                    $verificationCode = time();
                    $this->db->where('Email_Id', $email);
                    $this->db->update('tbl_customer', array('Varification_Key' => $verificationCode));

                    //echo $this->db->last_query(); die;

                    $to = $email;
                    $tablecustomer = 'tbl_customer';
                    $subject = 'Verification Mail';
                    

                    $cDetail = $this->db->get_where($tablecustomer, array('Email_Id' => $email))->row();

                    $data['cid'] = $cDetail->Id;
                    $data['vcode'] = $cDetail->Varification_Key;
                    $data['message'] = $message;

                    $body = $this->load->view('mailers/admin-verification-mail', $data, true);
                    
                    $send = Send_Mail($to, $subject, $body);

                    if($send)
                    {
                        return 1;
                    }
                    else
                    {
                        return 0;

                    }

            }
            else
            {
                return 0;
            }

    }
    
}
