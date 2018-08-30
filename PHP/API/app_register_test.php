<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'db_functions.php';
$db = new DB_Functions();

$password = false;
$shoot = false;
$venue = false;
//$telephone = '1234234586';
$telephone = false;
$name = false;
$surname = false;
$password = false;
$email = false;


$result = false;

$response = null;

if (isset($_POST['name']))
	$name = $_POST['name'];
if (isset($_POST['surname']))
$surname = $_POST['surname'];
if (isset($_POST['password']))
$password = $_POST['password'];
if (isset($_POST['email']))
$email = $_POST['email'];
if (isset($_POST['telephone']) && !empty($_POST['telephone']))
$telephone = $_POST['telephone'];

$verify_code = md5(date('YmdHms'));

//var_dump($_POST);
$item_id = '';
$single_id = '2';

if (isset($_POST['single_id']))
	$single_id = $_POST['single_id'];


$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}


$qStr = "SELECT cli_id FROM client WHERE cli_telephone = '".$telephone."'";
    //echo $qStr;
	$result = mysqli_query($con,$qStr);

    while($e=mysqli_fetch_assoc($result)){
      $response[] = $e;
    }
	
	//print json_encode($response);
	//echo sizeof($response);
	//var_dump($response);
	
if (sizeof($response) >0) {
	print json_encode($response);
	//exit;
} else {

// create a new user

        $qStr = "INSERT INTO `client` (`cli_name`, `cli_surname`,`cli_email`,`cli_telephone`,`cli_password`,`cli_verify_code`,`cli_created`)";
        $qStr = $qStr." VALUES";
        $qStr = $qStr." ('".$name."', '".$surname."', '".$email."', '".$telephone."', '".md5($password)."','".$verify_code."',NOW());";
        $result = mysqli_query($con,$qStr);
        $sql2 = "SELECT cli_id FROM client WHERE cli_telephone = '".$telephone."'";
        $result2 = mysqli_query($con,$sql2);
        while($e2=mysqli_fetch_assoc($result2)){
          $rows[] = $e2;
          //exit;
        }
		print json_encode($rows);
        //return 'success';
        //echo $sql2;
        //exit;
	}

mysqli_close($con);
exit;
?>
