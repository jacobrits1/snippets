<?php
require_once 'config_local.php';

  //Determine which option was chosen to send back via Json
  if (!empty($_POST['forgotPasswordEmail']))
	  $sentto = $_POST['forgotPasswordEmail'];
  else if (!empty($_POST['forgotPasswordMobile']))
	  $sentto = $_POST['forgotPasswordMobile'];
  else $sentto = '';
  
  $result = rowSelect($databaseConnection,'user',"use_email ='".$_POST['forgotPasswordEmail']."' OR use_cellphone ='".$_POST['forgotPasswordMobile']."'");

  if(mysqli_num_rows($result) > 0){ //It seems like even if the match isnt found it gives number of rows as 1
      while ($row=mysqli_fetch_assoc($result)){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $password = substr( str_shuffle( $chars ), 0, 8 );
        $name = $row['use_name'];
        $surname = $row['use_surname'];
        $id = $row['use_id'];
        $role = $row['use_type'];
        $email = $row['use_email'];
        $mobile = $row['use_cellphone'];
		$username = $row['use_username'];
      }
	  
      $result = rowUpdate($databaseConnection,'user',"use_password='".md5($password."fruitflo2018")."'","use_email='".$_POST['forgotPasswordEmail']."' OR use_cellphone='".$_POST['forgotPasswordMobile']."'");

      if(strlen($_POST['forgotPasswordEmail'])> 2){

        $subject = 'Fruitflo App Reset Password';

        $body = ' Dear '.$name.',
                    <br>Your Password was reset, Your new details:
                    <br>
                    <br>Username :'.$username.'
                    <br>Password :'.$password.'
                    <br>
                    <br>FruitFlo System<br>
                    <img src="http://fruitflo.medmin.co.za/app/assets/img/ffLogo.png">';


        sendEmail($email,$subject,$body,$attachments);
      }

       if(strlen($_POST['forgotPasswordMobile'])> 2){
        include_once("bulksms.php");

        $body = 'Your updated Login information is  : Username :'.$username.', Password :'.$password.' Regrads Fruitflo System';
        sentPasswordSMS($body,$mobile);

      }



      exit(json_encode(['successStatus' => '1', 'success' => 'Got Info','name'=> $name , 'surname'=>$surname, 'id'=>$id, 'role'=>$role,  'sentto'=>$sentto]));
  }else{
     exit(json_encode(['successStatus' => '0', 'failed' => 'Your email or number could not be found']));
  }
?>
