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

if (isset($_POST['single_id']))
	$single_id = $_POST['single_id'];


$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}


if($single_id !== ''){
	$qStr = "SELECT * FROM item WHERE item_id = ".$single_id ;
	$qStr2 = "SELECT * FROM item WHERE item_id = ".$single_id ;
	$qStr3 = "SELECT * FROM item WHERE item_id = ".$single_id ;
	
	
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
	
	//var_dump($rows);
	print json_encode($rows);
	
}



mysqli_close($con);
exit;
?>
