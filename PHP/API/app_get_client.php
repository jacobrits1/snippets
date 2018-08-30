<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);

require_once 'db_functions.php';
$db = new DB_Functions();

$cli_result = false;
$cli_id = false;
//$cli_id = "127";

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
	//Temp bypass to test
    
	$query = "SELECT * FROM client WHERE cli_id = ".$cli_id;	
	$result = mysqli_query($con,$query);
		 while($e=mysqli_fetch_assoc($result)){
         $cli_result =  $e;

         }
    print json_encode($cli_result);		 
	}
	


mysqli_close($con);
exit;
?>
