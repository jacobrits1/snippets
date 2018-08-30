<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");
ini_set('display_startup_errors',1);
ini_set('display_errors',1);
error_reporting(-1);
  $cellphone = '0000000';
  $giftcard = '000000';

  $cellphone = $_POST['cellphone'];
  $giftcard = $_POST['giftcard_code'];

  $apiID = "apiId: GiftDropQA";
  $apiPassword = "apiPassword: test";
  $json_arr = array("campaignId" => $giftcard,
                    "balance" => "1000",
                    "userRef"=> $cellphone,);
  $post_data = json_encode($json_arr);
  $url = "https://za-vsp-int.wigroup.co/cvs-issuer/rest/giftcards?issueWiCode=true";
  $ch = curl_init();
  curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json' , $apiID ,$apiPassword));
  curl_setopt($ch, CURLOPT_URL,$url);
  curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
  curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
  curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
  $result = curl_exec($ch);
  curl_close($ch);
  $result_arr = json_decode($result,true);
  $wicode = '0';
  foreach ($result_arr as $value) {
    if(strlen($value["wicode"]) > 10){
      $wicode = $value["wicode"];
    }
  }

  $con=mysqli_connect("localhost","medminco_demo","D3m0!234","medminco_giftdrop");

  if (mysqli_connect_errno())
  {
    echo "return: error - Failed to connect to MySQL: " . mysqli_connect_error();
  }
	$qStr = 'INSERT INTO `gift_transaction` (gif_wicode,gif_cellphone,gif_giftcard,gif_date_created) VALUES("'.$wicode.'","'.$cellphone.'","'.$giftcard.'",NOW())';
  //echo $qStr;
	$result = mysqli_query($con,$qStr);
	mysqli_close($con);

exit;
?>
