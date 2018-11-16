<?php
defined('BASEPATH') OR exit('No direct script access allowed');


/**
* Gallery Class
*
* @package Zoddl
* @author Antaryami
* @version 1.0
* @description Gallery Controller
* @link http://zoddl.com/gallery
*/

class Gallery extends CI_Controller
{
    
    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <Gallery>
    * @Description:     <this function load models>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Gallery()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("gallery_model");
        
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
    * @Function:        <customergallery>
    * @Description:     <this function will Customer gallery listing view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    public function customergallery($usertype, $custmerid)
        {
                $data = '';
                $data['PageTitle'] = "Zoddl | Customer gallery";
                $data['customerid'] = $custmerid;
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

            $data['CustomerPrimaryTag'] = $CustomerPrimaryTag = $this->gallery_model->get_customerprimarytag($custmerid);
            $data['CustomerSecondaryTag'] = $CustomerSecondaryTag = $this->gallery_model->get_customersecondarytag($custmerid);
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/gallery/index',$data);
            $this->load->view('admin/template/footer2');

        }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <gallerydata>
    * @Description:     <this function will Customer gallery listing data to admin panel>
    * @Parameters:      <$usertypeid, $customerid>
    * @Method:          <No>
    * @Returns:         <YES>
    * @Return Type:     <JSON ENCODED DATA>
    */
    
    public function gallerydata($usertypeid, $customerid)
        {       
            
            $list = $this->gallery_model->get_datatables($customerid);
            $data = array();
            $getGalleryimage = array();
            $no = $_POST['start'];      
            foreach ($list as $gallerydata) {  


                if(!empty($gallerydata->Primary_Tag))
                {
                    $getprimarydata = $this->gallery_model->get_primary($gallerydata->Primary_Tag);
                    if($getprimarydata)
                    {

                            $getprimaryName = $getprimarydata;
                    }
                    else
                    {

                            $getprimaryName = '';
                    }
                }
                else
                {
                    $getprimaryName = '';

                }

                if(!empty($gallerydata->Primary_Tag))
                {
                    $getGallerydata = $this->gallery_model->get_gallery_image($gallerydata->Primary_Tag);
                    if($getGallerydata)
                    {
                        
                           $getGalleryimage =   $getGallerydata;
                            
                    }
                    else
                    {

                            $getGalleryimage = '';
                    }
                }
                else
                {
                    $getGalleryimage = '';

                }

                
                $no++;
                $row = array();
                $row[] = $getprimaryName;
                $row[] = $getGalleryimage; 
                $row[] ='<div class="fa-item col-md-6 col-sm-6"><a href="'.base_url('gallery/viewdetailprimarytag/'.$usertypeid.'/'.$customerid.'/'.$gallerydata->Primary_Tag).'" title="View Detail" class="btn green">View Detail</a> </div>';
                $row[] = 'delete'.$gallerydata->Id;                    
                $data[] = $row;
            }       
         
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->gallery_model->count_all($customerid),
                            "recordsFiltered" => $this->gallery_model->count_filtered($customerid),
                            "data" => $data,
                    );      
            
