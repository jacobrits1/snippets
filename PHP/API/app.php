<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

$password = false;
$shoot = false;
$venue = false;

//var_dump($_POST);
$item_id = '';
$single_id = '';
//$item_id = 'all';

if (isset($_POST['item_action']))
	$action = $_POST['action'];
if (isset($_POST['item_id']))
	$item_id = $_POST['item_id'];
if (isset($_POST['single_id']))
	$single_id = $_POST['single_id'];


$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}

$sql_duplicate_rows = 'INSERT INTO item
SELECT NULL item_id, item_vendor_id, item_name, item_description, item_price, item_date_created, item_active, item_exp, item_img_id
FROM item
WHERE item_id IN (2,3,4,5,6,7,8,9)';


if($item_id == 'all'){ // return array of items with image info
    

    // $qStr_items = "SELECT * FROM item";
    // $result_items = mysqli_query($con,$qStr_items);
    // while ($row_items = mysqli_fetch_assoc($result_items)) {
       // $rows[] = $row_items;
    // }

	//All item and image info for active items	
$qStr3 = "SELECT
  i.img_id, i.img_name, 
  i.img_ext, i.img_folder, t.item_id, t.item_vendor_id, t.item_price, t.item_active 
FROM
  image i 
  INNER JOIN item t 
    ON i.img_id = t.item_img_id
	WHERE t.item_active = 1
	ORDER BY t.item_id DESC";	

	$result_images = mysqli_query($con,$qStr3);
    while ($row_items = mysqli_fetch_assoc($result_images)) {
       $rows2[] = $row_items;
    }
	
	//All vendor and image info	
$qStr2 = "SELECT
  i.img_id, i.img_name, 
  i.img_ext, i.img_folder, v.vendor_id 
FROM
  image i 
  INNER JOIN vendor v 
    ON i.img_id = v.vendor_img_id";	
	

	$result_images = mysqli_query($con,$qStr2);
    while ($row_items = mysqli_fetch_assoc($result_images)) {
       $rows2[] = $row_items;
    }	
	
	 $qStr_image_ids = "SELECT  FROM item";
	$qStr_images = "SELECT * FROM image WHERE img_id IN ('1','2','3')";
	

    // $return_arr = array();
    // $qStr_log = "SELECT cli_slip_id FROM client_slip_link WHERE cli_client_id = '".$client_id."'";
    // $result_log = mysqli_query($con,$qStr_log);
    // while ($row_log = mysqli_fetch_assoc($result_log)) {
      // $log_id = $row_log['cli_slip_id'];
      // $qStr_slip = "SELECT log_created,log_till_id FROM log_till WHERE log_id = '".$log_id."'";
      // $result_slip = mysqli_query($con,$qStr_slip);
      // while ($row_slip = mysqli_fetch_assoc($result_slip)) {
         // $return_arr['date_created'] = $row_slip['log_created'];
         // $return_arr['till_id'] = $row_slip['log_till_id'];
      // }
    // }
//var_dump($rows);
   //return json_decode($rows);
  print json_encode($rows2);
  //return "hallo";

}

else $single_id = '1';

if($single_id == '1'){
	$qStr = "SELECT * FROM item WHERE item_id = ".$single_id ;
	
	$result = mysqli_query($con,$qStr);
    while ($row_items = mysqli_fetch_assoc($result)) {
       $rows[] = $row_items;
    }	
	//var_dump($rows);
	print json_encode($rows);
	
}


if($action == "1"){ // login
    $email = $_POST['email'];
    $password = $_POST['pass'];
    $apikey = $_POST['apikey'];
    $cli_id = 0;
    $qStr = "SELECT cli_id WHERE `cli_email` ='".$email."' AND `cli_password` ='".$password."' )";
    $result = mysqli_query($con,$qStr);
    while ($row = mysqli_fetch_assoc($result)) {
      $cli_id = $row['cli_id'];
    }
    return $cli_id;

}
if($action == "2"){ // register

    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $password = $_POST['pass'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $verify_code = md5(date('YmdHms'));

    $qStr = "INSERT INTO `client` (`cli_name`, `cli_surname`,`cli_email`,`cli_telephone`,`cli_password`,`cli_verify_code`,`cli_created`)";
    $qStr = $qStr." VALUES";
	  $qStr = $qStr." ('".$name."', '".$surname."', '".$email."', '".$telephone."', '".md5($password)."','".$verify_code."',NOW());";
    $result = mysqli_query($con,$qStr);
    if($result === FALSE){
      return 'fails';
    }
    return 'success';
}

if($action == "3"){ // check if exsits
    $email = $_POST['email'];

    $qStr = "SELECT id FROM client WHERE cli_email = '".$email."'";
    $result = mysqli_query($con,$qStr);

}
if($action == 4){ // link slip to client
    $email = $_POST['email'];
    $qr_code = $_POST['qrc'];

    $qStr = "SELECT log_id FROM log_till WHERE log_qrcode = '".$qr_code."'";
    $result = mysqli_query($con,$qStr);
    while ($row = mysqli_fetch_assoc($result)) {
      $slip_id = $row['log_id'];
    }

    $qStr_client = "SELECT cli_id FROM client WHERE cli_email = '".$email."'";
    $result_client = mysqli_query($con,$qStr_client);
    while ($row_client = mysqli_fetch_assoc($result_client)) {
      $client_id = $row_client['cli_id'];
    }

    $qStr = "";
    $qStr = "INSERT INTO `client_slip_link` (`cli_slip_id`, `cli_client_id`,`cli_created`)";
    $qStr = $qStr." VALUES";
    $qStr = $qStr." ('".$slip_id."', '".$client_id."',NOW());";
    $result = mysqli_query($con,$qStr);

}

if($action == 5){ // list of slips
    $email = $_POST['email'];

    $qStr_client = "SELECT cli_id FROM client WHERE cli_email = '".$email."'";
    $result_client = mysqli_query($con,$qStr_client);
    while ($row_client = mysqli_fetch_assoc($result_client)) {
      $client_id = $row_client['cli_id'];
    }

    $return_arr = array();
    $qStr_log = "SELECT cli_slip_id FROM client_slip_link WHERE cli_client_id = '".$client_id."'";
    $result_log = mysqli_query($con,$qStr_log);
    while ($row_log = mysqli_fetch_assoc($result_log)) {
      $log_id = $row_log['cli_slip_id'];
      $qStr_slip = "SELECT log_created,log_till_id FROM log_till WHERE log_id = '".$log_id."'";
      $result_slip = mysqli_query($con,$qStr_slip);
      while ($row_slip = mysqli_fetch_assoc($result_slip)) {
         $return_arr['date_created'] = $row_slip['log_created'];
         $return_arr['till_id'] = $row_slip['log_till_id'];
      }
    }

  return json_decode($return_arr);

}


mysqli_close($con);
exit;
?>
