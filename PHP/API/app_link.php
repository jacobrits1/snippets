<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);


//$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_digislip");
// Check connection

$mysqli = new mysqli("localhost","medminco_demo","D3m0!234","medminco_digislip");//($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
  	/* check connection */
  	if (mysqli_connect_errno()) {
     		printf("Connect failed: %s\n", mysqli_connect_error());
     		exit();
  	}
    $email = $_GET['e'];
    $sid = $_GET['sid'];

    $mysqli->query("SET NAMES 'utf8'");
    $sql = "UPDATE transaction SET tra_email='".$email."' WHERE tra_id=".$sid;
  	$result=$mysqli->query($sql);

    $sql="SELECT tra_id,tra_date_created, tra_slip_data FROM transaction WHERE tra_email ='".$email."'";
    $result=$mysqli->query($sql);
    while($e=mysqli_fetch_assoc($result)){
        $e['tra_slip_data'] = strstr($e['tra_slip_data'], 'Cash');
        $e['tra_slip_data'] = strstr($e['tra_slip_data'], '[1B]![00]    Change ' ,true);
        $e['tra_slip_data'] = str_ireplace('Cash','Total',$e['tra_slip_data']);
        $output[]=$e;
    }

  	print(json_encode($output));
  	$mysqli->close();
?>
