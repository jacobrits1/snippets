<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'db_functions.php';
$db = new DB_Functions();

$cardnumber = false;
$expiry = false;
$ccvnum = false;
$cardname = false;
$cardtype = false;
$cli_id = false;
$check = false;
$delete = false;
//Test Data:
// $cardnumber = "1234123412341234";
// $expiry = "19/03";
// $ccvnum = "123";
// $cardname = "MR P POMPIES";
// $cardtype = "PERSONAL";
//$cli_id = "130";
//$check = 1;
//$delete = 1;

$result = false;
$cli_result = false;

if (isset($_POST['cardnumber']))
	$cardnumber = $_POST['cardnumber'];
if (isset($_POST['expiry']))
	$expiry = $_POST['expiry'];
if (isset($_POST['ccvnum']))
	$ccvnum = $_POST['ccvnum'];
if (isset($_POST['cardname']))
	$cardname =	$_POST['cardname'];
if (isset($_POST['cardtype']))
	$cardtype =	$_POST['cardtype'];
if (isset($_POST['cli_id']))
	$cli_id = $_POST['cli_id'];
if (isset($_POST['check']))
	$check = 1;
if (isset($_POST['delete']))
	$delete = 1;

$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}

//Check card
if (!empty($cli_id) && $check)
{
	$sql2 = "SELECT card_cardnumber FROM card_details WHERE card_cli_id = '".$cli_id."'";
	$result2 = mysqli_query($con,$sql2);
	while($e2=mysqli_fetch_assoc($result2)){
	 $cli_result =  $e2['card_cardnumber'];
	}
	print json_encode($cli_result);	
}

//Delete card

else if (!empty($cli_id) && $delete)
{
	//Delete existing card (if there is one)	
    $sql2 = "DELETE FROM `card_details` WHERE card_cli_id = '".$cli_id."'";
	$result2 = mysqli_query($con,$sql2);
	
	print json_encode(true);	
}

//Add new card
else if (!empty($cardnumber) && !empty($expiry) && !empty($ccvnum) && !empty($cardname) && !empty($cardtype) && !empty($cli_id))
	{
		
	//Delete existing card (if there is one)	
    $sql2 = "DELETE FROM `card_details` WHERE card_cli_id = '".$cli_id."'";
	$result2 = mysqli_query($con,$sql2);
			
	//Temp bypass to test
    $qStr = "INSERT INTO `card_details` (`card_cli_id`,`card_cardnumber`,`card_expiry`,`card_ccvnum`,`card_cardname`,`card_cardtype`,`card_date_added`)";
    $qStr = $qStr." VALUES";
    $qStr = $qStr." ('".$cli_id."','".$cardnumber."','".$expiry."','".$ccvnum."','".$cardname."','".$cardtype."',NOW());";
	$result = mysqli_query($con,$qStr);

	$sql2 = "SELECT card_id FROM card_details WHERE card_cli_id = '".$cli_id."'";
	$result2 = mysqli_query($con,$sql2);
	while($e2=mysqli_fetch_assoc($result2)){
	 $cli_id =  $e2['card_id'];
	}
	print json_encode($cli_id);
	
	} else print json_encode(false);
	


mysqli_close($con);
exit;
?>
