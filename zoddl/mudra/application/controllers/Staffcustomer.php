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
            $row[] = '<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline"><input type="checkbox" class="checkboxes" value='.$staffcustomerdata->Id.' /><span></span></label>';
            $row[] = $staffcustomerdata->Customer_Name;
            $row[] = $staffcustomerdata->Email_Id;
            $row[] = $staffcustomerdata->Phone_No;            
            $row[] = $staffcustomerdata->Facebook_Profile;

            if($staffcustomerdata->permission_g == 1)
                {
                  $imagegallery = '<div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('gallery/customergallery/'.$staffcustomerdata->User_Type.'/'.$staffcustomerdata->Id).'" title="Gallery"> <i class="fa fa-file-image-o"></i> </a></div>';
                }
                else
                {
                  $imagegallery = '';

                }

                if($staffcustomerdata->permission_d == 1)
                {

                    $docgallery = '<div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('document/customerdocument/'.$staffcustomerdata->User_Type.'/'.$staffcustomerdata->Id).'" title="Document"> <i class="fa fa-file-o"></i> </a></div>';
                }
                else
                {
                    $docgallery = '';

                }


            if($staffcustomerdata->Status=='1'){
             $row[] = '<span class="btn btn-sm green">Active</span>';   
            }
            elseif($staffcustomerdata->Status=='2'){
                $row[] = '<span class="btn btn-sm red">Inactive</span>';
            }
            elseif($staffcustomerdata->Status=='3'){
                $row[] = '<span class="btn btn-sm yellow">Verified</span>';
            }
            else {
              $row[] = '<span class="btn btn-sm grey-cascade">Unverified</span>';
             }
            $row[] = '<div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/changepassword/'.$staffcustomerdata->User_Type.'/'.$staffcustomerdata->Id).'" title="Change Password"> <i class="fa fa-key"></i></a> </div> <div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/edit/'.$staffcustomerdata->User_Type.'/'.$staffcustomerdata->Id).'" title="Edit"><i class="fa fa-pencil"></i> </a></div>  <div class="fa-item col-md-1 col-sm-4"><a href="'.base_url('customer/view/'.$staffcustomerdata->User_Type.'/'.$staffcustomerdata->Id).'" title="View"><i class="fa fa-eye"></i> </a></div> '.$imagegallery.' '.$docgallery.' <div class="fa-item col-md-2 col-sm-4"><a href="javascript:void();" title="Delete" onclick="delete_customer('."'".$staffcustomerdata->Id."'".')"><i class="fa fa-trash"></i> </a></div><div class="btn-group " ><button data-toggle="dropdown" class="btn dropdown-toggle"><i class="fa fa-user"></i><span class="ace-icon fa fa-caret-down icon-on-right"></span></button><ul class="dropdown-menu dropdown-default" style="margin:2px -40px  !important; min-width:100px !important;"><li><a href="javascript:void()" onclick="status_customer('."'".$staffcustomerdata->Id."'".','."'1'".')">Active</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$staffcustomerdata->Id."'".','."'2'".')">Inactive</a></li></li><li><a href="javascript:void()" onclick="status_customer('."'".$staffcustomerdata->Id."'".','."'3'".')">Verified</a></li><li><a href="javascript:void()" onclick="status_customer('."'".$staffcustomerdata->Id."'".','."'4'".')">Unverified</a></li></ul></div>';
                        
            $row[] = 'delete'.$staffcustomerdata->Id;                    
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
        $data['PageTitle'] = "Zoddl | appcustomer";
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/staffcustomer/index',$data);
        $this->load->view('admin/template/footer2');    
    }
    
    
}
