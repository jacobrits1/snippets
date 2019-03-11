<?php
/*

Developer : Jaco Brits
Email : jaco@netstart.co.za

Description:
Slack 3rd Party config file.
The token CSRF security on the ajax calls was removed

Also a remote mySQL DB connection to save alarm details


Functions:

sentToSlack($token,$message,$channel)
setAReminderSlack($token,$message,$channel,$time)
insertAlarmToDB($uuid,$alarm,$message)
rowActiveUpdate($dbconnection,$uuid)
rowSelectall($dbconnection)
jsonfyAlarms($dbObject)
*/

session_start();

$token = "xoxb-435850601634-436453140308-VnJ483I22cSfRzyxQyvG0ArV";
$room= "#general";

/**
 * Conection details to a Remote Hosted DB
 * Table: slackAlarm
 * id , uuid , message , alarm , active , time_created
 * 
 * 
 */
  $servername = "medmin.co.za";
  $username = "medminco_demo";
  $password = "D3m0!234";
  $db_name = "medminco_task";
  $invalid_characters = array("$", "%", "#", "<", ">", "|");


  $databaseConnection = new mysqli($servername, $username, $password, $db_name);
  if ($databaseConnection->connect_error) {
  	die("1 - Connection failed: " . $databaseConnection->connect_error);
    $connected_button = "danger";
    $connected_message = "<strong>Failed:</strong> Could not connect to Fancam Database.";
  }

/**
 * Send a Message to a Slack Channel.
 *
 * 
 * @param string $token already setup ontop of file.
 * @param string $message The message to post into a channel.
 * @param string $channel The name of the channel prefixed with #, example #foobar
 * @return value of slack connection
 */
function sentToSlack($token,$message,$channel)
{
    $ch = curl_init("https://slack.com/api/chat.postMessage");
    $data = http_build_query([
        "token" => $token,
    	"channel" => $channel,
    	"text" => $message,
    	"username" => "jamesbots",
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
}

/**
 * Set a reminder to Slack.
 *
 * 
 * @param string $token already setup ontop of file.
 * @param string $message The message to post into a channel.
 * @param string $channel The name of the channel prefixed with #, example #foobar
 * @param string $time in UNIX format
 * @return value of slack connection
 */
function setAReminderSlack($token,$message,$channel,$time)
{
    $ch = curl_init("https://slack.com/api/reminders.add");
    $data = http_build_query([
        "token" => $token,
    	"channel" => $channel,
    	"text" => $message,
    	"username" => "jamesbots",
    ]);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $result = curl_exec($ch);
    curl_close($ch);
    
    return $result;
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

function rowActiveUpdate($databaseConnection,$uuid)
{
    // build the update query to set active to 0
    $sql = "UPDATE slackAlarm SET sla_active = 0 WHERE sla_uuid='".$uuid."'";
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
 * Select all rows from slackAlarms on DB
 *
 * 
 * @param string $databaseConnection use current or another DB connection.
 * @return Object of all rows
 */

function rowSelectall($databaseConnection)
{
    
    // start the actual SQL statement
    $sql = "SELECT * FROM `slackAlarms` ";

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

function jsonfyAlarms($dbObject)
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
