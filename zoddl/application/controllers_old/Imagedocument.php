<?php
//error_reporting(0);
defined('BASEPATH') OR exit('No direct script access allowed');

class Imagedocument extends CI_Controller
{
    function Imagedocument()
    {
        parent::__construct();
        $this->load->database();       
        $this->load->model("Document_model");
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
    $data['PageTitle'] = "Zoddl | Document Gallery";
    $this->load->view('admin/template/header2',$data);  
    $this->load->view('admin/template/sidebar');    
    $this->load->view('admin/imagedocument/index');
    $this->load->view('admin/template/footer2');  
    }
    
    //abhinav function display image data entry

    public function image_data_entry()
    {
        
        $customerId = '';        
        $tagId = '';

        if($this->uri->segment('4') != '')
        {

           $customerId = $this->uri->segment('4');
        }        

        if( $this->uri->segment('3') != '')
        {

            $tagId = $this->uri->segment('3');

        }

        $data['customerId'] = $customerId;
        $data['tagId'] = $tagId;

        $data['tagData'] = $tagData =$this->Document_model->get_record_by_id('tbl_doc_tag',array('Id'=>$tagId));
        $all_Stags = explode( ',', $tagData->Secondry_Tag);

        $secondory_tagsArray = array();
        foreach ($all_Stags as $jk) 
        {
          $sec_data = $this->Document_model->get_record_by_id('tbl_doc_secondary_tag',array('Id'=>$jk));

          if(empty($sec_data))
          {
            $secondory_tagsArray[] = '';
          }
          else
          {
            $secondory_tagsArray[] = $sec_data->Secondary_Name;
          }
          
        }// end foreach

        $secondory_tagsArray = array_unique($secondory_tagsArray);
        $secondory_tagsArray= implode(',', $secondory_tagsArray);
        $data['finalsecondory_tagsArray'] = $secondory_tagsArray;
        $data['PageTitle'] = "Zoddl | Document Data Entry";
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/imagedocument/image_data_entry');
        $this->load->view('admin/template/footer2');    
    }

    public function not_found()
    {

        $data['PageTitle'] = "Zoddl | Document Data Entry";
        $this->load->view('admin/template/header2',$data);  
        $this->load->view('admin/template/sidebar');        
        $this->load->view('admin/imagedocument/not_found');
        $this->load->view('admin/template/footer2'); 


    }

    //abhinav function for insert single entry

    public function single_entry()
    {
         $tagId      = $this->uri->segment('3');
         if ($this->form_validation->run('single_entry_form') == FALSE) 
         {
            echo '<div class="alert alert-warning"><ul>' . validation_errors() . '</ul></div>';   
         }
        else
        {
            
            $tagData =$this->Document_model->get_record_by_id('tbl_doc_tag',array('Id'=>$tagId));
           
            /********Ptag data*************/
            $ptagsingle = $this->input->post('ptagsingle');
            $ptagData   =$this->Document_model->get_record_by_id('tbl_doc_primary_tag',array('Prime_Name'=>$ptagsingle,'Customer_Id'=>$tagData->Customer_Id));
            if(!empty($ptagData))
            {
                $ptag = $ptagData->Id;
            }
            else
            {
                $ptagsingle = $this->input->post('ptagsingle');
                $ptagInsert_id =$this->Document_model->insert_one_row('tbl_doc_primary_tag', array('Customer_Id'=>$tagData->Customer_Id,'Prime_Name'=>$ptagsingle));
                $ptag = $ptagInsert_id;
            }

            /********Ends Ptag data*************/

            /************Stag data**************/

             $stagsingle = $this->input->post('stagsingle');
             $input2 = explode( ',', $stagsingle );
       
            /********getting stag Id code************/
                $staggg               = $input2;
                $stagId= array();

                foreach ($staggg as $staggssbb) {
                   $stagData   =$this->Document_model->get_record_by_id('tbl_doc_secondary_tag',array('Secondary_Name'=>$staggssbb));
                   if($stagData)
                   {
                       $id_stag= $stagData->Id;
                   }
                   else
                   {
                      if(empty($staggssbb))
                      {
                        $staggssbb = 'Untagged';
                      }
                      else
                      {
                        $staggssbb = $staggssbb;
                      }
                      $id_stag = $this->Document_model->insert_one_row('tbl_doc_secondary_tag', array('Customer_Id'=>$tagData->Customer_Id,'Secondary_Name'=>$staggssbb));
                   }
                      $stagId[] = $id_stag;
                  
                }/*******end foreach********/

                  if($stagId)
                  {
                    $stag = implode( ',', $stagId );
                  }

                  else
                  {
                    $stag = 'Untagged';
                  }


            /********Ends Stag data*************/

            $tagData =$this->Document_model->get_record_by_id('tbl_doc_tag',array('Id'=>$tagId));
             $data = array(

                'Customer_Id'       => $tagData->Customer_Id,
                'Tag_Type'          => strtolower($this->input->post('etypesingle')),
                'Tag_Send_Date'     => $this->input->post('datesingle'),
                'Tag_Date_int'      => time(),
                'Tag_Date'          => date("Y-m-d",time()),
                'Amount'            => $this->input->post('amountsingle'),
                'Primary_Tag'       => $ptag,
                'Secondry_Tag'      => $stag,
                'Description'       => $this->input->post('descriptionsingle'),
                'Sub_Description'   => $this->input->post('subdescriptionsingle'),
                'CGST'              => $this->input->post('cgstsingle'),
                'SGST'              => $this->input->post('sgstsingle'),
                'IGST'              => $this->input->post('igstsingle'),
                'Invoice_Check_No'  => $this->input->post('invoice_nosingle'),
                'Tag_Status'        => $this->input->post('statussingle')
                );
             $this->db->update('tbl_doc_tag',$data,array('Id'=> $tagId));
             echo '<script>location.href="'.base_url('imagedocument/image_data_entry/'.$tagId.'/'.$tagData->Customer_Id).'";</script>';
        }
    }//ends single entry function

