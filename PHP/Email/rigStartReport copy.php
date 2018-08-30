<?php
header("access-control-allow-origin: *");
header('content-type: application/json; charset=utf-8');

$subject = "";
$contents = "";
$mail_to = "";
$mail_to_name = "";
$email_from = "";
$email_from_name="";

function email_sent($subject,$contents,$mail_to,$mail_to_name,$email_from,$email_from_name){
	 $email_sent = false;
	 $contents = $contents.'<br><img src="https://fancam.com/ssl/email/footer/fancam_logo.png" alt="fancam" height="25">';
	 require_once 'email/Mandrill.php';
	 $mandrill = new Mandrill('g3g6DdUgVKo7Zqr0JAyibw');
	 //$mail_to = $this->clean_email($mail_to);
				try{

						$message = array(
								'subject' => $subject,
								'html' => $contents, // or just use 'html' to support HTMl markup
												//'html' => '',
								'from_email' => 'info@fancam.com',
								'from_name' => $email_from_name, //optional
								'to' => array(
										array( // add more sub-arrays for additional recipients
												'email' => $mail_to,//$sent_to[$key],
												'name' => $mail_to_name, // optional
												'type' => 'to' //optional. Default is 'to'. Other options: cc & bcc
										)
								),
								'headers'=>array(
																'Reply-To' => 'info@fancam.com'
														),
								'important' => false,
								'google_analytics_domains' => array('fancam.com'),
								'google_analytics_campaign' => 'info@fancam.com',
								'metadata' => array('website' => 'www.fancam.com')

						);

						$result = $mandrill->messages->send($message);
						$email_sent = true;
				} catch(Mandrill_Error $e) {

						echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
						$email_sent = false;
						throw $e;
				}

		return $email_sent;
}

$report = html_entity_decode($_GET['rep']);
$subject = "RMS| ".$_GET['ven']."| Start Shoot | ".date('Y-m-d H:i:s');
$content = "RIG : ".$_GET['ven']." <br>
            <b>START REPORT</b><br>".$report."<br><br>
						Regards
						RIG Management System";

$servername = "77686c70cfcc649d51ba3b37308ff0daacccfe31.rackspaceclouddb.com";
$username = "fancam";
$password = "K3abENB2fv";
$dbname = "20161018_jaco";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
	die("Connection failed: " . $conn->connect_error);
}

email_sent($subject,$content,"jaco@fancam.com","Jaco Brits","info@fancam.com","fancam.com");
$qStr = "INSERT INTO error_log (err_subject, err_body, err_emails , err_type)
			 VALUES ('".$subject."', '".$content."', 'jaco@fancam.com',1)";
$conn->query($qStr);

?>
