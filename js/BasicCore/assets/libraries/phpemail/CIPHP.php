<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

	class CIPHP extends CI_Controller {
	
		function __construct(){
		
			parent::__construct();
			
			$this->load->library('phpmailer');
			//$this->load->library('smtp');
			
			
		}
		
		function send_email() {
			$emailbody = "Name".$_GET['name']."- with Email: ".$_GET['email']." and contact number: ".$_GET['cnumber']." Message: ".$_GET['comment'];
		
			$subject               =             'Automated email';
			
			$name                  =             'Support Medmin APCI';
			
			$email                   =            'jaco@netstart.co.za';
			
			$body                   =             $emailbody;
			
			$this->phpmailer->AddAddress($email);
			$this->phpmailer->IsMail();
			// $this->phpmailer->IsSMTP();
			$this->phpmailer->Host = "smtp.liquidedge.co.za";
		    $this->phpmailer->SMTPAuth = true;
			$this->phpmailer->Username = 'smtp@liquidedge.co.za';
			$this->phpmailer->Password = 'purplecarrot92';
			$this->phpmailer->Port = '587';
			
			
			
			$this->phpmailer->From     = 'support@medmin.co.za';
			
			$this->phpmailer->FromName = 'Automated Support email';
			
			$this->phpmailer->IsHTML(true);
			
			$this->phpmailer->Subject  =  $subject;
			
			$this->phpmailer->Body     =  $body;
			
			//$this->phpmailer->Send();
				// if ( ! $this->phpmailer->Send())
		    // {
		        // echo 'Failed to Send:';
		    // }
		    // else
		    // {
		        // echo 'Mail Sent';
		    // }
			
			//$this->load->view('calender');

		}
        
}