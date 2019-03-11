<?php
header("Access-Control-Allow-Origin: *");
session_start();
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(rand(32,32));
}
?>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="csrf-token" content="<?= $_SESSION['csrf_token'] ?>">
    <title>Slack Reminder App</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="assets/css/mdb.min.css" rel="stylesheet">
    <!-- Clockpicker Styles -->
    <link href="assets/css/clockpicker.css" rel="stylesheet">
    <link href="assets/css/standalone.css" rel="stylesheet">
    <!-- Custom Styles -->
    <link href="assets/css/style.css" rel="stylesheet">

</head>

<body class="bodystyle">
 
    <!--  Main-->
    <div id="mainContent">
      <!-- <div style="height: 100vh"> -->
        <h1 allign="center">Slack Alarm Set</h1>
        <h4 id="currentTime"> </h4>
        <div style="height: 100vh">
        <h2 allign="center">Set Alarm</h2>
        <form>
            <div class="row">
                <div class="col-sm">
                    <div class="form-group">
                        <div class='input-group date' id='datetimepicker'>
                            <input type='text' class="form-control" id="setTime" placeholder="Datetime UTC : 2018-05-25 12:00:00"/>
                            <span class="input-group-addon">
                                <span class="glyphicon glyphicon-calendar"></span>
                            </span>
                        </div>
                    </div>
                </div> 
                <div class="col-sm">   
                    <div class="form-group">
                        <input type="text" class="form-control" id="setName" placeholder="Message for your alarm">
                    </div>
                </div>
                <div class="col-sm">   
                    <button type="button" id= "submitForm" class="btn btn-raised btn-primary">Submit</button>
                </div>    
            </div>  
        </form>
        <div style="height: 100vh">

        <h2>List Alarms</h2>
        <div style="height: 50vh">
        <div id="listReminders">
            <table class="table" id="alarmlist">
                <thead>
                    <tr>
                        <th scope="col">UUID</th>
                        <th scope="col">Message</th>
                        <th scope="col">Time</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                    </tr>
                </tbody>
            </table>
        </div>    

      <!-- </div> -->
    </div>
    <!--  Main-->

    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="assets/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="assets/js/mdb.min.js"></script>
    <!-- Timepicker Code  --> 
    <script type="text/javascript" src="assets/js/clockpicker.js"></script>
    <!-- Core JavaScript -->
    <script type="text/javascript" src="assets/js/core.js"></script>

</body>

</html>
