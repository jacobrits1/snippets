<?php
header('content-type: application/json; charset=utf-8');
header("access-control-allow-origin: *");


$apiID = "apiId: GiftDropQA";
$apiPassword = "apiPassword: test";
$json_arr = array("campaignId" => "48262",
                  "balance" => "1000",
                  "userRef"=>"27720347762",);
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
mysqli_close($con);

foreach ($result_arr as $value) {
  var_dump($value["wicode"]);
}
exit;


 ?>
