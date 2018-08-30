<?php
/*
Author : Mobilee
Developer : Jaco Brits
Email : jaco@mobileeapps.com

Description:
Basic Security
Token check
Apikey check
Basic Model / Mysql Connection file with some base actions

Functions:

rowInsert($table_name, $form_data)   example : rowInsert('users',array[name]='jaco')
rowDelete($table_name, $where_clause='')   example : rowInsert('users',array[name]='jaco')
rowUpdate($table_name, $form_data, $where_clause='')
rowSelect($table_name, $where_clause='')
safeFile("weight",1,{weight_file})
generatePdf(1,"<h1>testing<h2>")
sendEmail('jaco@fancam.com','test subject2','test body data2')
*/
// ini_set('display_errors', 1);
// ini_set('display_startup_errors', 1);
// error_reporting(E_ALL);
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '2048M');
ini_set('max_execution_time', 300); //300 seconds = 5 minutes
error_reporting(E_ALL);
session_start();
if (empty($_SESSION['csrf_token'])) {
    $csrf_token =bin2hex(random_bytes(32));
    $_SESSION['csrf_token'] = $csrf_token;
}

header('Content-Type: application/json');

$headers = apache_request_headers();
if (isset($headers['CsrfToken'])) {
    if ($headers['CsrfToken'] !== $_SESSION['csrf_token']) {
        //exit(json_encode(['error' => 'Wrong CSRF token.']));
    }
} else {
    //exit(json_encode(['error' => 'No CSRF token.']));
}

if(!isset($_POST['apikey'])){
  $postData = json_decode($_POST['contianerDetails']);
  $apiKey = $postData->apikey;
}else{
  $apiKey = $_POST['apikey'];
}


if($apiKey == "cd8f23a8e07a223e8d89f3fbf42c3874"){

  $servername = "medmin.co.za";
  $username = "medminco_demo";
  $password = "D3m0!234";
  $db_name = "medminco_fruitflo";
  $invalid_characters = array("$", "%", "#", "<", ">", "|");


  $databaseConnection = new mysqli($servername, $username, $password, $db_name);
  if ($databaseConnection->connect_error) {
  	die("1 - Connection failed: " . $databaseConnection->connect_error);
    $connected_button = "danger";
    $connected_message = "<strong>Failed:</strong> Could not connect to Fancam Database.";
  }
}else{
  exit(json_encode(['error' => 'invalid APIKEY']));
}


function rowInsert($databaseConnection,$table_name, $form_data)
{
    // retrieve the keys of the array (column titles)
    $fields = array_keys($form_data);

    // build the query
    $sql = "INSERT INTO ".$table_name."
    (`".implode('`,`', $fields)."`)
    VALUES('".implode("','", $form_data)."')";

    // run and return the query result resource
    return mysqli_query($databaseConnection,$sql);
}

function rowDelete($databaseConnection,$table_name, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $sql = "DELETE FROM ".$table_name.$whereSQL;

    // run and return the query result resource
    return mysqli_query($databaseConnection,$sql);
}

// again where clause is left optional
function rowUpdate($databaseConnection,$table_name, $form_data, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

    // loop and build the column /
    $sets = array();

	if (count($form_data) > 1)
	{
		foreach($form_data as $column => $value)
		{
			 $sets[] = "`".$column."` = '".$value."'";
			 echo "`".$column."` = '".$value."'";

		}
    $sql .= implode(', ', $sets);
	}
	// if only one set, simply append SQL query
	else
	{
	$sql.= $form_data;
	}


    // append the where statement
    $sql .= $whereSQL;

    // run and return the query result
    return mysqli_query($databaseConnection,$sql);
}

// again where clause is left optional
function rowSelect($databaseConnection,$table_name, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "SELECT * FROM ".$table_name." ";

    // append the where statement
    $sql .= $whereSQL;

    // run and return the query result
    return mysqli_query($databaseConnection,$sql);
}

/*
Mobilee Apps generate save image file it in ../assets/uploads/{id}/{type}
example : safeFile("weight",1,{weight_file});
*/
function saveFile($type,$id,$filedata){
  //var_dump($filedata);exit;
  $target_dir = "../assets/uploads/".$id."/".$type."/";
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }
  $target_file = $target_dir . $id ."_" . basename($filedata["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  $check = getimagesize($filedata["tmp_name"]);
  if($check !== false) {
    move_uploaded_file($filedata["tmp_name"], $target_file);
  }

  return $target_file;
}


/*
Mobilee Apps generate pdf file and store it in ../assets/uploads/{id}/report
example : generatePdf(1,"<h1>testing<h2>");
*/
function generatePdf($id,$html){
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


/*
Mobilee Apps Send email via SMTP server
example : sendEmail('jaco@fancam.com','test subject2','test body data2');
*/

function sendEmail($email, $subject, $body, $atts = array()) {

  require_once('../assets/libraries/phpemail/class.phpmailer.php');

    $mail = new PHPMailer();

    $mail->IsSMTP(); // set mailer to use SMTP

    $mail->Host = 'mail.jbrnd.co.za'; //'mail.medmin.co.za'; // specify main and backup server

    $mail->SMTPAuth = true; // turn on SMTP authentication

    $mail->Username = 'info@jbrnd.co.za'; //'support@medmin.co.za'; // SMTP username

    $mail->Password = '!Nf0G588e'; // SMTP password
    $mail->Port = 587;

    $mail->From = 'noreply@mobileeapps.com'; //do NOT fake header.

    $mail->FromName = 'Fruitflo';

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

    //echo $response;
    }

?>
