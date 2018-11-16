<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Staffcustomer extends CI_Controller
{
    function Staffcustomer()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("staffcustomer_model");
        //$this->load->library('form_validation');
        
        /*cache control*/
	    $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		check_login();

    }
    
    function index()
    {
        $data = '';
        $data['searchkeyword'] = '';
		$data['PageTitle'] = "Zoddl | staffcustomer";
		$this->load->view('admin/template/header2',$data);	
		$this->load->view('admin/template/sidebar');		
		$this->load->view('admin/staffcustomer/index');
		$this->load->view('admin/template/footer2');	
    }
    
    public function staffcustomerdata($searchkeyword = '')
    {
       
        
        $list = $this->staffcustomer_model->get_datatables($searchkeyword);
        $data = array();
        $no = $_POST['start'];      
        foreach ($list as $staffcustomerdata) {          
            $no++;
            $row = array();
            $row[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input type="checkbox" class="checkboxes" value='.$staffcustomerdata->id.' /><span></span></label>';
            $row[] = $staffcustomerdata->name;
            $row[] = $staffcustomerdata->email;
            $row[] = $staffcustomerdata->phone_number;            
            $row[] = $staffcustomerdata->facebook_profile;            

            if($staffcustomerdata->status=='1'){
             $row[] = '<span class="btn btn-sm green">Active</span>';   
            }            
            else {
              $row[] = '<span class="btn btn-sm red">Inactive</span>';
             }

             if($this->session->userdata('user_type') == 1)
             {

                $delete = '<li><a href="javascript:void();" title="Delete" onclick="delete_customer('." '".$staffcustomerdata->id."' ".')">Delete</a></li>';
             }
             else
             {

                $delete = '';
             }
            $row[] = '<div class="btn-group "> <button data-toggle="dropdown" class="btn dropdown-toggle"><i class="fa fa-user"></i><span class="ace-icon fa fa-caret-down icon-on-right"></span></button> <ul class="dropdown-menu dropdown-default" style="margin:2px -40px !important; min-width:100px !important;"> <li><a href="javascript:void()" onclick="status_customer('." '".$staffcustomerdata->id."' ".','." '1' ".')">Active</a></li><li><a href="javascript:void()" onclick="status_customer('." '".$staffcustomerdata->id."' ".','." '2' ".')">Inactive</a></li><li><a href="'.base_url('customer/useredit/'.$staffcustomerdata->user_type.'/'.$staffcustomerdata->id).'" title="Edit"> Edit </a></li><li><a href="'.base_url('customer/userview/'.$staffcustomerdata->user_type.'/'.$staffcustomerdata->id).'" title="View">View </a></li>'.$delete.'<li><a href="'.base_url('customer/userchangepassword/'.$staffcustomerdata->user_type.'/'.$staffcustomerdata->id).'" title="Change Password"> Change Password</a></li></ul></div>';
                        
            $row[] = 'delete'.$staffcustomerdata->id;                    
            $data[] = $row;
        }       
     
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->staffcustomer_model->count_all($searchkeyword),
                        "recordsFiltered" => $this->staffcustomer_model->count_filtered($searchkeyword),
                        "data" => $data,
                );      
        
        echo json_encode($output);
    }

    function search($searchkeyword = '')
    {
        $data = '';
        $data['searchkeyword'] = $searchkeyword;
        $data['PageTitle'] = "Zoddl | staffuser";
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/staffcustomer/index',$data);
        $this->load->view('admin/template/footer2');    
    }
    
    
}
