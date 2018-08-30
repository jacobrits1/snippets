<?php
require_once 'config_local.php';

if (isset($_POST['registerDetailsName']) && isset($_POST['registerPasswordEnter'])) {

  //$result = rowSelect($databaseConnection,'user',"use_username ='".$_POST['registerDetailsEmail']."' AND use_password='".md5($_POST['registerPasswordEnter']."fruitflo2018")."'");
  $result = '';
  
  if(mysqli_num_rows($result) > 0){
      while ($row=mysqli_fetch_assoc($result)){
        $name = $row['use_name'];
        $surname = $row['use_surname'];
        $id = $row['use_id'];
        $role = $row['use_type'];
      }
      exit(json_encode(['success' => 'Got Info','name'=> $name , 'surname'=>$surname, 'id'=>$id, 'role'=>$role]));
  }else{

   if (isset($_POST['registerDetailsEmail']))
		$username = $_POST['registerDetailsEmail'];
	if ($username == '')
		$username = $_POST['registerDetailsMobile'];

	  
    $dataArray = array('use_puc' => $_POST['registerPuc'],
                       'use_exporter' => $_POST['registerExporter'],
                       'use_importer' => $_POST['registerImporter'],
                       'use_name' => $_POST['registerDetailsName'],
                       'use_surname' => $_POST['registerDetailsSurname'],
                       'use_email' => $_POST['registerDetailsEmail'],
					   'use_username' => $username,
                       'use_cellphone' => $_POST['registerDetailsMobile'],
                       'use_dob' => $_POST['registerDobDate'],
                       'use_position' => $_POST['registerDobPosition'],
                       'use_type' => '2',
                       'use_date_created' => date("Y-m-d H:i:s"),
                       'use_password' => md5($_POST['registerPasswordEnter']."fruitflo2018"));
					   
	//check if email already in DB	
	if (isset($_POST["registerDetailsEmail"]) && !empty($_POST["registerDetailsEmail"])) {
		$result = rowSelect($databaseConnection,'user',"use_email ='".$_POST['registerDetailsEmail']."'");

		if(mysqli_num_rows($result) > 0)
			exit(json_encode(['error' => 'error','message'=>'The email already exists']));
	}
	
	//check if mobile already in DB	
	if (isset($_POST["registerDetailsMobile"]) && !empty($_POST["registerDetailsMobile"])) {
		$result = rowSelect($databaseConnection,'user',"use_cellphone ='".$_POST['registerDetailsMobile']."'");

		if(mysqli_num_rows($result) > 0)
			exit(json_encode(['error' => 'error','message'=>'The mobile number already exists']));
	}	
	

    rowInsert($databaseConnection,'user',$dataArray);
    $result = rowSelect($databaseConnection,'user',"use_username ='".$username."' AND use_password='".md5($_POST['registerPasswordEnter']."fruitflo2018")."'");

    while ($row=mysqli_fetch_assoc($result)){
      $name = $row['use_name'];
      $surname = $row['use_surname'];
      $id = $row['use_id'];
      $role = $row['use_type'];
    }
	
	//send email if these is an email entered
	if (isset($_POST["registerDetailsEmail"]) && !empty($_POST["registerDetailsEmail"])) 
	{

    $subject = 'Welcome to Fruitflo App';

    $body = ' Welcome '.$_POST['registerDetailsName'].',
              <br>You can now start using the Fruitflow App
              <br>
              <br>Username :'.$username.'
              <br>Password :'.$_POST['registerPasswordEnter'].'
              <br>
              <br>FruitFlo System<br>
              <img src="http://fruitflo.medmin.co.za/app/assets/img/ffLogo.png">';


    sendEmail($_POST['registerDetailsEmail'],$subject,$body,$attachments);
	}
	
	//send sms if these is not an email entered and if there is a mobile entered
	else if (isset($_POST["registerDetailsMobile"]) && !empty($_POST["registerDetailsMobile"])) 
	{
	include_once("bulksms.php");

    $body = ' Your updated Login information is  : Username :'.$username.' Password :'.$_POST['registerPasswordEnter'].' Regrads Fruitflo System';
    sentPasswordSMS($body,$_POST["registerDetailsMobile"]);
	}


    exit(json_encode(['success' => 'Got Info','name'=> $name , 'surname'=>$surname, 'id'=>$id, 'role'=>$role]));

  }


}else{
  exit(json_encode(['error' => 'No names or password']));
}

?>
