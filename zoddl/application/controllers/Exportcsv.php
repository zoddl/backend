<?php
error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Exportcsv extends CI_Controller
{
    function Exportcsv()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("appcustomer_model");
        $this->load->model("Gallery_model");
        $this->load->library("form_validation");
        
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
        $data['PageTitle'] = "Zoddl | Image Gallery";
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');    
        $this->load->view('admin/imagegallery/index');
        $this->load->view('admin/template/footer2');  
    }
    
    //csv upload
     public function csv_upload()
    {
        
        $data['PageTitle'] = "Zoddl | Image Data Entry";
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/imagegallery/aa',$data);
        $this->load->view('admin/template/footer2');    
    }

    /*********abhinav function to export Staff Users CSV**********/
    public function export_csvStaff()
    {
          date_default_timezone_set('Asia/calcutta');

        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('All Staff Users');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Staff Users');
        $this->excel->getActiveSheet()->setCellValue('A2', 'Name');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Email');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Phone No');
        $this->excel->getActiveSheet()->setCellValue('D2', 'Alternate Phone No ');
        $this->excel->getActiveSheet()->setCellValue('E2', 'Facebook Profile');
        $this->excel->getActiveSheet()->setCellValue('F2', 'Linkedin Profile');
        $this->excel->getActiveSheet()->setCellValue('G2', 'Twitter Profile');
        $this->excel->getActiveSheet()->setCellValue('H2', 'Aadhar No');
        $this->excel->getActiveSheet()->setCellValue('I2', 'Skype Id');
        
        //merge cell A1 until C1
        $this->excel->getActiveSheet()->mergeCells('A1:I1');
        //set aligment to center for that merged cell (A1 to C1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');

        for($col = ord('A'); $col <= ord('I'); $col++){
          //set column dimension
            $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
         //change the font size
            $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //retrive contries table data
        $sql = "SELECT name,email,phone_number,alt_phone_number,facebook_profile,linked_in_profile,twitter_handle,aadhar_no,skype_id FROM `tbl_users` WHERE `user_type`='2'";
        $rs = $this->db->query($sql);

        $exceldata=array();
        foreach ($rs->result_array() as $row){
            $exceldata[] = $row;
        }
                //Fill data 
        $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');

        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       


        $filename='Staff Users'.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        $objWriter->save('php://output');

    }//ends abhinav staff user export function


 /*********abhinav function to export Staff Users Excel**********/
      public function export_excelStaff()
    {
         $this->load->library('Excel');
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")->setLastModifiedBy("Maarten Balliauw")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document")->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")->setKeywords("office 2007 openxml php")->setCategory("Test result file");
        
      
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Name')->setCellValue('B1', 'Email')->setCellValue('C1', 'Phone No')->setCellValue('D1', 'Alternate Phone No')->setCellValue('E1', 'Facebook Profile')->setCellValue('F1', 'Linkedin Profile')->setCellValue('G1', 'Twitter Profile')->setCellValue('H1', 'Aadhar No')->setCellValue('I1', 'Skype Id');
        $i         = 2;

        $sql = "SELECT name,email,phone_number,alt_phone_number,facebook_profile,linked_in_profile,twitter_handle,aadhar_no,skype_id FROM `tbl_users` WHERE `user_type`='2'";
        $rs = $this->db->query($sql);

        $user_data = $rs->result();
        foreach ($user_data as $row) 
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $row->name)->setCellValue('B' . $i, $row->email)->setCellValue('C' . $i, $row->phone_number)->setCellValue('D' . $i, $row->alt_phone_number)->setCellValue('E' . $i, $row->facebook_profile)->setCellValue('F' . $i, $row->linked_in_profile)->setCellValue('G' . $i, $row->twitter_handle)->setCellValue('H' . $i, $row->aadhar_no)->setCellValue('I' . $i, $row->skype_id);
            $i++;
        }
        
    
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Staff Users');    
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Staff_users.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
          
    }//ends abhinav function to export Staff Users Excel

  /*********abhinav function to export General Users CSV**********/
    public function export_csvGeneral()
    {
      date_default_timezone_set('Asia/calcutta');

        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('All General Users');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'General Users');
        $this->excel->getActiveSheet()->setCellValue('A2', 'Customer Name');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Email');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Phone No');
        $this->excel->getActiveSheet()->setCellValue('D2', 'Alternate Phone No ');
        $this->excel->getActiveSheet()->setCellValue('E2', 'Facebook Profile');
        $this->excel->getActiveSheet()->setCellValue('F2', 'Linkedin Profile');
        $this->excel->getActiveSheet()->setCellValue('G2', 'Twitter Profile');
        $this->excel->getActiveSheet()->setCellValue('H2', 'Aadhar No');
        $this->excel->getActiveSheet()->setCellValue('I2', 'Skype Id');
        
        //merge cell A1 until C1
        $this->excel->getActiveSheet()->mergeCells('A1:I1');
        //set aligment to center for that merged cell (A1 to C1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');

        for($col = ord('A'); $col <= ord('I'); $col++){
          //set column dimension
            $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
         //change the font size
            $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //retrive contries table data
        $sql = "SELECT Customer_Name,Email_Id,Phone_No,Alt_Phone_No,Facebook_Profile,Linked_In_Profile,Twitter_Handle,Aadhar_No,Skype_Id FROM `tbl_customer` WHERE `User_Type`='2'";
        $rs = $this->db->query($sql);

        $exceldata=array();
        foreach ($rs->result_array() as $row){
            $exceldata[] = $row;
        }
                //Fill data 
        $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');

        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       


        $filename='General Users'.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        $objWriter->save('php://output');
    }//ends abhinav General user export function

    /*********abhinav function to export General Users Excel**********/

      public function export_excelGeneral()
    {
        $this->load->library('Excel');
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")->setLastModifiedBy("Maarten Balliauw")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document")->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")->setKeywords("office 2007 openxml php")->setCategory("Test result file");
        
      
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Customer Name')->setCellValue('B1', 'Email')->setCellValue('C1', 'Phone No')->setCellValue('D1', 'Alternate Phone No')->setCellValue('E1', 'Facebook Profile')->setCellValue('F1', 'Linkedin Profile')->setCellValue('G1', 'Twitter Profile')->setCellValue('H1', 'Aadhar No')->setCellValue('I1', 'Skype Id');
        $i         = 2;

        $sql = "SELECT Id,Customer_Name,Email_Id,Phone_No,Alt_Phone_No,Facebook_Profile,Linked_In_Profile,Twitter_Handle,Aadhar_No,Skype_Id FROM `tbl_customer` WHERE `User_Type`='2'";
        $rs = $this->db->query($sql);

        $user_data = $rs->result();
        foreach ($user_data as $row) 
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $row->Customer_Name)->setCellValue('B' . $i, $row->Email_Id)->setCellValue('C' . $i, $row->Phone_No)->setCellValue('D' . $i, $row->Alt_Phone_No)->setCellValue('E' . $i, $row->Facebook_Profile)->setCellValue('F' . $i, $row->Linked_In_Profile)->setCellValue('G' . $i, $row->Twitter_Handle)->setCellValue('H' . $i, $row->Aadhar_No)->setCellValue('I' . $i, $row->Skype_Id);
            $i++;
        }
        
    
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('General Users');    
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="General_users.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    
          
    }//ends abhinav function to export General Users Excel

    /*********abhinav function to export Company Users CSV**********/
    public function export_csvCompany()
    {
      date_default_timezone_set('Asia/calcutta');

        $this->load->library('excel');
        $this->excel->setActiveSheetIndex(0);
        //name the worksheet
        $this->excel->getActiveSheet()->setTitle('All Company Users');
        //set cell A1 content with some text
        $this->excel->getActiveSheet()->setCellValue('A1', 'Company Users');
        $this->excel->getActiveSheet()->setCellValue('A2', 'Name');
        $this->excel->getActiveSheet()->setCellValue('B2', 'Email');
        $this->excel->getActiveSheet()->setCellValue('C2', 'Phone No');
        $this->excel->getActiveSheet()->setCellValue('D2', 'Alternate Phone No ');
        $this->excel->getActiveSheet()->setCellValue('E2', 'Facebook Profile');
        $this->excel->getActiveSheet()->setCellValue('F2', 'Linkedin Profile');
        $this->excel->getActiveSheet()->setCellValue('G2', 'Twitter Profile');
        $this->excel->getActiveSheet()->setCellValue('H2', 'Aadhar No');
        $this->excel->getActiveSheet()->setCellValue('I2', 'Skype Id');
        
        //merge cell A1 until C1
        $this->excel->getActiveSheet()->mergeCells('A1:I1');
        //set aligment to center for that merged cell (A1 to C1)
        $this->excel->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        //make the font become bold
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setBold(true);
        $this->excel->getActiveSheet()->getStyle('A1')->getFont()->setSize(12);
        $this->excel->getActiveSheet()->getStyle('A1')->getFill()->getStartColor()->setARGB('#333');

        for($col = ord('A'); $col <= ord('I'); $col++){
          //set column dimension
            $this->excel->getActiveSheet()->getColumnDimension(chr($col))->setAutoSize(true);
         //change the font size
            $this->excel->getActiveSheet()->getStyle(chr($col))->getFont()->setSize(12);
            $this->excel->getActiveSheet()->getStyle(chr($col))->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        }
        //retrive contries table data
        $sql = "SELECT name,email,phone_number,alt_phone_number,facebook_profile,linked_in_profile,twitter_handle,aadhar_no,skype_id FROM `tbl_users` WHERE `user_type`='3'";
        $rs = $this->db->query($sql);

        $exceldata=array();
        foreach ($rs->result_array() as $row){
            $exceldata[] = $row;
        }
                //Fill data 
        $this->excel->getActiveSheet()->fromArray($exceldata, null, 'A3');

        $this->excel->getActiveSheet()->getStyle('A3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('B3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('C3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('D3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('E3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('F3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('G3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('H3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('I3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $this->excel->getActiveSheet()->getStyle('J3')->getAlignment()->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
       


        $filename='Company Users'.'.xls'; //save our workbook as this file name
        header('Content-Type: application/vnd.ms-excel'); //mime type
        header('Content-Disposition: attachment;filename="'.$filename.'"'); //tell browser what's the file name
        header('Cache-Control: max-age=0'); //no cache
        //if you want to save it as .XLSX Excel 2007 format
        $objWriter = PHPExcel_IOFactory::createWriter($this->excel, 'Excel5');  
        $objWriter->save('php://output');
    }//ends abhinav Company user export function

        /*********abhinav function to export General Users Excel**********/

      public function export_excelCompany()
    {
        $this->load->library('Excel');
        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();
        
        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Maarten Balliauw")->setLastModifiedBy("Maarten Balliauw")->setTitle("Office 2007 XLSX Test Document")->setSubject("Office 2007 XLSX Test Document")->setDescription("Test document for Office 2007 XLSX, generated using PHP classes.")->setKeywords("office 2007 openxml php")->setCategory("Test result file");
        
      
        // Add some data
        $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A1', 'Name')->setCellValue('B1', 'Email')->setCellValue('C1', 'Phone No')->setCellValue('D1', 'Alternate Phone No')->setCellValue('E1', 'Facebook Profile')->setCellValue('F1', 'Linkedin Profile')->setCellValue('G1', 'Twitter Profile')->setCellValue('H1', 'Aadhar No')->setCellValue('I1', 'Skype Id');
        $i         = 2;

       $sql = "SELECT name,email,phone_number,alt_phone_number,facebook_profile,linked_in_profile,twitter_handle,aadhar_no,skype_id FROM `tbl_users` WHERE `user_type`='3'";
        $rs = $this->db->query($sql);

        $user_data = $rs->result();
        foreach ($user_data as $row) 
        {
            $objPHPExcel->setActiveSheetIndex(0)->setCellValue('A' . $i, $row->name)->setCellValue('B' . $i, $row->email)->setCellValue('C' . $i, $row->phone_number)->setCellValue('D' . $i, $row->alt_phone_number)->setCellValue('E' . $i, $row->facebook_profile)->setCellValue('F' . $i, $row->linked_in_profile)->setCellValue('G' . $i, $row->twitter_handle)->setCellValue('H' . $i, $row->aadhar_no)->setCellValue('I' . $i, $row->skype_id);
            $i++;
        }
        
    
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Company Users');    
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);
        
        
        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Company_users.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');
        
        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
    
          
    }//ends abhinav function to export Company Users Excel
    
}
