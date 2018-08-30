<?php
require_once 'config_local.php';

  $result = rowSelect($databaseConnection,'user',"use_email ='".$_POST['forgotPasswordEmail']."' OR use_cellphone ='".$_POST['forgotPasswordMobile']."'");

  if(mysqli_num_rows($result) > 0){ //It seems like even if the match isnt found it gives number of rows as 1
      while ($row=mysqli_fetch_assoc($result)){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%&*_";
        $tempPassword = $row['use_password'];
        $name = $row['use_name'];
        $surname = $row['use_surname'];
        $id = $row['use_id'];
        $role = $row['use_type'];
        $email = $row['use_email'];
        $mobile = $row['use_cellphone'];
		$username = $row['use_username'];
      }
	  
	  //check if the temporary password matches the one in the DB
	  if ($tempPassword == md5($_POST['forgotPasswordResetTemp']."fruitflo2018")){

      $result = rowUpdate($databaseConnection,'user',"use_password='".md5($_POST['forgotPasswordResetNew']."fruitflo2018")."'","use_email='".$_POST['forgotPasswordEmail']."' OR use_cellphone='".$_POST['forgotPasswordMobile']."'");


      exit(json_encode(['successStatus' => '1', 'success' => 'Got Info','name'=> $name , 'surname'=>$surname, 'id'=>$id, 'role'=>$role]));
  }else{
     exit(json_encode(['successStatus' => '0', 'failed' => 'The temporary password was incorrect']));
  }
  }else{
     exit(json_encode(['successStatus' => '0', 'failed' => 'Something went wrong, please try again']));
  }
?>
