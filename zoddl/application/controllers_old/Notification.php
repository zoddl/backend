<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
* Notification Class
*
* @package Zoddl
* @author Abhijeet
* @version 1.0
* @description Notification Controller
* @link http://zoddl.com/notification
*/

class Notification extends CI_Controller {

    /**
    * @Author:          Abhijeet
    * @Last modified:   <14-03-2018>
    * @Project:         <Zoddl>
    * @Function:        <Notification>
    * @Description:     <this function load models, library and helper>
    * @Parameters:      <NO>
    * @Method:          <NO>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

    function Notification()
    {
		parent::__construct();
		$this->load->model('Customer_Api_Model');

		$this->load->library(array('encrypt', 'form_validation', 'session'));
		$this->load->helper(array('form'));
    }

     /**
    * @Author:          Abhijeet
    * @Last modified:   <14-03-2018>
    * @Project:         <Zoddl>
    * @Function:        <index>
    * @Description:     <this function will user dashboard listing view to admin panel>
    * @Parameters:      <NO>
    * @Method:          <No>
    * @Returns:         <NO>
    * @Return Type:     <NO>
    */

	public function index()
	{
		check_login();

		$data = '';
		$data['PageTitle'] = "Zoddl | Notification";
		$this->load->view('admin/template/header2',$data);
		$this->load->view('admin/template/sidebar');

		$this->load->view('admin/notification/index');

		$this->load->view('admin/template/footer2');
	}

	/**
    * @Author:          Abhijeet
    * @Last modified:   <14-03-2018>
    * @Project:         <Zoddl>
    * @Function:        <send>
    * @Description:     <this function will send notification to user>
    * @Parameters:      <No>
    * @Method:          <POST>
    * @Returns:         <Yes>
    * @Return Type:     <Boolean>
    */
	
	public function send()
	{

		$data = '';
		$data['PageTitle'] = "Zoddl | Send Notification";
		$this->form_validation->set_rules('notifyTitle', 'Title', 'required', 'required|min_length[5]|max_length[30]');
        $this->form_validation->set_rules('messageTextarea', 'Message', 'required|min_length[5]|max_length[100]');

		if($this->form_validation->run() == FALSE)
		{
			$data['PageTitle'] = "Zoddl | Send Notification";
			$this->load->view('admin/template/header',$data);	
			$this->load->view('admin/template/sidebar');
			$this->load->view('admin/notification/index');
			$this->load->view('admin/template/footer');
	    }
		else
		{
			$notifyTitleValue = fpv($this->input->post('notifyTitle'));
			$messageTextareaValue = fpv($this->input->post('messageTextarea'));

			$allUserDeviceToken = $this->Customer_Api_Model->getAllUserDeviceToken();

			if(count($allUserDeviceToken)>0){
				foreach ($allUserDeviceToken as $key => $value) {
					
					if(!empty($value->device_token)){
						
						$message = $notifyTitleValue;
						$key = time();

						//$deviceToken = "d4uGcsoihdo:APA91bED3CQ22tC53BfpaUJVVBDOOBzqHqtoOQIp9yZwZZ2NNb721OJMbypdNrv7tkZw3rfQ1VOHu-g2lSAzD8ItxJxkRHiJ-lkI---82FgclX-z9aLFv3Hugz7p4kVcMNTnmOi8PbNy";

						//$deviceToken = "13de5b99fc15392c26aec1a4c90c1c37cbe56e1fc19e285e0f4223e4e943228a";

						$deviceToken = $value->device_token;

						//$details = array("user_id"=>$value->id, "username"=>$value->Customer_Name, "user_email"=>$value->Email_Id, "user_mobile"=>$value->Phone_No, "status"=>0);

						$details = array("message" => $messageTextareaValue, "key" => $key);
						//$result  = send_fcm_notification($deviceToken,$details,$message);

						$result = $this->sendIOSNotification($deviceToken, $details, $message);

						$this->session->set_flashdata('flashNotifySuccess',"Notification has been send successfully !");
					}
				}
			}else{
				$this->session->set_flashdata('flashNotifySuccess',"Not able to send any notification, Device is missing !!");
			}

			redirect(base_url('notification'),'refresh');

			$this->load->view('admin/template/header',$data);	
			$this->load->view('admin/template/sidebar');
			$this->load->view('admin/notification/index');
			$this->load->view('admin/template/footer');
			
		}
	}


	/**
    * @Author:          Manoj
    * @Last modified:   <14-03-2018>
    * @Project:         <Zoddl>
    * @Function:        <Send Notification>
    * @Description:     <this function will send notification to user>
    * @Parameters:      <No>
    * @Method:          <Parameter>
    * @Returns:         <Yes>
    * @Return Type:     <Boolean>
    */

	private function sendIOSNotification($deviceToken, $details, $message)
	{
		//$tHost = 'gateway.push.apple.com';
	    //$tHost = 'gateway.sandbox.push.apple.com';
	    // Put your device token here (without spaces):
		//$deviceToken = 'F3A59F5C184995E646A81EAC3156BFB1FE484E8166E74A06BF24285F4971B66E';
		// Put your private key's passphrase here:
		$passphrase = '';
		// Put your alert message here:
		//$message = 'A push notification has been sent!';
		////////////////////////////////////////////////////////////////////////////////
		$ctx = stream_context_create();
		stream_context_set_option($ctx, 'ssl', 'local_cert', 'pushcert.pem'); //put here path of .pem file
		stream_context_set_option($ctx, 'ssl', 'verify_peer', false);
		//stream_context_set_option($ctx, 'ssl', 'passphrase', $passphrase);
		// Open a connection to the APNS server
		$fp = stream_socket_client('ssl://gateway.sandbox.push.apple.com:2195', $err, $errstr, 60, STREAM_CLIENT_CONNECT|STREAM_CLIENT_PERSISTENT, $ctx);

		if (!$fp)
			exit("Failed to connect: $err $errstr" . PHP_EOL);

		//echo 'Connected to APNS' . PHP_EOL;
		// Create the payload body
		/*$body['aps'] = array(
		'alert' => array(
		'body' => $message,
		'action-loc-key' => 'Linkbook App',
		),
		'badge' => 2,
		'sound' => 'oven.caf',
		);*/
		$body['aps'] = array(
				'alert' => $message,
				'type'=>8, 
				'title'=>$details,
				'sound' => 'default'
				);
		// Encode the payload as JSON
		$payload = json_encode($body);
		// Build the binary notification
		$msg = chr(0) . pack('n', 32) . pack('H*', $deviceToken) . pack('n', strlen($payload)) . $payload;
		// Send it to the server
		$result = fwrite($fp, $msg, strlen($msg));
		if (!$result){
			//echo 'Message not delivered' . PHP_EOL;
		}else{
			//echo 'Message successfully delivered' . PHP_EOL;
		}
		// Close the connection to the server

		fclose($fp);
		return $result;
	}
		
}
