<?php 
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

function Api_Log_Generate($Table_Name, $dataJson, $Api_Date, $otherInfo="")
{
    $CI =& get_instance();
    $data=array("data" => $dataJson, "datetime" => $Api_Date, "other"=>$otherInfo);
    $CI->db->insert($Table_Name,$data); 

    return true;
}


?>