            echo json_encode($output);
        }

    /** Start search by primary tag data **/


     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <searchbyprimarytag>
    * @Description:     <this function Search by Primary Tag listing View to admin panel>
    * @Parameters:      <$usertype, $customerid, $primarytagid>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    public function searchbyprimarytag($usertype, $customerid, $primarytagid)
        {

                $data = '';
                $data['PageTitle'] = "Zoddl | Customer gallery";
                $data['customerid'] = $customerid;
                $data['usertypeid'] = $usertype;
                $data['primarytagid'] = $primarytagid;
                $data['Prime_Name'] = $this->gallery_model->get_primary($primarytagid);

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

            $data['CustomerPrimaryTag'] = $CustomerPrimaryTag = $this->gallery_model->get_customerprimarytag($customerid);
            $data['CustomerSecondaryTag'] = $CustomerSecondaryTag = $this->gallery_model->get_customersecondarytag($customerid);
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/gallery/primarytagsearch_view',$data);
            $this->load->view('admin/template/footer2');

        }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <searchprimarytaggallerydata>
    * @Description:     <this function Search by Primary Tag listing data to admin panel>
    * @Parameters:      <$usertype, $customerid, $primarytagid>
    * @Method:          <No>
    * @Returns:         <YES>
    * @Return Type:     <JSON ENCODED DATA>
    */

    public function searchprimarytaggallerydata($usertypeid, $customerid, $primarytagid)
        { 
              $allst = '';
              $arraySecondaryValue = array();

                 $get_primary_secondary_tag = $this->db->query("select Secondry_Tag from tbl_tag where Customer_Id = $customerid AND Primary_Tag = $primarytagid")->result();
                   
                   foreach($get_primary_secondary_tag as $st)
                   {
                       $allst .= $st->Secondry_Tag.","; 
                   }
              
              $filterValue = trim($allst,',');
              $arraySecondaryValue = explode(',',$filterValue);
              $uniqeValue = array_unique($arraySecondaryValue);      

            
            $list = $this->gallery_model->get_datatables_primary($customerid,$uniqeValue);
            $data = array();
            $getGalleryimage = array();
            $no = $_POST['start'];      
            foreach ($list as $gallerydata) {  

                if(!empty($gallerydata->Id))
                {
                    $getGallerydata = $this->gallery_model->get_secondary_gallery_image($gallerydata->Id,$customerid, $primarytagid);
                    if($getGallerydata)
                    {
                        
                           $getGalleryimage =   $getGallerydata;
                            
                    }
                    else
                    {

                            $getGalleryimage = '';
                    }
                }
                else
                {
                    $getGalleryimage = '';

                }
                $no++;
                $row = array();
                $row[] = $gallerydata->Secondary_Name;
                $row[] = $getGalleryimage; 
                $row[] ='<div class="fa-item col-md-6 col-sm-6"><a class="btn green" href="'.base_url('gallery/viewdetailsecondorytag/'.$usertypeid.'/'.$customerid.'/'.$gallerydata->Id.'/'.$primarytagid).'" title="View Detail">
                View Detail</a> </div>';
                $row[] = 'delete'.$gallerydata->Id;                    
                $data[] = $row;
            }       
         
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->gallery_model->count_all_primary($customerid,$uniqeValue),
                            "recordsFiltered" => $this->gallery_model->count_filtered_primary($customerid,$uniqeValue),
                            "data" => $data,
                    );      
            
            echo json_encode($output);
        }
   
    
     /** Start search by primary tag data **/


     /** Start search by secondary tag data **/


     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <searchbysecondarytag>
    * @Description:     <this function Search by Secondary Tag listing View to admin panel>
    * @Parameters:      <$usertype, $customerid, $secondarytagid>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    public function searchbysecondarytag($usertype, $customerid, $secondarytagid)
        {

                $data = '';
                $data['PageTitle'] = "Zoddl | Customer gallery";
                $data['customerid'] = $customerid;
                $data['usertypeid'] = $usertype;
                $data['secondarytagid'] = $secondarytagid;
                $data['Secondary_Name'] = $this->gallery_model->get_secondary($secondarytagid);

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

            $data['CustomerPrimaryTag'] = $CustomerPrimaryTag = $this->gallery_model->get_customerprimarytag($customerid);
            $data['CustomerSecondaryTag'] = $CustomerSecondaryTag = $this->gallery_model->get_customersecondarytag($customerid);
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/gallery/secondarytagsearch_view',$data);
            $this->load->view('admin/template/footer2');

        }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <searchsecondarytaggallerydata>
    * @Description:     <this function Search by Secondary Tag listing data to admin panel>
    * @Parameters:      <$usertype, $customerid, $secondarytagid>
    * @Method:          <No>
    * @Returns:         <YES>
    * @Return Type:     <JSON ENCODED DATA>
    */

    public function searchsecondarytaggallerydata($usertypeid, $customerid, $secondarytagid)
        {       
            
            $list = $this->gallery_model->get_datatables_secondary($customerid, $secondarytagid);
            $data = array();
            $getGalleryimage = array();
            $no = $_POST['start'];      
            foreach ($list as $gallerydata) {  

            if(!empty($gallerydata->Primary_Tag))
                {
                    $getprimarydata = $this->gallery_model->get_primary($gallerydata->Primary_Tag);
                    if($getprimarydata)
                    {

                            $getprimaryName = $getprimarydata;
                    }
                    else
                    {

                            $getprimaryName = '';
                    }
                }
                else
                {
                    $getprimaryName = '';

                }

                if(!empty($gallerydata->Primary_Tag))
                {
                    $getGallerydata = $this->gallery_model->get_gallery_image($gallerydata->Primary_Tag, $secondarytagid);
                    if($getGallerydata)
                    {
                        
                           $getGalleryimage =   $getGallerydata;
                            
                    }
                    else
                    {

                            $getGalleryimage = '';
                    }
                }
                else
                {
                    $getGalleryimage = '';

                }

                
                $no++;
                $row = array();
                $row[] = $getprimaryName;
                $row[] = $getGalleryimage; 
                $row[] ='<div class="fa-item col-md-6 col-sm-6"><a class="btn green" href="'.base_url('gallery/viewdetailprimarytag/'.$usertypeid.'/'.$customerid.'/'.$gallerydata->Primary_Tag.'/'.$secondarytagid).'" title="View Detail">View Detail</a> </div>';
                $row[] = 'delete'.$gallerydata->Id;                    
                $data[] = $row;
            }       
         
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->gallery_model->count_all_secondary($customerid, $secondarytagid),
                            "recordsFiltered" => $this->gallery_model->count_filtered_secondary($customerid, $secondarytagid),
                            "data" => $data,
                    );      
            
            echo json_encode($output);
        }
   
    
     /** End search by secondary tag data **/   
   

     /*** Start View detail by primary tag ***/

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <viewdetailprimarytag>
    * @Description:     <this function view deatail by primary tag to admin panel>
    * @Parameters:      <$usertype, $customerid, $primarytagid, $secondarytagid = ''>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    public function viewdetailprimarytag($usertype, $customerid, $primarytagid, $secondarytagid = '')
       {
        

                $data = '';
                $data['PageTitle'] = "Zoddl | Customer view gallery detail";
                $data['customerid'] = $customerid;
                $data['usertypeid'] = $usertype;
                $data['primarytagid'] = $primarytagid;
                $data['secondarytagid'] = $secondarytagid;
                if($secondarytagid != '')
                {
                  $data['Secondary_Name'] = $this->gallery_model->get_secondary($secondarytagid);
                }
                else
                {
                  $data['Secondary_Name'] = '';  
                }

                if(!empty($primarytagid))
                {

                    $data['primaryName'] = $primaryName = $this->gallery_model->get_primary($primarytagid);
                }

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
            $data['CustomerPrimaryTag'] = $CustomerPrimaryTag = $this->gallery_model->get_customerprimarytag($customerid);
            $data['CustomerSecondaryTag'] = $CustomerSecondaryTag = $this->gallery_model->get_customersecondarytag($customerid);
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/gallery/primarytagviewdetail',$data);
            $this->load->view('admin/template/footer2');
       }

       /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <primarytagviewdata>
    * @Description:     <this function view deatail data by primary tag to admin panel>
    * @Parameters:      <$usertype, $customerid, $primarytagid, $secondarytagid = ''>
    * @Method:          <No>
    * @Returns:         <YES>
    * @Return Type:     <JSON ENCODED DATA>
    */


    public function primarytagviewdata($usertypeid, $customerid, $primarytagid, $secondarytagid = '')
        {       
            
            $list = $this->gallery_model->get_datatables_primary_view($customerid, $primarytagid, $secondarytagid);
            $data = array();
            $getGalleryimage = array();
            $no = $_POST['start'];      
            foreach ($list as $gallerydata) {

                if(!empty($gallerydata->Primary_Tag))
                {
                    $getGallerydata = $this->gallery_model->get_date_by_gallery_image($customerid, $gallerydata->Primary_Tag, $gallerydata->Tag_Date_int, $secondarytagid);
                    $getAmountdata = $this->gallery_model->get_amount_primary_tag($customerid, $gallerydata->Primary_Tag, $gallerydata->Tag_Date_int, $secondarytagid);
                    if($getGallerydata)
                    {
                        
                           $getGalleryimage =   $getGallerydata;
                            
                    }
                    else
                    {

                            $getGalleryimage = '';
                    }

                    if($getAmountdata)
                    {
                        $totalAmount = array_sum($getAmountdata);
                    }
                    else
                    {
                        $totalAmount = '';  
                    }
                }
                else
                {
                    $getGalleryimage = '';
                    $totalAmount = '';

                }

                
                $no++;
                $row = array();
                $row[] = date("jS F", $gallerydata->Tag_Date_int).'</br>Image Count - '.count($getGalleryimage).'</br>Total Amount -'.$totalAmount;
                $row[] = $getGalleryimage;
                $row[] = 'delete'.$gallerydata->Id;                    
                $data[] = $row;
            }       
         
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->gallery_model->count_all_primary_view($customerid, $primarytagid, $secondarytagid),
                            "recordsFiltered" => $this->gallery_model->count_filtered_primary_view($customerid, $primarytagid, $secondarytagid),
                            "data" => $data,
                    );      
            
            echo json_encode($output);
        }

     /*** Start View detail by primary tag ***/ 

     /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <viewdetailsecondorytag>
    * @Description:     <this function view deatail by secondary tag to admin panel>
    * @Parameters:      <$usertype, $customerid, $secondorytagid, $primarytagid>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    public function viewdetailsecondorytag($usertype, $customerid, $secondorytagid, $primarytagid)
       {

                $data = '';
                $data['PageTitle'] = "Zoddl | Customer view gallery detail";
                $data['customerid'] = $customerid;
                $data['usertypeid'] = $usertype;
                $data['secondorytagid'] = $secondorytagid;
                $data['primarytagid'] = $primarytagid;
                $data['Prime_Name'] = $this->gallery_model->get_primary($primarytagid);
                if(!empty($secondorytagid))
                {

                    $data['secondoryName'] = $secondoryName = $this->gallery_model->get_secondary($secondorytagid);
                }

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
            $data['CustomerPrimaryTag'] = $CustomerPrimaryTag = $this->gallery_model->get_customerprimarytag($customerid);
            $data['CustomerSecondaryTag'] = $CustomerSecondaryTag = $this->gallery_model->get_customersecondarytag($customerid);
            $this->load->view('admin/template/header2',$data);  
            $this->load->view('admin/template/sidebar');        
            $this->load->view('admin/gallery/secondarytagviewdetail',$data);
            $this->load->view('admin/template/footer2');
        }

    /**
    * @Author:          Antaryami
    * @Last modified:   <27-12-2017>
    * @Project:         <Zoddl>
    * @Function:        <secondorytagviewdata>
    * @Description:     <this function view deatail data by secondary tag to admin panel>
    * @Parameters:      <$usertypeid, $customerid, $secondorytagid, $primarytagid>
    * @Method:          <No>
    * @Returns:         <YES>
    * @Return Type:     <JSON ENCODED DATA>
    */

    public function secondorytagviewdata($usertypeid, $customerid, $secondorytagid, $primarytagid)
        {       
            
            $list = $this->gallery_model->get_datatables_secondory_view($customerid, $secondorytagid, $primarytagid);
            $data = array();
            $getGalleryimage = array();
            $no = $_POST['start'];      
            foreach ($list as $gallerydata) {

                if(!empty($gallerydata->Secondry_Tag))
                {

                    $getGallerydata = $this->gallery_model->get_date_by_gallery_image2($customerid, $secondorytagid, $gallerydata->Tag_Date_int, $primarytagid);
                    $getAmountdata = $this->gallery_model->get_amount_secondory_tag($customerid, $secondorytagid, $gallerydata->Tag_Date_int, $primarytagid);
                    if($getGallerydata)
                    {
                        
                           $getGalleryimage =   $getGallerydata;
                            
                    }
                    else
                    {

                            $getGalleryimage = '';
                    }

                    if($getAmountdata)
                    {
                        $totalAmount = array_sum($getAmountdata);
                    }
                    else
                    {
                        $totalAmount = '';  
                    }
                }
                else
                {
                    $getGalleryimage = '';
                    $totalAmount     = '';

                }
                
                $no++;
                $row = array();
                $row[] = date("jS F", $gallerydata->Tag_Date_int).'</br>Image Count - '.count($getGalleryimage).'</br>Total Amount -'.$totalAmount;
                $row[] = $getGalleryimage;
                $row[] = 'delete'.$gallerydata->Id;                    
                $data[] = $row;
            }       
         
            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->gallery_model->count_all_secondory_view($customerid, $secondorytagid, $primarytagid),
                            "recordsFiltered" => $this->gallery_model->count_filtered_secondory_view($customerid, $secondorytagid, $primarytagid),
                            "data" => $data,
                    );      
            
            echo json_encode($output);
        }
    
}
