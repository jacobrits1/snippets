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
    <title>FruitFlo App</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <!-- Bootstrap core CSS -->
    <link href="assets/css/bootstrap.min.css" rel="stylesheet">
    <!-- Material Design Bootstrap -->
    <link href="assets/css/mdb.min.css" rel="stylesheet">
    <!-- Mobilee custom styles -->
    <link href="assets/css/style.css" rel="stylesheet">

</head>

<body class="bodystyle">
 
    <!-- Fruitflo Main-->
    <div id="mainContent">
      <!-- <div style="height: 100vh"> -->

          <div class="flex-center flex-column">
              <img src="assets/img/ffLogo.png" class="logoTop" alt="1">

              <br>
              <div class="topWarning"><p id="loginError"></p></div>
                 
              <div class="col-auto">
                <label class="sr-only" for="inlineFormInputGroup">Username</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <i class="fa fa-user input-group-text"></i>
                    </div>
                    <input type="text" class="form-control py-0" id="indexUsername"  placeholder="Username">
                  </div>
              </div>

              <div class="col-auto">
                <label class="sr-only" for="inlineFormInputGroup">Password</label>
                  <div class="input-group mb-2">
                    <div class="input-group-prepend">
                      <i class="fa fa-lock input-group-text"></i>
                    </div>
                    <input type="password" class="form-control py-0" id="indexPassword"  placeholder="Password">
                  </div>
              </div>

              <button action="login" eltarget = "mainContent" url="views/dashboard/dashboardWelcome.html" type="button" id="mainLogin" class="btn button btn-primary isrelative rounded">Log In</button>

              <button eltarget = "mainContent" url="views/register/register.html" type="button" id="mainRegister" class="btn button btn-primary isrelative rounded">Register</button>

              <div class="form-group">
                <a eltarget = "mainContent" url="views/login/forgotPassword.html" class="button"> Forgot Password? </a>
              </div>


          </div>
      <!-- </div> -->
    </div>
    <!-- Fruitflo Main-->

    <!-- SCRIPTS -->
    <!-- JQuery -->
    <script type="text/javascript" src="assets/js/jquery-3.2.1.min.js"></script>
    <!-- Bootstrap tooltips -->
    <script type="text/javascript" src="assets/js/popper.min.js"></script>
    <!-- Bootstrap core JavaScript -->
    <script type="text/javascript" src="assets/js/bootstrap.min.js"></script>
    <!-- MDB core JavaScript -->
    <script type="text/javascript" src="assets/js/mdb.min.js"></script>
    <!-- Custom dropdown Script -->
    <script type="text/javascript" src="assets/js/pdfobject.js"></script>
    <!-- FruitFlo core JavaScript -->
    <script type="text/javascript" src="assets/js/core.js"></script>

</body>

</html>
