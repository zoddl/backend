<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tag extends CI_Controller
{
    function Tag()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("tag_model");
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
		$data['PageTitle'] = "Zoddl | Tag";
		$this->load->view('admin/template/header2',$data);	
		$this->load->view('admin/template/sidebar');		
		$this->load->view('admin/tag/index');
		$this->load->view('admin/template/footer2');	
    }
    
    public function tagdata()
    {
       
        
        $list = $this->tag_model->get_datatables();
        $data = array();
        $no = $_POST['start'];      
        foreach ($list as $tagdata) {          
             if(!empty($tagdata->Customer_Id))
            {
                $getCustomerdata = $this->tag_model->get_customer($tagdata->Customer_Id);
                if($getCustomerdata)
                {

                        $getCustomerName = $getCustomerdata;
                }
                else
                {

                        $getCustomerName = '';
                }
            }
            else
            {
                $getCustomerName = '';

            }

            if(!empty($tagdata->Primary_Tag))
            {
                $getPrimarydata = $this->tag_model->get_primary($tagdata->Primary_Tag);
                if($getPrimarydata)
                {

                        $getPrimaryName = $getPrimarydata;
                }
                else
                {

                        $getPrimaryName = '';
                }
            }
            else
            {
                $getPrimaryName = '';

            }

            if(!empty($tagdata->Secondry_Tag))
            {
                $getSecondrydata = $this->tag_model->get_secondry($tagdata->Secondry_Tag);
                if($getSecondrydata)
                {

                        $getSecondryName = $getSecondrydata;
                }
                else
                {

                        $getSecondryName = '';
                }
            }
            else
            {
                $getSecondryName = '';

            }

            $no++;
            $row = array();
            $row[] = $getCustomerName;
            $row[] = $getPrimaryName;
            $row[] = $getSecondryName;
            if($tagdata->Tag_Status=='1'){
             $row[] = '<span class="label label-success arrowed-in arrowed-in-right">Approved</span>';   
            }
            elseif($tagdata->Tag_Status=='2'){
                $row[] = '<span class="label label-warning arrowed ">Pending</span>';
            }
            else {
              $row[] = '<span class="label label-danger arrowed">Decline</span>';
             }
            $row[] ='<div class="btn-group" ><button data-toggle="dropdown" class="btn dropdown-toggle"><i class="glyphicon glyphicon-user"></i><span class="ace-icon fa fa-caret-down icon-on-right"></span></button><ul class="dropdown-menu dropdown-default" style="margin:2px -40px  !important; min-width:100px !important;"><li><a href="javascript:void()" onclick="status_tag('."'".$tagdata->Id."'".','."'1'".')">Approved</a></li><li><a href="javascript:void()" onclick="status_tag('."'".$tagdata->Id."'".','."'2'".')">Pending</a></li></li><li><a href="javascript:void()" onclick="status_tag('."'".$tagdata->Id."'".','."'3'".')">Decline</a></li></ul></div>';
            $row[] = 'delete'.$tagdata->Id;                    
            $data[] = $row;
        }       
     
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->tag_model->count_all(),
                        "recordsFiltered" => $this->tag_model->count_filtered(),
                        "data" => $data,
                );      
        
        echo json_encode($output);
    }
   
    function changestatus($id,$status)
    {
        
        if( $this->tag_model->changestatus($id,$status) )
        {
            echo 'success';
        }
        
    }
   
    
}
