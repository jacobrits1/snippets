<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");

$password = false;
$shoot = false;
$venue = false;


$action = $_POST['action'];
$email = $_POST['email'];
$devicename = $_POST['devicename'];


$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_digislip");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}


if($action == 1){ // log
    $till_id = $_POST['tid'];
    $slip_id = $_POST['sid'];
    $qr_code = $_POST['qrc'];
    $slip_data = $_POST['slipdata'];


    $qStr = "INSERT INTO `log_till` (`log_slip`, `log_qrcode`,`log_slip_id`,`log_till_id`, `created`)";
    $qStr = $qStr." VALUES";
	  $qStr = $qStr." ('".$slip_data."', '".$qr_code."', '".$slip_id."', '".$till_id."',NOW());";
    $result = mysqli_query($con,$qStr);

}

mysqli_close($con);
exit;
?>
