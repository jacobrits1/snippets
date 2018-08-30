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
    $id = $_GET['i'];

    $mysqli->query("SET NAMES 'utf8'");
  	$sql="SELECT tra_slip_data as data FROM transaction WHERE tra_id ='".$id."'";
  	$result=$mysqli->query($sql);
  	while($e=mysqli_fetch_assoc($result)){
        $str     = $e['data'];
        $order   = array("@","!","[1B]","[00]","[1C]p[01]0",);
        $replace = '';

        $e['data'] = str_replace($order, $replace, $str);
            // $e['tra_slip_data'] = strstr($e['tra_slip_data'], 'Cash');
            // $e['tra_slip_data'] = strstr($e['tra_slip_data'], '[1B]![00]    Change ' ,true);
        // $e['data'] = ;

        $output[]=$e;
  	}

  	print(json_encode($output));
  	$mysqli->close();
?>
