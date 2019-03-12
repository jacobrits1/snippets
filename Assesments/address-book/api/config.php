<?php
/*

Developer : Jaco Brits
Email : jaco@netstart.co.za

Description:
address 3rd Party config file.
The token CSRF security on the ajax calls was removed

Also a remote mySQL DB connection to save alarm details


Functions:

sentToaddress($token,$message,$channel)
setAReminderaddress($token,$message,$channel,$time)
insertAlarmToDB($uuid,$alarm,$message)
rowActiveUpdate($dbconnection,$uuid)
rowSelectall($dbconnection)
jsonfyResult($dbObject)
*/

session_start();

/**
 * Conection details to a Remote Hosted DB
 * Table: addressAlarm
 * id , uuid , message , alarm , active , time_created
 *
 *
 */
  $servername = "medmin.co.za";
  $username = "medminco_address";
  $password = "p28nZXt#th3Y";
  $db_name = "medminco_address";
  $invalid_characters = array("$", "%", "#", "<", ">", "|");


  $databaseConnection = new mysqli($servername, $username, $password, $db_name);
  if ($databaseConnection->connect_error) {
  	die("1 - Connection failed: " . $databaseConnection->connect_error);
    $connected_button = "danger";
    $connected_message = "<strong>Failed:</strong> Could not connect to addressbook Database.";
  }

/**
 * Insert row to DB
 *
 *
 * @param string $databaseConnection use current or another DB connection.
 * @param string $table_name table to insert values to.
 * @param string $form_data array of [fieldname] => Value inserted
 * @return object of row spesified
 */

function rowInsert($databaseConnection,$table_name, $form_data)
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

/**
 * Update spesific row to non active from UUID
 *
 *
 * @param string $databaseConnection use current or another DB connection.
 * @param string $uuid.
 * @return object
 */

function rowActiveUpdate($databaseConnection,$id)
{
    // build the update query to set active to 0
    $sql = "UPDATE person SET per_active = 0 WHERE per_id=".$id."";
    // run and return the query result
    return mysqli_query($databaseConnection,$sql);
}

/**
 * Select spesific row on DB
 *
 *
 * @param string $databaseConnection use current or another DB connection.
 * @param string $table_name table to insert values to.
 * @param string $form_data array of [fieldname] => Value inserted
 * @param string $where_clause is by default empty and can be spesified
 * @return object
 */
function rowSelect($databaseConnection,$table_name, $where_clause='')
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

/**
 * Select spesific row on DB
 *
 *
 * @param string $databaseConnection use current or another DB connection.
 * @param string $table_name table to insert values to.
 * @param string $form_data array of [fieldname] => Value inserted
 * @param string $where_clause is by default empty and can be spesified
 * @return object
 */
function rowSelectPerson($databaseConnection,$table_name, $where_clause='')
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
    $sql = "SELECT CONCAT(per_fname ,' ',per_sname) as per_name , per_email , per_cellnr , address.add_street1 , per_id FROM person LEFT JOIN address on ( address.add_ref_person = per_id ) ";

    // append the where statement
    $sql .= $whereSQL;

    // run and return the query result
    //echo $sql;
    return mysqli_query($databaseConnection,$sql);
}

/**
 * Select all rows from addressAlarms on DB
 *
 *
 * @param string $databaseConnection use current or another DB connection.
 * @return Object of all rows
 */

function rowSelectall($databaseConnection,$table)
{

    // start the actual SQL statement
    $sql = "SELECT * FROM `$table` ";

    // run and return the query result
    return mysqli_query($databaseConnection,$sql);

}

/**
 * Populate JSON structure for return
 *
 *
 * @param string $databaseConnection use current or another DB connection.
 * @return string in json format
 */

function jsonfyPerson($dbObject)
{
    $returnJson ="[";
    foreach($dbObject as $row){
        $JsonEncode = '{
            "id":"'.$row["sla_uuid"].'",
            "name":"'.$row["sla_name"].'",
            "alarm_at":"'.$row["sla_alarm"].'"
        }';
        $returnJson= $returnJson.$JsonEncode.",";
    }
    $returnJson = substr($returnJson,0,-1);
    return $returnJson."]";
}

?>
