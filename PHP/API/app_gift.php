<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'db_functions.php';
$db = new DB_Functions();
$rows = false;

//test data
// $gift_cli_id = "127";
// $gift_item_id = "testy";
// $gift_rec_id = "testy";
// $gift_exp = "testy";
// $gift_message = "testy";
// $gift_to = "testy";
// $gift_display_sender = "testy";
// $gift_total = "testy";
// $gift_counter = "testy";
// $gift_gif_id = "testy";
// $gift_wi_id = "testy";
// $gift_sender_confirmed = "testy";
// $gift_bank_confirmed = "testy";
// $add_new = "testy";


$gift_cli_id = false;
$gift_item_id = false;
$gift_rec_id = false;
$gift_exp = false;
$gift_message = false;
$gift_to = false;
$gift_display_sender = false;
$gift_total = false;
$gift_counter = false;
$gift_gif_id = false;
$gift_wi_id = false;
$gift_sender_confirmed = false;
$gift_bank_confirmed = false;
$telephone = false;
$add_new = false;
$get_all = false;

 // $telephone = "666666666";
 // $get_all = 1;
 // $gift_cli_id = "144";


$result = false;
$webcode = false; 

if (isset($_POST['gift_cli_id']))
	$gift_cli_id = $_POST['gift_cli_id'];
if (isset($_POST['gift_item_id']))
	$gift_item_id = $_POST['gift_item_id'];
if (isset($_POST['gift_rec_id']))
	$gift_rec_id = $_POST['gift_rec_id'];
if (isset($_POST['gift_exp']))
	$gift_exp = $_POST['gift_exp'];
if (isset($_POST['gift_message']))
	$gift_message = $_POST['gift_message'];
if (isset($_POST['gift_to']))
	$gift_to = $_POST['gift_to'];
if (isset($_POST['gift_display_sender']))
	$gift_display_sender = $_POST['gift_display_sender'];
if (isset($_POST['gift_total']))
	$gift_total = $_POST['gift_total'];
if (isset($_POST['gift_counter']))
	$gift_counter = $_POST['gift_counter'];
if (isset($_POST['gift_gif_id']))
	$gift_gif_id = $_POST['gift_gif_id'];
if (isset($_POST['gift_wi_id']))
	$gift_wi_id = $_POST['gift_wi_id'];
if (isset($_POST['gift_sender_confirmed']))
	$gift_sender_confirmed = $_POST['gift_sender_confirmed'];
if (isset($_POST['gift_bank_confirmed']))
	$gift_bank_confirmed = $_POST['gift_bank_confirmed'];
if (isset($_POST['telephone']))
	$telephone = $_POST['telephone'];
if (isset($_POST['add_new']))
	$add_new = 1;
if (isset($_POST['get_all']))
	$get_all = 1;


//Generate 14 digit random key
$digits = 7;
$code  = str_pad(rand(0, pow(14, $digits)-1), $digits, '0', STR_PAD_LEFT);
$code2  = str_pad(rand(0, pow(14, $digits)-1), $digits, '0', STR_PAD_LEFT);
$verify_code = strval($code).strval($code2); 


$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}


//Add new gift
if ($add_new)
	{
		
	
	//Temp bypass to test
    $qStr = "INSERT INTO `gift` (`gift_cli_id`, `gift_rec_id`,  `gift_item_id`,  `gift_exp`,  `gift_message`,  `gift_to`,  `gift_display_sender`,  `gift_total`,  `gift_counter`,  `gift_gif_id`,  `gift_wi_id`,  `gift_date`,  `gift_sender_confirmed`,  `gift_bank_confirmed`,  `gift_web_id`)";
    $qStr = $qStr." VALUES";
	$qStr = $qStr." ('".$gift_cli_id."','".$gift_rec_id."','".$gift_item_id."','".$gift_exp."','".$gift_message."','".$gift_to."','".$gift_display_sender."','".$gift_total."','".$gift_counter."','".$gift_gif_id."','".$gift_wi_id."',NOW(),'".$gift_sender_confirmed."','".$gift_bank_confirmed."','".$verify_code."');";
    //echo $qStr;
	
	$result = mysqli_query($con,$qStr);
    if ($result)
	print json_encode($verify_code);
		else 
	print json_encode($result);
	}
//Retreive all gifts from db
else if ($get_all){
  $qStr = "SELECT * FROM gift WHERE gift_cli_id = ".$gift_cli_id." OR gift_rec_telephone = ".$telephone." ORDER BY gift_date DESC;";
  $results = mysqli_query($con,$qStr);
	while ($row_items1 = mysqli_fetch_assoc($results)) {
       $rows[] = $row_items1;
	   $single_id = $row_items1['gift_item_id'];
	   $cli_id = $row_items1['gift_cli_id'];
        //echo $single_id;
		if($single_id !== ''){
			
			//Item info
			$qStr = "SELECT * FROM item WHERE item_id = ".$single_id ;
			$result = mysqli_query($con,$qStr);
			while ($row_items = mysqli_fetch_assoc($result)) {
			   $rows[] = $row_items;
			}
			
			//Vendor info
			$qStr = "SELECT * FROM vendor WHERE vendor_id IN (SELECT item_vendor_id FROM item WHERE item_id = ".$single_id.")" ;
			
			$result = mysqli_query($con,$qStr);
			while ($row_items = mysqli_fetch_assoc($result)) {
			   $rows[] = $row_items;
			}

			//Vendor image info
			$qStr = "SELECT * FROM image WHERE img_id IN (SELECT vendor_img_id FROM vendor WHERE vendor_id IN (SELECT item_vendor_id FROM item WHERE item_id = ".$single_id."))" ;
			$result = mysqli_query($con,$qStr);
			while ($row_items = mysqli_fetch_assoc($result)) {
			   $rows[] = $row_items;
			}
			
			//Item image info
			$qStr = "SELECT * FROM image WHERE img_id IN (SELECT item_img_id FROM item WHERE item_id = ".$single_id.")";
			$result = mysqli_query($con,$qStr);
			while ($row_items = mysqli_fetch_assoc($result)) {
			   $rows[] = $row_items;
			}

			//From info
			$qStr = "SELECT * FROM client WHERE cli_id  = ".$cli_id;
			$result = mysqli_query($con,$qStr);
			while ($row_items = mysqli_fetch_assoc($result)) {
			   $rows[] = $row_items;
			}				
			
			//var_dump($rows);
			//print json_encode($rows);
			
		}    
	
	
	}
	//echo $qStr;
	//var_dump($rows);
    print json_encode($rows);
	//print json_encode($row);
	
}	


mysqli_close($con);
exit;
?>
