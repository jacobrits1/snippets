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
    http://addressbook.medmin.co.za/api/action.php/add_contact
    Post : {
            id:random UUID created by user,
            name: {your alarm name},
            alert_at:{timestamp};
        }
    return: 200 Code
    This will add new row with name and time in UTC to database, were it wait for trigger action, and sent message to addressbook

Method:
    http://addressbook.medmin.co.za/api/action.php/list
    Get : {}
    return: currenttime in UTC
    It simulates a cron and will check every 30 seconds if there is an alarm set and the message will be pushed to addressbook

Method:
    http://addressbook.medmin.co.za/api/action.php/alarm
    Get : {}
    return: list of all alarms in database, JSON format:
            [{
                id:random UUID created by user,
                name: {your alarm name},
                alert_at:{timestamp as saved};
            }]
    By envoking only alarm you will recieve a json collection with all set alarms to the return format

Method:
    http://addressbook.medmin.co.za/api/action.php/alarm/{uuid}
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

$demoData = '{
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
    [
      "Cedric Kelly",
      "Senior Javascript Developer",
      "Edinburgh",
      "6224",
      "2012/03/29",
      "$433,060"
    ],
    [
      "Airi Satou",
      "Accountant",
      "Tokyo",
      "5407",
      "2008/11/28",
      "$162,700"
    ],
    [
      "Brielle Williamson",
      "Integration Specialist",
      "New York",
      "4804",
      "2012/12/02",
      "$372,000"
    ],
    [
      "Herrod Chandler",
      "Sales Assistant",
      "San Francisco",
      "9608",
      "2012/08/06",
      "$137,500"
    ],
    [
      "Rhona Davidson",
      "Integration Specialist",
      "Tokyo",
      "6200",
      "2010/10/14",
      "$327,900"
    ],
    [
      "Colleen Hurst",
      "Javascript Developer",
      "San Francisco",
      "2360",
      "2009/09/15",
      "$205,500"
    ],
    [
      "Sonya Frost",
      "Software Engineer",
      "Edinburgh",
      "1667",
      "2008/12/13",
      "$103,600"
    ],
    [
      "Jena Gaines",
      "Office Manager",
      "London",
      "3814",
      "2008/12/19",
      "$90,560"
    ],
    [
      "Quinn Flynn",
      "Support Lead",
      "Edinburgh",
      "9497",
      "2013/03/03",
      "$342,000"
    ],
    [
      "Charde Marshall",
      "Regional Director",
      "San Francisco",
      "6741",
      "2008/10/16",
      "$470,600"
    ],
    [
      "Haley Kennedy",
      "Senior Marketing Designer",
      "London",
      "3597",
      "2012/12/18",
      "$313,500"
    ],
    [
      "Tatyana Fitzpatrick",
      "Regional Director",
      "London",
      "1965",
      "2010/03/17",
      "$385,750"
    ],
    [
      "Michael Silva",
      "Marketing Designer",
      "London",
      "1581",
      "2012/11/27",
      "$198,500"
    ],
    [
      "Paul Byrd",
      "Chief Financial Officer (CFO)",
      "New York",
      "3059",
      "2010/06/09",
      "$725,000"
    ],
    [
      "Gloria Little",
      "Systems Administrator",
      "New York",
      "1721",
      "2009/04/10",
      "$237,500"
    ],
    [
      "Bradley Greer",
      "Software Engineer",
      "London",
      "2558",
      "2012/10/13",
      "$132,000"
    ],
    [
      "Dai Rios",
      "Personnel Lead",
      "Edinburgh",
      "2290",
      "2012/09/26",
      "$217,500"
    ],
    [
      "Jenette Caldwell",
      "Development Lead",
      "New York",
      "1937",
      "2011/09/03",
      "$345,000"
    ],
    [
      "Yuri Berry",
      "Chief Marketing Officer (CMO)",
      "New York",
      "6154",
      "2009/06/25",
      "$675,000"
    ],
    [
      "Caesar Vance",
      "Pre-Sales Support",
      "New York",
      "8330",
      "2011/12/12",
      "$106,450"
    ],
    [
      "Doris Wilder",
      "Sales Assistant",
      "Sidney",
      "3023",
      "2010/09/20",
      "$85,600"
    ],
    [
      "Angelica Ramos",
      "Chief Executive Officer (CEO)",
      "London",
      "5797",
      "2009/10/09",
      "$1,200,000"
    ],
    [
      "Gavin Joyce",
      "Developer",
      "Edinburgh",
      "8822",
      "2010/12/22",
      "$92,575"
    ],
    [
      "Jennifer Chang",
      "Regional Director",
      "Singapore",
      "9239",
      "2010/11/14",
      "$357,650"
    ],
    [
      "Brenden Wagner",
      "Software Engineer",
      "San Francisco",
      "1314",
      "2011/06/07",
      "$206,850"
    ],
    [
      "Fiona Green",
      "Chief Operating Officer (COO)",
      "San Francisco",
      "2947",
      "2010/03/11",
      "$850,000"
    ],
    [
      "Shou Itou",
      "Regional Marketing",
      "Tokyo",
      "8899",
      "2011/08/14",
      "$163,000"
    ],
    [
      "Michelle House",
      "Integration Specialist",
      "Sidney",
      "2769",
      "2011/06/02",
      "$95,400"
    ],
    [
      "Suki Burks",
      "Developer",
      "London",
      "6832",
      "2009/10/22",
      "$114,500"
    ],
    [
      "Prescott Bartlett",
      "Technical Author",
      "London",
      "3606",
      "2011/05/07",
      "$145,000"
    ],
    [
      "Gavin Cortez",
      "Team Leader",
      "San Francisco",
      "2860",
      "2008/10/26",
      "$235,500"
    ],
    [
      "Martena Mccray",
      "Post-Sales support",
      "Edinburgh",
      "8240",
      "2011/03/09",
      "$324,050"
    ],
    [
      "Unity Butler",
      "Marketing Designer",
      "San Francisco",
      "5384",
      "2009/12/09",
      "$85,675"
    ],
    [
      "Howard Hatfield",
      "Office Manager",
      "San Francisco",
      "7031",
      "2008/12/16",
      "$164,500"
    ],
    [
      "Hope Fuentes",
      "Secretary",
      "San Francisco",
      "6318",
      "2010/02/12",
      "$109,850"
    ],
    [
      "Vivian Harrell",
      "Financial Controller",
      "San Francisco",
      "9422",
      "2009/02/14",
      "$452,500"
    ],
    [
      "Timothy Mooney",
      "Office Manager",
      "London",
      "7580",
      "2008/12/11",
      "$136,200"
    ],
    [
      "Jackson Bradshaw",
      "Director",
      "New York",
      "1042",
      "2008/09/26",
      "$645,750"
    ],
    [
      "Olivia Liang",
      "Support Engineer",
      "Singapore",
      "2120",
      "2011/02/03",
      "$234,500"
    ],
    [
      "Bruno Nash",
      "Software Engineer",
      "London",
      "6222",
      "2011/05/03",
      "$163,500"
    ],
    [
      "Sakura Yamamoto",
      "Support Engineer",
      "Tokyo",
      "9383",
      "2009/08/19",
      "$139,575"
    ],
    [
      "Thor Walton",
      "Developer",
      "New York",
      "8327",
      "2013/08/11",
      "$98,540"
    ],
    [
      "Finn Camacho",
      "Support Engineer",
      "San Francisco",
      "2927",
      "2009/07/07",
      "$87,500"
    ],
    [
      "Serge Baldwin",
      "Data Coordinator",
      "Singapore",
      "8352",
      "2012/04/09",
      "$138,575"
    ],
    [
      "Zenaida Frank",
      "Software Engineer",
      "New York",
      "7439",
      "2010/01/04",
      "$125,250"
    ],
    [
      "Zorita Serrano",
      "Software Engineer",
      "San Francisco",
      "4389",
      "2012/06/01",
      "$115,000"
    ],
    [
      "Jennifer Acosta",
      "Junior Javascript Developer",
      "Edinburgh",
      "3431",
      "2013/02/01",
      "$75,650"
    ],
    [
      "Cara Stevens",
      "Sales Assistant",
      "New York",
      "3990",
      "2011/12/06",
      "$145,600"
    ],
    [
      "Hermione Butler",
      "Regional Director",
      "London",
      "1016",
      "2011/03/21",
      "$356,250"
    ],
    [
      "Lael Greer",
      "Systems Administrator",
      "London",
      "6733",
      "2009/02/27",
      "$103,500"
    ],
    [
      "Jonas Alexander",
      "Developer",
      "San Francisco",
      "8196",
      "2010/07/14",
      "$86,500"
    ],
    [
      "Shad Decker",
      "Regional Director",
      "Edinburgh",
      "6373",
      "2008/11/13",
      "$183,000"
    ],
    [
      "Michael Bruce",
      "Javascript Developer",
      "Singapore",
      "5384",
      "2011/06/27",
      "$183,000"
    ],
    [
      "Donna Snider",
      "Customer Support",
      "New York",
      "4226",
      "2011/01/25",
      "$112,000"
    ]
  ]
}';

