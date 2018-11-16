<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Gallery extends CI_Controller
{
    function Gallery()
    {
        parent::__construct();
        $this->load->database();
        $this->load->model("gallery_model");
        //$this->load->library('form_validation');
        
        /*cache control*/
	    $this->output->set_header('Last-Modified:'.gmdate('D, d M Y H:i:s').'GMT');
		$this->output->set_header('Cache-Control: no-store, no-cache, must-revalidate');
		$this->output->set_header('Cache-Control: post-check=0, pre-check=0',false);
		$this->output->set_header('Pragma: no-cache');
		check_login();

    }

    function customergallery($usertype, $custmerid)
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

    public function searchbyprimarytag($usertype, $customerid, $primarytagid)
    {

            $data = '';
            $data['PageTitle'] = "Zoddl | Customer gallery";
            $data['customerid'] = $customerid;
            $data['usertypeid'] = $usertype;
            $data['primarytagid'] = $primarytagid;

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

    public function searchprimarytaggallerydata($usertypeid, $customerid, $primarytagid)
    {       
        
        $list = $this->gallery_model->get_datatables_primary($customerid, $primarytagid);
        $data = array();
        $getGalleryimage = array();
        $no = $_POST['start'];      
        foreach ($list as $gallerydata) {  


            if(!empty($gallerydata->Secondry_Tag))
            {
                $getsecondarydata = $this->gallery_model->get_secondary($gallerydata->Secondry_Tag);
                if($getsecondarydata)
                {

                        $getsecondaryName = $getsecondarydata;
                }
                else
                {

                        $getsecondaryName = '';
                }
            }
            else
            {
                $getsecondaryName = '';

            }

            if(!empty($gallerydata->Secondry_Tag))
            {
                $getGallerydata = $this->gallery_model->get_secondary_gallery_image($gallerydata->Secondry_Tag);
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
            $row[] = $getsecondaryName;
            $row[] = $getGalleryimage; 
            $row[] ='<div class="fa-item col-md-6 col-sm-6"><a class="btn green" href="'.base_url('gallery/viewdetailsecondorytag/'.$usertypeid.'/'.$customerid.'/'.$gallerydata->Secondry_Tag).'" title="View Detail">
            View Detail</a> </div>';
            $row[] = 'delete'.$gallerydata->Id;                    
            $data[] = $row;
        }       
     
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->gallery_model->count_all_primary($customerid, $primarytagid),
                        "recordsFiltered" => $this->gallery_model->count_filtered_primary($customerid, $primarytagid),
                        "data" => $data,
                );      
        
        echo json_encode($output);
    }
   
    
     /** Start search by primary tag data **/


     /** Start search by secondary tag data **/

    public function searchbysecondarytag($usertype, $customerid, $secondarytagid)
    {

            $data = '';
            $data['PageTitle'] = "Zoddl | Customer gallery";
            $data['customerid'] = $customerid;
            $data['usertypeid'] = $usertype;
            $data['secondarytagid'] = $secondarytagid;

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
            $row[] ='<div class="fa-item col-md-6 col-sm-6"><a class="btn green" href="'.base_url('gallery/viewdetailprimarytag/'.$usertypeid.'/'.$customerid.'/'.$gallerydata->Primary_Tag).'" title="View Detail">View Detail</a> </div>';
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

     public function viewdetailprimarytag($usertype, $customerid, $primarytagid)
       {

                $data = '';
                $data['PageTitle'] = "Zoddl | Customer view gallery detail";
                $data['customerid'] = $customerid;
                $data['usertypeid'] = $usertype;
                $data['primarytagid'] = $primarytagid;

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


public function primarytagviewdata($usertypeid, $customerid, $primarytagid)
    {       
        
        $list = $this->gallery_model->get_datatables_primary_view($customerid, $primarytagid);
        $data = array();
        $getGalleryimage = array();
        $no = $_POST['start'];      
        foreach ($list as $gallerydata) {

            if(!empty($gallerydata->Primary_Tag))
            {
                $getGallerydata = $this->gallery_model->get_date_by_gallery_image($customerid, $gallerydata->Primary_Tag, $gallerydata->Tag_Date_int);
                $getAmountdata = $this->gallery_model->get_amount_primary_tag($customerid, $gallerydata->Primary_Tag, $gallerydata->Tag_Date_int);
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
                        "recordsTotal" => $this->gallery_model->count_all_primary_view($customerid, $primarytagid),
                        "recordsFiltered" => $this->gallery_model->count_filtered_primary_view($customerid, $primarytagid),
                        "data" => $data,
                );      
        
        echo json_encode($output);
    }

     /*** Start View detail by primary tag ***/ 

      /*** abhinav function Start View detail by primary tag ***/

      public function viewdetailsecondorytag($usertype, $customerid, $secondorytagid)
       {

                $data = '';
                $data['PageTitle'] = "Zoddl | Customer view gallery detail";
                $data['customerid'] = $customerid;
                $data['usertypeid'] = $usertype;
                $data['secondorytagid'] = $secondorytagid;

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
        }//ends abhinav function  

       /********abhinav function secondory view taga data******/

    public function secondorytagviewdata($usertypeid, $customerid, $secondorytagid)
    {       
        
        $list = $this->gallery_model->get_datatables_secondory_view($customerid, $secondorytagid);
        $data = array();
        $getGalleryimage = array();
        $no = $_POST['start'];      
        foreach ($list as $gallerydata) {

            if(!empty($gallerydata->Secondry_Tag))
            {

                $getGallerydata = $this->gallery_model->get_date_by_gallery_image2($customerid, $gallerydata->Secondry_Tag, $gallerydata->Tag_Date_int);
                $getAmountdata = $this->gallery_model->get_amount_secondory_tag($customerid, $gallerydata->Secondry_Tag, $gallerydata->Tag_Date_int);
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
                        "recordsTotal" => $this->gallery_model->count_all_secondory_view($customerid, $secondorytagid),
                        "recordsFiltered" => $this->gallery_model->count_filtered_secondory_view($customerid, $secondorytagid),
                        "data" => $data,
                );      
        
        echo json_encode($output);
    }// end abhinav function
    
}
