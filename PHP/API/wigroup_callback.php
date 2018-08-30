<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}

if(!empty($_POST)){
	$data = $_POST;
	$qStr = 'INSERT INTO `wigroup_callback` (wig_data,wig_type,wig_date_created) VALUES("'.json_encode($data).'",2,NOW())';
	//echo $qStr;
	$result = mysqli_query($con,$qStr);
	mysqli_close($con);
	echo "{'respnse':'true'}";
}else{
	mysqli_close($con);
	echo "{'respnse':'false' , 'reason':'no data'}";
}
exit;
?>
