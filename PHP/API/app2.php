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
    $output = array();
    $mysqli->query("SET NAMES 'utf8'");
  	$sql="SELECT tra_id, tra_date_created, tra_slip_data FROM transaction LIMIT 20";//WHERE tra_email ='".$email."'";
  	$result=$mysqli->query($sql);
  	while($e=mysqli_fetch_assoc($result)){
            $e['tra_slip_data'] = strstr($e['tra_slip_data'], 'Cash');
            $e['tra_slip_data'] = strstr($e['tra_slip_data'], '[1B]![00]    Change ' ,true);
            $e['tra_slip_data'] = str_ireplace('Cash','',$e['tra_slip_data']);
            $phpdate = strtotime( $e['tra_date_created'] );
            $mysqldate = date( 'd M', $phpdate );
            $e['tra_date_created'] = $mysqldate;
        		$output[]=$e;
  			}
    if(count($output) == 0){
      $e['tra_id'] = "0";
      $e['tra_date_created'] = "";
      $e['tra_slip_data'] = "No Reciepts Yet";
      $output[]=$e;
    }
  	print(json_encode($output));
  	$mysqli->close();

?>
