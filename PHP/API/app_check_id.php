<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'db_functions.php';
$db = new DB_Functions();

$cli_id = '';
$cli_result = '';
$result = false;
$response = null;

if (isset($_POST['cli_id']))
	$cli_id = $_POST['cli_id'];


$con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");
// Check connection

if (mysqli_connect_errno())
{
  echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
}

//User logs in with SMS code

if (!empty($cli_id))
	{
	    $sql2 = "SELECT * FROM client WHERE cli_id = '".$cli_id."'";
        $result2 = mysqli_query($con,$sql2);
		while($e2=mysqli_fetch_assoc($result2)){
         $cli_result =  $e2['cli_telephone'];
		}
		print json_encode($cli_result);
	}

//echo "cheers";

mysqli_close($con);
exit;
?>
