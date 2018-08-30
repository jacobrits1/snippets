<?php
require_once 'config_local.php';

if (isset($_POST['username']) && isset($_POST['password'])) {

  $username = str_replace(' ','',$_POST['username']);
  $password = str_replace(' ','',$_POST['password']);
  $username = str_replace($invalid_characters, "", $username);
  $password = str_replace($invalid_characters, "", $password);
  $password = md5($password."fruitflo2018");

  $result = rowSelect($databaseConnection,'user',"use_username ='$username' AND use_password='".$password."'");

  if(mysqli_num_rows($result) > 0){
      while ($row=mysqli_fetch_assoc($result)){
        $name = $row['use_name'];
        $surname = $row['use_surname'];
        $id = $row['use_id'];
        $role = $row['use_type'];
      }
      exit(json_encode(['success' => 'Got Info','name'=> $name , 'surname'=>$surname, 'id'=>$id, 'role'=>$role]));
  }else{
      exit(json_encode(['error' => 'Encorrect Usename or Password']));
  }


}else{
  exit(json_encode(['error' => 'No email or password']));
}

?>
