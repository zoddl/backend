<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
//Admin Function
    function check_login()
    {
        $CI =& get_instance();
        
        $CI->load->model("user_model");
            
        $username = $CI->session->userdata('username');
    	$userhash = $CI->session->userdata('userhash');
            
        if($username == '' || $userhash == '')
    	{
    		redirect(base_url().'login','refresh');
    		exit();
    	}
    	elseif( $CI->user_model->exists_email( $username ) == 0 )
    	{
    		redirect(base_url().'login','refresh');
    		exit();
    	}
    	else
    	{
    			
    		$userdata  = $CI->user_model->user_data( $username );
                
    		$hash_db  = md5($userdata->password.$CI->config->item('password_hash'));
    			
    		if($userhash != $hash_db)
    		{
    			redirect(base_url().'login','refresh');
    			exit();
    		}
    	}
    }


    function is_login()
	{
	   
        $CI =& get_instance();
        
		$CI->load->model("user_model");
		
		$username = $CI->session->userdata('username');
		$userhash = $CI->session->userdata('userhash');
		
		if( $username == '' || $userhash == '' )
		{
			return 0;	
		}
		else
		{
			$userdata  = $CI->user_model->user_data( $username );
                
    		$hash_db  = md5($userdata->password.$CI->config->item('password_hash'));
			
			
			if( $CI->user_model->exists_email( $username ) == 0 )
			{
				return 0;
			}
			elseif( $userhash != $hash_db )
			{
				return 0;
			}
			elseif( ( $CI->user_model->exists_email( $username ) == 1 ) && $userhash == $hash_db )
			{
				return 1;
			}
		}
	}

    function userdata( $field = 'id' )
    {
        $CI =& get_instance();
        
		$CI->load->model("user_model");
		
		$username = $CI->session->userdata('username');
        
        $userdata = $CI->user_model->user_data( $username );
        
        return $userdata->$field;
    }
	
	function fpv($value)
	{
		$CI =& get_instance();
		 $val = $CI->db->escape(trim($value));
		return trim($val,"'");
		
	}

	function dump($obj) 
	{
	    echo '<pre>';
	    print_r($obj);
	    echo '</pre>';
	}

function pr($obj) 
	{
	    echo '<pre>';
	    print_r($obj);
	    echo '</pre>';
	}

function lq() 
	{
	    $CI = CI();
	    return $CI->db->last_query();
	}
    
?>
