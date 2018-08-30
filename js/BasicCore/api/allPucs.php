<?php
require_once 'config_local.php';
 $result = rowSelect($databaseConnection,'puc_codes',"");

  $html = '<option value="0" selected>Choose your option</option>';
  while ($row=mysqli_fetch_assoc($result)){
        $name = $row['puc_code'].' - '.$row['puc_farm'];
        $html = $html."<option value=".$row['puc_code'].">".$name."</option>";
  }
  echo $html;

?>
