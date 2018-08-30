<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'db_functions.php';
$db = new DB_Functions();

//$telephone = '1234234586';
$telephone = false;
$name = false;
$surname = false;
$email = false;
$cli_id = false;
$cli_id_post = false;
$code = false;
$update = 0;
$resend = 0;

$result = false;
$cli_result = false;

$response = null;

if (isset($_POST['cli_id_post']))
	$cli_id_post = $_POST['cli_id_post'];
if (isset($_POST['name']))
	$name = $_POST['name'];
if (isset($_POST['surname']))
$surname = $_POST['surname'];
if (isset($_POST['code']))
$code = $_POST['code'];
if (isset($_POST['email']))
$email = $_POST['email'];
if (isset($_POST['update']))
$update = 1;
if (isset($_POST['resend']))
$resend = $_POST['resend'];
if (isset($_POST['telephone']) && !empty($_POST['telephone']))
$telephone = $_POST['telephone'];

//$verify_code = md5(date('YmdHms'));
$digits = 4;
$verify_code  = str_pad(rand(0, pow(10, $digits)-1), $digits, '0', STR_PAD_LEFT);


//var_dump($_POST);

//$telephone = '720347762';
//$code = '4427';
//$name = 'Robb';
//$surname = 'Keul';
//$update = 1;
//$email = 'rob@ga.com';
//$cli_id_post = '60';
//echo $telephone;
//echo $code;

$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}

//User logs in with SMS code
if (!empty($telephone) && !empty($code) && !$update)
	{
	//Temp bypass to test
    if ($code == "0000")
	$query = "SELECT * FROM client WHERE (cli_telephone = '".$telephone."') LIMIT 1";
	else
    $query = "SELECT * FROM client WHERE (cli_telephone = '".$telephone."' AND cli_verify_code = '".$code."')";
	$result = mysqli_query($con,$query);
		 while($e=mysqli_fetch_assoc($result)){
         $cli_result =  $e;

         }
    print json_encode($cli_result);
	}

//User submits only mobile number
else if (!empty($telephone) && (!$update))
	{

	$query = "SELECT cli_id,cli_verify_code,cli_verify FROM client WHERE cli_telephone = '".$telephone."'";
    $result = mysqli_query($con,$query);

    while($e=mysqli_fetch_assoc($result)){
      $cli_id =  $e['cli_id'];
	  $verified = $e['cli_verify'];
    }

	
	//echo $verify_code;
	//print json_encode($response);
	//echo sizeof($response);
	//var_dump($response);

	//If the number exists and verified => direct to login
	if (!empty($cli_id) && !empty($verified) && !$resend) {
	print "111111"; //random code which means the user exists
	} else
	{
	if (!empty($cli_id)) {
		$query = 'UPDATE client SET cli_verify_code = '.$verify_code.' WHERE cli_id = '.$cli_id;
		$result = mysqli_query($con,$query);
		//echo $query;
		//while($e=mysqli_fetch_assoc($result)){
          // $cli_id =  $e['cli_id'];
        // }
		//print json_encode($cli_id);
		print $verify_code; 
		//exit;
	} else {
    // create a new user
        //echo 'create';
		$qStr = "INSERT INTO `client` (`cli_telephone`,`cli_verify_code`,`cli_created`)";
    $qStr = $qStr." VALUES";
    $qStr = $qStr." ('".$telephone."', '".$verify_code."',NOW());";
		$result = mysqli_query($con,$qStr);
	  $sql2 = "SELECT cli_id,cli_verify_code FROM client WHERE cli_telephone = '".$telephone."'";
    $result2 = mysqli_query($con,$sql2);
		while($e2=mysqli_fetch_assoc($result2)){
         $cli_verify_code =  $e2['cli_verify_code'];
		}
		//print json_encode($cli_verify_code);
		print $cli_verify_code;
	}
	}
	

	}

//update user details for given client id
else if (!empty($name) && !empty($surname) && $update && !empty($telephone))
	{

		$qStr = "UPDATE client set cli_name = '".$name."', cli_surname = '".$surname."', cli_email = '".$email."', cli_verify = '1' WHERE cli_telephone = '".$telephone."'";
		//echo $qStr;
		$result = mysqli_query($con,$qStr);
		$sql2 = 'SELECT * FROM client WHERE cli_telephone = '.$telephone;
		//echo $sql2;
        $result2 = mysqli_query($con,$sql2);
		 while($e=mysqli_fetch_assoc($result2)){
         $cli_result =  $e;

         }
         print json_encode($cli_result);


		//return 'success';
		//echo $sql2;
		//exit;
	}




mysqli_close($con);
exit;
?>
