<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
// error_reporting(E_ALL);
// ini_set('display_errors', 1);
require_once 'db_functions.php';
$db = new DB_Functions();


	// $_POST['name'] = "Piet";
	// $_POST['password'] = "1234";
	// $_POST['telephone'] = "0723456678";
	// $_POST['email'] = "robbie@gmail.com";

// json response array
$response = array("error" => FALSE);

if (isset($_POST['name']) && isset($_POST['telephone']) && isset($_POST['password'])) {
    $con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
    // Check connection

    if (mysqli_connect_errno())
    { 
      echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
    }
    // receiving the post params
    // $name = $_POST['name'];
    // $surname = $_POST['surname'];
    // $email = $_POST['email'];
    // $password = $_POST['password'];
    // $mobile = $_POST['telephone'];
	

	
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $password = $_POST['password'];
    $email = $_POST['email'];
    $telephone = $_POST['telephone'];
    $verify_code = md5(date('YmdHms'));
    $response = 0;

    // check if user is already existed with the same mobile
    $qStr = "SELECT cli_id FROM client WHERE cli_telephone = '".$telephone."'";
    $result = mysqli_query($con,$qStr);

    while($e=mysqli_fetch_assoc($result)){
      $response[] = $e;
    }

    if ($result > 1) {
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
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters (name, telephone or password) is missing!";
    print json_encode($response);
}


?>
