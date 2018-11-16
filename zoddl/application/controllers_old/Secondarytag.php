<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
* Secondarytag Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description Secondarytag Controller
* @link http://zoddl.com/secondarytag
*/

class Secondarytag extends CI_Controller
{
    
    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Secondarytag>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Secondarytag()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("secondarytag_model");        
        
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
    * @Description:     <this function will Secondary Tag listing view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */
    
    public function index()
        {
            $data = '';
    		$data['PageTitle'] = "Zoddl | Secondary Tag";
    		$this->load->view('admin/template/header2',$data);	
    		$this->load->view('admin/template/sidebar');		
    		$this->load->view('admin/secondarytag/index');
    		$this->load->view('admin/template/footer2');	
        }
    
     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <appcustomerdata>
    * @Description:     <this function will Secondary Tag listing data to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <Yes>
    * @Return Type:     <JSON Encoded Data>
    */


    public function secondarytagdata()
        {   
            $list = $this->secondarytag_model->get_datatables();
            $data = array();
            $no = $_POST['start'];      
            foreach ($list as $secondarytagdata) { 
                
                if(!empty($secondarytagdata->Customer_Id))
                {
                    $getCustomerdata = $this->secondarytag_model->get_customer($secondarytagdata->Customer_Id);
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

                $no++;
                $row = array();
                $row[] = $getCustomerName;
                $row[] = $secondarytagdata->Secondary_Name;            
                $row[] = 'delete'.$secondarytagdata->Id;                    
                $data[] = $row;
            }       
         
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->secondarytag_model->count_all(),
                            "recordsFiltered" => $this->secondarytag_model->count_filtered(),
                            "data" => $data,
                    );      
            
            echo json_encode($output);
        }
    
}
