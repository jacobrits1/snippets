<?php
require_once 'config_local.php';

$username = '';
$usersurname = '';
$usermobile = '';
$useremail = '';
$id = '';
$dob = '';
$role = '';

//Update profile
if (isset($_POST['action']) && isset($_POST['userid'])) {
	if ($_POST['action'] == 'updateDetails')
	{
		$userid = $_POST['userid'];
		
		if (isset($_POST['username']))
			$username = $_POST['username'];
		if (isset($_POST['usermobile']))
			$usermobile = $_POST['usermobile'];
		if (isset($_POST['usersurname']))
			$usersurname = $_POST['usersurname'];
		if (isset($_POST['useremail']))
			$useremail = $_POST['useremail'];

	//check if email already in DB	
	if (isset($_POST["useremail"]) && !empty($_POST["useremail"])) {
		$result = rowSelect($databaseConnection,'user',"use_email ='".$_POST['useremail']."' AND use_id != '".$_POST['userid']."'");

		if(mysqli_num_rows($result) > 0)
			exit(json_encode(['error' => 'duplicate','message'=>'The email already exists']));
	}
	
	//check if mobile already in DB	
	if (isset($_POST["usermobile"]) && !empty($_POST["usermobile"])) {
		$result = rowSelect($databaseConnection,'user',"use_cellphone ='".$_POST['usermobile']."' AND use_id != '".$_POST['userid']."'");

		if(mysqli_num_rows($result) > 0)
			exit(json_encode(['error' => 'duplicate','message'=>'The mobile number already exists']));
	}	
		
	
  $result = rowUpdate($databaseConnection,'user', "use_email = '".$useremail."', use_name = '".$username."', use_surname = '".$usersurname."', use_cellphone = '".$usermobile."'" , "use_id = '".$userid."'");		
  
  exit(json_encode(['success' => 'Got Info','name'=> $username , 'surname'=>$usersurname, 'id'=>$userid, 'email'=>$useremail]));
					
  }
  
}

//Update DOB
if (isset($_POST['action']) && isset($_POST['userid'])) {
	if ($_POST['action'] == 'updateDob')
	{
		$userid = $_POST['userid'];
		
		if (isset($_POST['userdob']))
			$dob = $_POST['userdob'];
		if (isset($_POST['userrole']))
			$role = $_POST['userrole'];
	
  $result = rowUpdate($databaseConnection,'user', "use_dob = '".$dob."', use_position = '".$role."'" , "use_id = '".$userid."'");		  
  exit(json_encode(['success' => 'Got Info','dob'=> $dob , 'role'=>$role]));
					
  }

}

//read profile data
else if (isset($_POST['userid']) && isset($_POST['username'])) {

  $userid = str_replace(' ','',$_POST['userid']);
  $username = str_replace(' ','',$_POST['username']);
  //$username = str_replace($invalid_characters, "", $username);

  $result = rowSelect($databaseConnection,'user',"use_name ='".$username."' AND use_id='".$userid."'");

  if(mysqli_num_rows($result) > 0){
      while ($row=mysqli_fetch_assoc($result)){
        $name = $row['use_name'];
        $surname = $row['use_surname'];
        $id = $row['use_id'];
        $role = $row['use_type'];
        $email = $row['use_email'];
		$mobile = $row['use_cellphone'];
		$dob = $row['use_dob'];
		$position = $row['use_position'];		
      }
      exit(json_encode(['success' => 'Got Info','name'=> $name , 'surname'=>$surname, 'id'=>$id, 'role'=>$role, 'email'=>$email , 'mobile'=>$mobile,'dob'=>$dob,'position'=>$position]));
  }else{
      exit(json_encode(['error' => 'Error']));
  }


}else{
  exit(json_encode(['error' => 'There was an error']));
}

?>
