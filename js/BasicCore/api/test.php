<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

//require_once 'config_local.php';
//send_email_html('jaco@fancam.com','test subject2','test body data2');
$pdf = generate_pdf(1,"<h1>testing<h2>");
$attachments = array();
$attachments[] = $pdf;
send_email_html('jaco@mobileeapps.com','test subject2','test body data2',$attachments);
send_email_html('robert@mobileeapps.com','test subject2','test body data2',$attachments);

function generate_pdf($id,$html){
  require_once("../assets/libraries/dompdf/dompdf_config.inc.php");
  $filename = "../assets/uploads/".$id."/report.pdf";
  $dompdf = new DOMPDF();
  $dompdf->load_html($html);
  $dompdf->render();
  $output = $dompdf->output();
  file_put_contents($filename, $output);
  //copy($company_lead->col_file_invoice,$final_invoice);
  return $filename;
}

function send_email_html($email, $subject, $body, $atts = array()) {

  require_once('../assets/libraries/phpemail/class.phpmailer.php');

  $mail = new PHPMailer();

  $mail->IsSMTP(); // set mailer to use SMTP

  $mail->Host = 'mail.jbrnd.co.za'; //'mail.medmin.co.za'; // specify main and backup server

  $mail->SMTPAuth = true; // turn on SMTP authentication

  $mail->Username = 'info@jbrnd.co.za'; //'support@medmin.co.za'; // SMTP username

  $mail->Password = '!Nf0G588e'; // SMTP password
  $mail->Port = 587;

  $mail->From = 'noreply@mobileeapps.com'; //do NOT fake header.

  $mail->FromName = 'Mobilee Apps';

  $mail->AddAddress($email); // Email on which you want to send mail
  //$mail->AddReplyTo(“Reply to Email “, “Support and Help”); //optional

  foreach ($atts as $att) {
    $mail->AddAttachment($att);
  }

  $mail->Subject = $subject;

  $mail->Body = $body;

  $mail->IsHTML(true);

  if (!$mail->Send()) {
    $response = $mail->ErrorInfo;
  } else {
    $response = 'email was sent';
  }



        echo $response;
    }
?>
