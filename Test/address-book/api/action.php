<?php
/*

Developer : Jaco Brits
Email : jaco@netstart.co.za

Description:
Address-book action file.
The token CSRF security on the ajax calls was removed

All the php action and routes are filtered and invoked here.
With the help of the config file basic function is triggered here.

Please note:  South Africa time line is UTC+2 and system check for UTC

Method:
    http://adress_book.medmin.co.za/api/action.php/add_contact
    Post : {
            id:random UUID created by user,
            name: {your alarm name},
            alert_at:{timestamp};
        }
    return: 200 Code
    This will add new row with name and time in UTC to database, were it wait for trigger action, and sent message to adress_book

Method:
    http://adress_book.medmin.co.za/api/action.php/list
    Get : {}
    return: currenttime in UTC
    It simulates a cron and will check every 30 seconds if there is an alarm set and the message will be pushed to adress_book

Method:
    http://adress_book.medmin.co.za/api/action.php/alarm
    Get : {}
    return: list of all alarms in database, JSON format:
            [{
                id:random UUID created by user,
                name: {your alarm name},
                alert_at:{timestamp as saved};
            }]
    By envoking only alarm you will recieve a json collection with all set alarms to the return format

Method:
    http://adress_book.medmin.co.za/api/action.php/alarm/{uuid}
    Get : {}
    return: only alarm for spesific UUID, JSON format:
            [{
                id:random UUID created by user,
                name: {your alarm name},
                alert_at:{timestamp as saved};
            }]
    Adding a UUID of a saved Alarm will return that spesific alarm in JSON format

*/
//include basic setup and CRUD functionality
require_once 'config.php';
//get everything afer action.php
$action = $_SERVER["PATH_INFO"];

switch ($action) {
    case "/list": // /alarm it self has 2 functions and is seperated by n POST that gets priority
        if(isset($_POST['id'])){  // check for a post and insert new alarm in db
          echo  '{
              "data": [
                [
                  "Tiger Nixon",
                  "System Architect",
                  "Edinburgh",
                  "5421",
                  "2011/04/25",
                  "$320,800"
                ],
                [
                  "Garrett Winters",
                  "Accountant",
                  "Tokyo",
                  "8422",
                  "2011/07/25",
                  "$170,750"
                ],
                [
                  "Ashton Cox",
                  "Junior Technical Author",
                  "San Francisco",
                  "1562",
                  "2009/01/12",
                  "$86,000"
                ],
              ]
            }';
        }else{ // if only /alarm is selected it return a row into db
            //get all rows from DB and return it as JSON object
            $rows = jsonfyAlarms(rowSelectAll($databaseConnection));
            //echo json back in plain/text
            echo $rows;
            break;

        }
  case "/edit_contact": // /alarm it self has 2 functions and is seperated by n POST that gets priority
    if(isset($_POST['uuid'])){  // check for a post and insert new alarm in db
                //$results = sentToadress_book ($token,'setting alarm','#general'); // extra to alert on adress_book
                $dataArray = array('sla_uuid' => $_POST['uuid'],
                                   'sla_name' => $_POST['name'],
                                   'sla_alarm' => date("Y-m-d\TH:i:00\Z" ,strtotime($_POST['alarm_at'])));

                rowInsert($databaseConnection,'adress_book',$dataArray);

                break;
            }else{ // if only /alarm is selected it return a row into db
                //get all rows from DB and return it as JSON object
                $rows = jsonfyAlarms(rowSelectAll($databaseConnection));
                //echo json back in plain/text
                echo $rows;
                break;

    }

    case "/add_contact": // open web page will check every miniute #todo create cron
        // do a check for the current time on PC or server UTC standard
        $result = rowSelect($databaseConnection,'adress_bookAlarms',"sla_alarm = '".date("Y-m-d\TH:i:00\Z")."'");

        if($result){ // it checks the sql return if true and a alarm was triggered with the UTC time
            foreach($result as $row){ // sql is returned in a array
                if($row['sla_active'] == '1'){ // check if the alarm is still active
                    sentToadress_book ($token,$row['sla_name'],$room);//the message is still active, it will sent the name of message with the Token and room to adress_book API
                    rowActiveUpdate($row['sla_uuid']);// alarm is set inactive
                    echo '{"result": "Success, adress_book massage sent"}'; // respons is echo
                }
                echo '{"result": "Success, adress_book massage already"}';
            }
        }else{
            echo '{"result": "Current UTC TIME:"'.date("Y-m-d\TH:i:00\Z").'}'; // nothing to do return TIME UTC
            break;
        }
        echo '{"result": "Current UTC TIME:"'.date("Y-m-d\TH:i:00\Z").'}'; // nothing to do return TIME UTC
        break;

    default: // by default, is alarm/{uuid} and wil return value from saved alarm with the uuid

        $uuid = str_ireplace('/alarm/',"",$action); //cuts out the un needed parts from the string
        // the uuid is left and compared to in a select fuction if found it will return a json object else an empty json
        echo jsonfyAlarms(rowSelect($databaseConnection,'adress_book',"sla_uuid = '".$uuid."'"));
        break;
}

?>