    //abhinav function for multiple entry

    public function multiple_entry()
    {
            
            $tagId      = $this->uri->segment('3');
            $tagData =$this->Document_model->get_record_by_id('tbl_doc_tag',array('Id'=>$tagId));
              /********getting ptag Id code*******/
                $ptaggg               = $this->input->post('ptag');
                $ptagId= array();

                foreach ($ptaggg as $ptaggssbb) {
                   $ptagData   =$this->Document_model->get_record_by_id('tbl_doc_primary_tag',array('Prime_Name'=>$ptaggssbb,'Customer_Id'=>$tagData->Customer_Id));
                   if($ptagData)
                   {
                       $id_ptag= $ptagData->Id;
                   }
                   else
                   {
                      $id_ptag = $this->Document_model->insert_one_row('tbl_doc_primary_tag', array('Customer_Id'=>$tagData->Customer_Id,'Prime_Name'=>$ptaggssbb));
                   }
                      $ptagId[] = $id_ptag;
                  
                }/*******end foreach********/
                   $ptag = $ptagId;

              /********ends getting ptag Id code*******/

              /********getting stag Id code************/
                $staggg               = $this->input->post('stag');
                
                $all_stagId = array();
                foreach ($staggg as $staggssbb) {
                     $input2 = explode( ',', $staggssbb );

                     $id_stag = array();
                     $stagId= array();
                    foreach ($input2 as $stag_values) {
                         $stagData   =$this->Document_model->get_record_by_id('tbl_doc_secondary_tag',array('Secondary_Name'=>$stag_values));
                       if($stagData)
                       {
                           $id_stag[]= $stagData->Id;
                       }
                       else
                       {
                          if(empty($stag_values))
                          {
                            $stag_values = 'Untagged';
                          }
                          else
                          {
                            $stag_values = $stag_values;
                          }
                          $id_stag[] = $this->Document_model->insert_one_row('tbl_doc_secondary_tag', array('Customer_Id'=>$tagData->Customer_Id,'Secondary_Name'=>$stag_values));
                       }

                          $stagId[] = implode(', ',$id_stag);
                    }

                         $all_stagId[] = end($stagId);
                }/*******end foreach********/

                  $stag = $all_stagId;

                  if($stag)
                  {
                    $stag = $stag;
                  }

                  else
                  {
                    $stag = '';
                  }

              /********ends getting ptag Id code******/

                $tagData =$this->Document_model->get_record_by_id('tbl_doc_tag',array('Id'=>$tagId));
                
                $etype              = $this->input->post('etype');
                $date               = $this->input->post('date');
                $Tag_Date_int       = time();
                $Tag_Date           = date("Y-m-d",time());
                $amount             = $this->input->post('amount');
                $ptag               = $ptag;
                $stag               = $stag;
                $Description        = $this->input->post('description');
                $Sub_Desc           = $this->input->post('subdescription');
                $CGST               = $this->input->post('cgst');
                $SGST               = $this->input->post('sgst');
                $IGST               = $this->input->post('igst');
                $Invoice_Check_No   = $this->input->post('invoice_no');
                $Tag_Status         = $this->input->post('status');
                
               for($i=0;$i<count($etype);$i++)
               {
               $this->Document_model->insert_one_row('tbl_doc_tag', array(

                'Tag_Type'          => strtolower($etype[$i]),
                'Customer_Id'       => $tagData->Customer_Id,
                'Tag_Send_Date'     => $date[$i],
                'Tag_Date_int'      => time(),
                'Tag_Date'          => date("Y-m-d",time()),
                'Amount'            => $amount[$i],
                'Primary_Tag'       => $ptag[$i],
                'Secondry_Tag'      => $stag[$i],
                'Description'       => $Description[$i],
                'Sub_Description'   => $Sub_Desc[$i],
                'CGST'              => $CGST[$i],
                'SGST'              => $SGST[$i],
                'IGST'              => $IGST[$i],
                'Invoice_Check_No'  => $Invoice_Check_No[$i],
                'Tag_Status'        => $Tag_Status[$i]
                ));

               }
         
             echo '<script>location.href="'.base_url('imagedocument/image_data_entry/'.$tagId.'/'.$tagData->Customer_Id).'";</script>';
        
    }// ends multiple entry function

 //abhinav function load all Ptag

    public function load_allPtag()
    {
        $searchTerm = @$_GET['term'];
        $get_allPtag = $this->Document_model->get_all_record_by_condition('tbl_doc_primary_tag',null); 
        $Ptagg = array();
         foreach ($get_allPtag as $ptag) 
         {
             if(!empty($ptag))
             {
                $Ptagg[] = $ptag->Prime_Name;      
             }
        }  
        $tag_name = array_unique($Ptagg, SORT_REGULAR);
        $tag_name = array_values($tag_name);
        $response['tagss'] = $tag_name;
        echo json_encode($response);
    }//ends abhinav function

//abhinav function load Stag

    public function load_allStag()
    {
        $searchTerm = @$_GET['term'];
        $get_allStag = $this->Document_model->get_all_record_by_condition('tbl_doc_secondary_tag',null); 
        $Stagg = array();
         foreach ($get_allStag as $stag) 
         {
             if(!empty($stag))
             {
                $Stagg[] = $stag->Secondary_Name;      
             }
        }  
        $tag_name = array_unique($Stagg, SORT_REGULAR);
        $tag_name = array_values($tag_name);
        $response['tagss'] = $tag_name;
        echo json_encode($response);
    }//ends abhinav function

    
}