switch ($action) {
    case "/list": // /alarm it self has 2 functions and is seperated by n POST that gets priority
        if(isset($_POST['id'])){  // check for a post and insert new alarm in db
          $result = rowSelect($databaseConnection,'person',"per_ref_user = '".$_POST['uuid']."'");

          echo json_encode($demoData);
        }else{ // if only /alarm is selected it return a row into db
          echo '{}';
          break;

        }
  case "/edit_contact": // /alarm it self has 2 functions and is seperated by n POST that gets priority
    if(isset($_POST['uuid'])){  // check for a post and insert new alarm in db
                //$results = sentToaddressbook ($token,'setting alarm','#general'); // extra to alert on addressbook
                $result = rowSelect($databaseConnection,'person',"per_ref_user = '".$_POST['uuid']."'");

                if($result){ // it checks the sql return if true and a alarm was triggered with the UTC time
                    foreach($result as $row){ // sql is returned in a array
                        if($row['sla_active'] == '1'){ // check if the alarm is still active
                            sentToaddressbook ($token,$row['sla_name'],$room);//the message is still active, it will sent the name of message with the Token and room to addressbook API
                            rowActiveUpdate($row['sla_uuid']);// alarm is set inactive
                            echo '{"result": "Success, addressbook massage sent"}'; // respons is echo
                        }
                        echo '{"result": "Success, addressbook massage already"}';
                    }

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
        $result = rowSelect($databaseConnection,'addressbookAlarms',"sla_alarm = '".date("Y-m-d\TH:i:00\Z")."'");

        if($result){ // it checks the sql return if true and a alarm was triggered with the UTC time
            foreach($result as $row){ // sql is returned in a array
                if($row['sla_active'] == '1'){ // check if the alarm is still active
                    sentToaddressbook ($token,$row['sla_name'],$room);//the message is still active, it will sent the name of message with the Token and room to addressbook API
                    rowActiveUpdate($row['sla_uuid']);// alarm is set inactive
                    echo '{"result": "Success, addressbook massage sent"}'; // respons is echo
                }
                echo '{"result": "Success, addressbook massage already"}';
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
        echo jsonfyAlarms(rowSelect($databaseConnection,'addressbook',"sla_uuid = '".$uuid."'"));
        break;
}

?>
