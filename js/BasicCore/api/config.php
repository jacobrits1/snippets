<?php
/*
Author : Mobilee
Developer : Jaco Brits
Email : jaco@mobileeapps.com

Description:
Basic Security
Token check
Apikey check
Basic Model / Mysql Connection file with some base actions

Functions:

rowInsert($table_name, $form_data)   example : rowInsert('users',array[name]='jaco')
rowDelete($table_name, $where_clause='')   example : rowInsert('users',array[name]='jaco')
function rowUpdate($table_name, $form_data, $where_clause='')


*/
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
ini_set('memory_limit', '2048M');
error_reporting(E_ALL);
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(rand(32,32));
}

header('Content-Type: application/json');

$headers = apache_request_headers();
if (isset($headers['CsrfToken'])) {
    if ($headers['CsrfToken'] !== $_SESSION['csrf_token']) {
        exit(json_encode(['error' => 'Wrong CSRF token.']));
    }
} else {
    exit(json_encode(['error' => 'No CSRF token.']));
}

if($_POST['apikey'] == "cd8f23a8e07a223e8d89f3fbf42c3874"){

  $servername = "localhost";
  $username = "medminco_demo";
  $password = "D3m0!234";
  $db_name = "medminco_fruitflo";
  $invalid_characters = array("$", "%", "#", "<", ">", "|");


  $databaseConnection = new mysqli($servername, $username, $password, $db_name);
  if ($centralConn->connect_error) {
  	die("1 - Connection failed: " . $centralConn->connect_error);
    $connected_button = "danger";
    $connected_message = "<strong>Failed:</strong> Could not connect to Fancam Database.";
  }
}else{
  exit(json_encode(['error' => 'invalid APIKEY']));
}


function rowInsert($table_name, $form_data)
{
    // retrieve the keys of the array (column titles)
    $fields = array_keys($form_data);

    // build the query
    $sql = "INSERT INTO ".$table_name."
    (`".implode('`,`', $fields)."`)
    VALUES('".implode("','", $form_data)."')";

    // run and return the query result resource
    return mysqli_query($databaseConnection,$sql);
}

function rowDelete($table_name, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add keyword
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // build the query
    $sql = "DELETE FROM ".$table_name.$whereSQL;

    // run and return the query result resource
    return mysqli_query($databaseConnection,$sql);
}

// again where clause is left optional
function rowUpdate($table_name, $form_data, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "UPDATE ".$table_name." SET ";

    // loop and build the column /
    $sets = array();
    foreach($form_data as $column => $value)
    {
         $sets[] = "`".$column."` = '".$value."'";
    }
    $sql .= implode(', ', $sets);

    // append the where statement
    $sql .= $whereSQL;

    // run and return the query result
    return mysqli_query($databaseConnection,$sql);
}

// again where clause is left optional
function rowSelect($table_name, $where_clause='')
{
    // check for optional where clause
    $whereSQL = '';
    if(!empty($where_clause))
    {
        // check to see if the 'where' keyword exists
        if(substr(strtoupper(trim($where_clause)), 0, 5) != 'WHERE')
        {
            // not found, add key word
            $whereSQL = " WHERE ".$where_clause;
        } else
        {
            $whereSQL = " ".trim($where_clause);
        }
    }
    // start the actual SQL statement
    $sql = "SELECT * FROM ".$table_name." ";

    // append the where statement
    $sql .= $whereSQL;

    // run and return the query result
    return mysqli_query($databaseConnection,$sql);
}

function saveFile($type,$id,$filedata){
  //var_dump($filedata);exit;
  $target_dir = "../assets/uploads/".$id."/".$type."/";
  if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
  }
  $target_file = $target_dir . $id ."_" . basename($filedata["name"]);
  $uploadOk = 1;
  $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

  $check = getimagesize($filedata["tmp_name"]);
  if($check !== false) {
    move_uploaded_file($filedata["tmp_name"], $target_file);
  }

  return $target_file;
}



?>
