/*
Mobilee Apps
Core control of view pages and load mainContent div


*/
//global veriables
var happy = 0;
var numOfPuc = 0;
var currentPucCount = 0;
var csrfToken = '<?php echo $csrf_token?>';
var currentUserName = '';
var currentUserSurname = '';
var currentUserEmail = '';
var currentUserId = '';
var currentUserRole = '';
var currentPucNo = '';
var listPuc = {};
var donePuc = {};
var gpsLat = '38.8951';
var gpsLon = '-77.0367';
var regJson;
var weight_file;
var pressure_file;
var brix_file;
var temperature_file;
var internal_file;
var external_file;
var sentto;
var successStatus;
var grading_images_src = [
	'assets/img/blank64x64.png',
	'assets/img/green64x64.png',
	'assets/img/amber64x64.png',
	'assets/img/red64x64.png'
];
var checkInputs = 1;
var checkImage = 1;
var checkGrading = 1;

var contianerDetails = {
    "apikey": "cd8f23a8e07a223e8d89f3fbf42c3874",
    "number":"",
    "location":"",
    "gpsLat":"",
    "gpsLon":"",
    "send_office":"",
    "send_exporter":"",
    "send_Puc" :[],
    "user_id":"",
    "date_created":"",
    "puc_count":"",
    "puc": [],
    };

var emptyPucObject = {
			"number":"",
			"fruit_type":"" ,
			"fruit_variety":"" ,
			"weight":"",
			"weight_grading":"",
			"weight_image_temp_name":"",
			"weight_image_temp_details":"",
			"weight_image_temp_data":"",
			"weight_image_orientation":"",
			"pressure":"",
			"pressure_grading":"",
			"pressure_image_temp_name":"",
			"pressure_image_temp_data":"",
			"pressure_image_temp_details":"",
			"pressure_image_orientation":"",
			"brix":"",
			"brix_grading":"",
			"brix_image_temp_name":"",
			"brix_image_temp_data":"",
			"brix_image_temp_details":"",
			"brix_image_orientation":"",
			"temperature":"",
			"temperature_grading":"",
			"temperature_image_temp_name":"",
			"temperature_image_temp_data":"",
			"temperature_image_temp_details":"",
			"temperature_image_orientation":"",
			"internal_image_temp_name":"",
			"internal_image_temp_data":"",
			"internal_image_temp_details":"",
			"internal_image_orientation":"",
			"external_image_temp_name":"",
			"external_image_temp_data":"",
			"external_image_temp_details":"",
			"external_image_orientation":"",
			"custom":"",
			"custom_metric":"",
			"additional_comments":"",
			"overall_grading":"",
	    "custom_grading":"",
		};

//global controls setups
$.ajaxSetup({
  headers : {
      'CsrfToken': $('meta[name="csrf-token"]').attr('content')
    }
});



/*grobal define use functions
*/
/*
Load all user details and populate correct info to the append
*/
function removeProp(obj, propToDelete) {
  for (var property in obj) {
    if (obj.hasOwnProperty(property)) {
      if (typeof obj[property] == "object") {
        removeProp(obj[property], propToDelete);
      } else {
        if (property === propToDelete && obj[property] === true) {
          delete obj[property];
        }
      }
    }
  }
}

function compressImg(source_img_obj, quality, maxWidth, output_format){
    var mime_type = "image/jpeg";
    if(typeof output_format !== "undefined" && output_format=="png"){
        mime_type = "image/png";
    }

    maxWidth = maxWidth || 1000;
    var natW = source_img_obj.naturalWidth;
    var natH = source_img_obj.naturalHeight;
    var ratio = natH / natW;
    if (natW > maxWidth) {
        natW = maxWidth;
        natH = ratio * maxWidth;
    }

    var cvs = document.createElement('canvas');
    cvs.width = natW;
    cvs.height = natH;

    var ctx = cvs.getContext("2d").drawImage(source_img_obj, 0, 0, natW, natH);
    var newImageData = cvs.toDataURL(mime_type, quality/100);
    var result_image_obj = new Image();
    result_image_obj.src = newImageData;
    return result_image_obj;
}


function clearCache (){
	weight_file = "";
	pressure_file = "";
	brix_file = "";
	temperature_file = "";
	internal_file = "";
	external_file = "";
	//var myObj = {}; // Empty Object
	var arrayLength = 4;
	for (var i = 0; i < arrayLength; i++) {
		if (typeof contianerDetails.puc[i] !== "undefined") {
		    contianerDetails.puc.shift();
		} else {
		    return;
		}
	}
}

function resetWarnings(){
		//$('#forgotPasswordMsg').fadeOut();
}

function displayMessage(message,id){
	  $('#'+id).show();
	  document.getElementById(id).innerHTML = message;

	  setTimeout(function(){
        $('#'+id).fadeOut();
		}, 2000);

}

function setCookie(cname, cvalue, exdays) {
  var d = new Date();
  d.setTime(d.getTime() + (exdays*24*60*60*1000));
  var expires = "expires="+ d.toUTCString();
  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
}

function getCookie(cname) {
  var name = cname + "=";
  var decodedCookie = decodeURIComponent(document.cookie);
  var ca = decodedCookie.split(';');
  for(var i = 0; i <ca.length; i++) {
      var c = ca[i];
      while (c.charAt(0) == ' ') {
          c = c.substring(1);
      }
      if (c.indexOf(name) == 0) {
          return c.substring(name.length, c.length);
      }
  }
  return "";
}

function checkLoggedinCookie(){
   var name = getCookie("ffUserName");

   if (name == "" || name === 'undefined') {
      console.log("no one logged in");
   }else{
     currentUserName = getCookie("ffUserName");
     currentUserSurname = getCookie("ffUsersurname");
     currentUserId = getCookie("ffid");
     currentUserRole = getCookie("ffrole");
     // load welcome page
     $("#mainContent").load("views/dashboard/dashboardWelcome.html");
   }
}
/*geo location functions***/
function getLocationConstant()
{

  if(navigator.geolocation)
  {
   navigator.geolocation.getCurrentPosition(onGeoSuccess,onGeoError);
  } else {
   console.log("No GPS support");
  }
}

function onGeoSuccess(event)
{
	gpsLat = event.coords.latitude;
  gpsLon = event.coords.longitude;
  var url = 'https://nominatim.openstreetmap.org/reverse?format=jsonv2&zoom=16&lat=' + gpsLat+ '&lon=' + gpsLon+ '&addressdetails=1&amenity=public_building';
  $.ajax({
    type: "GET",
    header:{'Access-Control-Allow-Origin': '*'},
    url: url,
    dataType: 'json',
    crossDomain:true,
    //data : locData,
    cache: false,
    success: function(response){
      console.log(response);

                  console.log(response.address.town + ', ' +response.address.suburb);
                  // var placeName = response.address.town + ', ' +response.address.suburb;
                  // $('#stepContainerLocation').append($('<option>', { placeName : placeName }).text(placeName));
         initMap();
    }
  });
	console.log("Success: "+event.coords.latitude+", "+event.coords.longitude);

}

function onGeoError(event)
{
  console.log("Error code " + event.code + ". " + event.message);
}

function initMap() {
  var locCordinates = {lat: parseFloat(gpsLat), lng: parseFloat(gpsLon)};
  map = new google.maps.Map(document.getElementById('stepContainerMap'), {
    center: locCordinates,
    zoom: 14
  });
  infowindow = new google.maps.InfoWindow();
  var service = new google.maps.places.PlacesService(map);
  service.nearbySearch({

    location: locCordinates,
    radius: 100,
    type: ['city','point_of_interest']
  }, callback);
}

function callback(results, status) {
  if (status === google.maps.places.PlacesServiceStatus.OK) {
    $('#stepContainerLoader').hide();
    $('.stepContainerSelect').show();
    for (var i = 0; i < results.length; i++) {
      //createMarker(results[i]);
      //console.log(results[i].name);
      var placeName = results[i].name;

      $('#stepContainerLocation').append($('<option>', { value : placeName }).text(placeName));

    }
  }
}



function logOut(){
   setCookie("ffUserName","",0);
   setCookie("ffSurname","",0);
   setCookie("ffid","",0);
   location.reload();
}

function emptyContainer(){
  contianerDetails = {
      "number":"",
      "location":"",
      "date_created":"",
      "puc_count":"",
      "puc": {
              "number":"",
              "fruit_type":"" ,
              "fruit_variety":"" ,
              "weight":"",
              "weight_grading":"",
              "weight_image_temp_name":"",
              "weight_image_orientation":"",
              "pressure":"",
              "pressure_grading":"",
              "pressure_image_temp_name":"",
              "pressure_image_orientation":"",
              "brix":"",
              "brix_grading":"",
              "brix_image_temp_name":"",
              "brix_image_orientation":"",
              "temperature":"",
              "temperature_grading":"",
              "temperature_image_temp_name":"",
              "temperature_image_orientation":"",
              "internal_image_temp_name":"",
              "internal_image_orientation":"",
              "external_image_temp_name":"",
              "external_image_orientation":"",
              "custom":"",
              "custom_metric":"",
              "additional_comments":"",
              "overall_grading":"",
			       "custom_grading":"",
      }
   };
 }


function addPuc (key){
  listPuc[key]= $(this).val();

}

function loadPage(container,page){
  $('#'+container).load(page);
  $('html,body').scrollTop(0);
}

function setImageCSS(imageID,placeHolderId,imageRotateID,reload)
{

//console.log($('#'+imageRotateID).val());

	if ($('#'+imageID).height() < $('#'+imageID).width())
	{
		//var imgBound =  document.getElementById(inputID).getBoundingClientRect();
		var offset = ($('#'+imageID).width()-$('#'+imageID).height())/2;
		 document.getElementById(imageID).style.top = offset+'px';
		 document.getElementById(placeHolderId).style.height = $('#'+imageID).width();
	 }

	else if ((($('#'+imageRotateID).val() != '90') && ($('#'+imageRotateID).val() != '270') || reload) && ($('#'+imageID).height() > $('#'+imageID).width()))
	{
	  var newWidth = ($('#'+imageID).width()/$('#'+imageID).height())*$('#'+imageID).width();
	  var newHeight = ($('#'+imageID).width()/$('#'+imageID).height())*$('#'+imageID).height();

	  console.log('new width:'+newWidth);
	  console.log('new height:'+newHeight);

	var offset = (newWidth-newHeight)/2;
	document.getElementById(imageID).style.top = offset+'px';
    document.getElementById(imageID).style.width = newWidth;
	document.getElementById(imageID).style.height = newHeight;
	document.getElementById(imageID).style.maxHeight = '2000px';
	document.getElementById(imageID).style.maxWidth = '2000px';
	document.getElementById(placeHolderId).style.height = newWidth;
	}
}

function resetImageCSS(imageID,placeHolderId) {
   document.getElementById(imageID).style.height = 'auto';
   document.getElementById(imageID).style.width = 'auto';

   document.getElementById(imageID).style.maxHeight = '100%';
   document.getElementById(imageID).style.maxWidth = '100%';
   document.getElementById(imageID).style.top = '0px';
   document.getElementById(imageID).style.left = '0px';
   document.getElementById(placeHolderId).style.height = document.getElementById(imageID).style.height;

}

function checkInput(input,type,minlength){
	switch(type) {
		case 'string':
		if (input.length >= minlength)
		return true;
		else return false;
		break;

		case 'number':
		if (!isNaN(input) && input.length >= minlength)
		return true;
		else return false;
		break;

        default:
            return false;
	}
}

function checkQuality(stepName,checkInputs,checkImage,checkGrading,gradingValue)
{
			var happy = 0;

			if (checkInputs)
			{
				if (!checkInput(contianerDetails.puc[currentPucCount][stepName],'number',1))
					displayMessage('Please enter a '+stepName+' value','qcMsg');
				else
					happy = 1;
			}

			 if (checkImage && happy)
			 {
			 if (!(contianerDetails.puc[currentPucCount][stepName+'_image_temp_data']))
				    {displayMessage('Please select an image','qcMsg');
					 happy = 0;
				    }
				 else
					 happy = 1;
			 }

			 if (checkGrading && happy)
			 {
				 if (gradingValue == 0)
					 {displayMessage('Please select a grading','qcMsg');
					 happy = 0;
					 }
				 else
					 happy = 1;
			 }


            return happy;

}


$('body').on('change', '.selectPuc', function(){
  var arrPlace = parseInt($(this).attr('pucArr'));
	//assign empty object to detailed array
	var newEmpty = Object.assign({},emptyPucObject);
	contianerDetails.puc.push(newEmpty);
	contianerDetails.puc[arrPlace].number = $(this).val();
  listPuc[arrPlace]= $(this).val();

});

$( document ).ready(function() {

  //global functions

  $('body').on('click', '.button', function(){
    var eltarget = $(this).attr('eltarget');
    var loadUrl = $(this).attr('url');
    var action = $(this).attr('action');

    switch(action) {
	case 'updateProfile':

     currentUserName = getCookie("ffUserName");
     currentUserSurname = getCookie("ffUsersurname");
     currentUserId = getCookie("ffid");
     currentUserRole = getCookie("ffrole");

         var dataJson = { 'username': currentUserName,
                           'userid': currentUserId,
                           'apikey': 'cd8f23a8e07a223e8d89f3fbf42c3874'};

          $.ajax({
              type: "POST",
              url: "api/updateProfile.php",
              data: dataJson,
              dataType: "json",
              success: function(data){
                console.log(data);
                if(data.error == 'Error'){
                  displayMessage('There was an error. Please try again later'); //dsiplay error
                  return;
					}
					currentUserName = data.name;
					currentUserSurname = data.surname;

					currentUserId = data.id;
					currentUserRole = data.role;
					currentUserEmail = data.email;
					currentUserMobile = data.mobile;
					currentUserPosition = data.position;
					currentUserDob = data.dob;
					//console.log('Email'+currentUserEmail);
					loadPage(eltarget,loadUrl);
				  },
				  failure: function(errMsg) {

					//console.log(errMsg);
				  }
				});


	break;

	case 'profileDetails':

	var userName = $('#profileDetailsName').val();
	var userSurname = $('#profileDetailsSurname').val();
	var userMobile = $('#profileDetailsMobile').val();
	var userEmail = $('#profileDetailsEmail').val();

	if (userName == '')
		displayMessage('Please enter a valid name','profileMsg');
	else if (userSurname == '')
		displayMessage('Please enter a valid surname','profileMsg');
	else if ((userMobile == '') && (userEmail == ''))
		displayMessage('Please enter a valid mobile number or email','profileMsg');
	else {

	currentUserId = getCookie("ffid");

        var dataJson = {  'username': userName,
						  'usersurname': userSurname,
						  'usermobile': userMobile,
						  'useremail': userEmail,
                           'userid': currentUserId,
						   'action':'updateDetails',
                           'apikey': 'cd8f23a8e07a223e8d89f3fbf42c3874'};

          $.ajax({
              type: "POST",
              url: "api/updateProfile.php",
              data: dataJson,
              dataType: "json",
              success: function(data){
                console.log(data);
				if(data.error == 'duplicate'){
				  displayMessage(data.message,'profileMsg'); //dsiplay error
                  return;
				}
                else if(data.error == 'Error'){
                  displayMessage('There was an error. Please try again later','profileMsg'); //dsiplay error
                  return;
					}
					setCookie("ffUserName", data.name, 365);
					setCookie("ffSurname", data.surname, 365);

					loadPage(eltarget,loadUrl);
				  },
				  failure: function(errMsg) {

					//console.log(errMsg);
				  }
				});

    }

	break;

	case 'profileDob':

	var userDOB = $('#profileDobDate').val();
	var userRole = $('#profileDobPosition').val();

	if (userDOB == '')
		displayMessage('Please enter a valid date of birth','profileMsg');
	else if (userRole == '')
		displayMessage('Please enter a valid position/role','profileMsg');
	else {

	currentUserId = getCookie("ffid");

        var dataJson = {  'userdob': userDOB,
						  'userrole': userRole,
                           'userid': currentUserId,
						   'action':'updateDob',
                           'apikey': 'cd8f23a8e07a223e8d89f3fbf42c3874'};

          $.ajax({
              type: "POST",
              url: "api/updateProfile.php",
              data: dataJson,
              dataType: "json",
              success: function(data){
                console.log(data);
                if(data.error == 'Error'){
                  displayMessage('There was an error. Please try again later'); //dsiplay error
                  return;
					}
					setCookie("ffrole", data.role, 365);

					loadPage(eltarget,loadUrl);
				  },
				  failure: function(errMsg) {

					//console.log(errMsg);
				  }
				});

    }

	break;




      case 'login':   // login action getss username and password from index and check it against db
          var username = $('#indexUsername').val();
          var password = $('#indexPassword').val();

          if(username == "" || password == ""){
			    displayMessage('Incorrect Usename or Password','loginError'); //dsiplay error
          }

		  else
		  {

          var dataJson = { 'username': username,
                           'password': password,
                           'apikey': 'cd8f23a8e07a223e8d89f3fbf42c3874'};

          $.ajax({
              type: "POST",
              url: "api/login.php",
              data: dataJson,
              dataType: "json",
              success: function(data){
                console.log(data);
                if(data.error == 'Encorrect Usename or Password'){
                  displayMessage('Incorrect Usename or Password','loginError'); //dsiplay error
                  return;
					}
					//clearCache();
					currentUserName = data.name;
					currentUserSurname = data.surname;
					currentUserId = data.id;
					currentUserRole = data.role;
					currentUserEmail = data.email;
					setCookie("ffUserName", data.name, 365);
					setCookie("ffSurname", data.surname, 365);
					setCookie("ffid", data.id, 365);
					setCookie("ffrole", data.role, 365);
					loadPage(eltarget,loadUrl);
				  },
				  failure: function(errMsg) {

					//console.log(errMsg);
				  }
				 });
		    }

          break;
      case 'gotoStepContainer':
			    clearCache();
      	  loadPage(eltarget,loadUrl);
      	break;
      case 'registerPUC':
            globalRegCode = "0";
            registerPuc = $('#registerPuc').val();
            registerExporter = $('#registerExporter').val();
            registerImporter = $('#registerImporter').val();

			console.log('registerPuc'+registerExporter);

			if (!(registerPuc != '0' || registerExporter != '0' || registerImporter != '0'))
			    displayMessage('Please make a selection','registerErr');
			else{
            if(registerPuc != "0")
              globalRegCode = registerPuc;
            if(registerExporter != "0")
              globalRegCode = registerExporter;
            if(registerImporter != "0")
              globalRegCode = registerImporter;

            loadPage(eltarget,loadUrl);
			}
            break;
      case 'registerDetails':
            registerDetailsName = $('#registerDetailsName').val();
            registerDetailsSurname = $('#registerDetailsSurname').val();
            registerDetailsMobile = $('#registerDetailsMobile').val();
            registerDetailsEmail = $('#registerDetailsEmail').val();

			if (registerDetailsName == '')
				displayMessage('Please enter a valid name','registerErr');
			else if (registerDetailsSurname == '')
				displayMessage('Please enter a valid surname','registerErr');
			else if ((registerDetailsMobile == '') && (registerDetailsEmail == ''))
				displayMessage('Please enter a valid mobile number or email','registerErr');
			else {

            loadPage(eltarget,loadUrl);
			}
            break;

      case 'registerDob':
            registerDobDate = $('#registerDobDate').val();
            registerDobPosition = $('#registerDobPosition').val();

            loadPage(eltarget,loadUrl);
            break;

      case 'registerDetailsBack':

            loadPage(eltarget,loadUrl);
            break;

      case 'register':
            registerPasswordEnter = $('#registerPasswordEnter').val();
			registerPasswordRepeat = $('#registerPasswordRepeat').val();

			if (registerPasswordEnter == registerPasswordRepeat && registerPasswordRepeat != '')
			{

           var dataJson = {'registerPuc': registerPuc,
                           'registerExporter': registerExporter,
                           'registerImporter': registerImporter,
                           'registerDetailsName': registerDetailsName,
                           'registerDetailsSurname': registerDetailsSurname,
                           'registerDetailsMobile': registerDetailsMobile,
                           'registerDetailsEmail': registerDetailsEmail,
                           'registerDobDate': registerDobDate,
                           'registerDobPosition': registerDobPosition,
                           'registerPasswordEnter': registerPasswordEnter,
                           'apikey': 'cd8f23a8e07a223e8d89f3fbf42c3874'};

              $.ajax({
                  type: "POST",
                  url: "api/register.php",
                  data: dataJson,
                  dataType: "json",
                  success: function(data){
					console.log(data);

					if (data.error)
						//displayMessage(data.message,'registerPasswordMsg');
						loadPage('mainContent','views/register/registerError.html');
					else
					{
                    currentUserName = data.name;
                    currentUserSurname = data.surname;
                    currentUserId = data.id;
                    currentUserRole = data.role;
                    setCookie("ffUserName", data.name, 365);
                    setCookie("ffSurname", data.surname, 365);
                    setCookie("ffid", data.id, 365);
                    setCookie("ffrole", data.role, 365);
                    loadPage(eltarget,loadUrl);
					}
                  },
                  failure: function(errMsg) {
                    console.log(errMsg);
                  }
              });

			}
			else
			{
				displayMessage('The passwords did not match. Please try again','registerPasswordMsg');
			}

          break;


      case 'updatePassword':
            //forgotPasswordMobile = $('#forgotPasswordMobile').val();
            //forgotPasswordEmail = $('#forgotPasswordEmail').val();
			forgotPasswordResetTemp = $('#forgotPasswordResetTemp').val();
			forgotPasswordResetRepeat = $('#forgotPasswordResetRepeat').val();
			forgotPasswordResetNew = $('#forgotPasswordResetNew').val();

			if ((forgotPasswordMobile) || (forgotPasswordEmail))
			{
			if (forgotPasswordResetRepeat == forgotPasswordResetNew)
			{

            var dataJson = {'forgotPasswordMobile': forgotPasswordMobile,
                            'forgotPasswordEmail': forgotPasswordEmail,
							'forgotPasswordResetTemp':forgotPasswordResetTemp,
							'forgotPasswordResetNew':forgotPasswordResetNew,
                            'apikey': 'cd8f23a8e07a223e8d89f3fbf42c3874'};
            $.ajax({
                type: "POST",
                url: "api/updatePassword.php",
                data: dataJson,
                dataType: "json",
                success: function(data){
					currentUserName = data.name;
                    currentUserSurname = data.surname;
                    currentUserId = data.id;
                    currentUserRole = data.role;
					successStatus = data.successStatus;

					if (successStatus > 0)
						loadPage(eltarget,loadUrl); //Load next page
					    //console.log(data.failed);
					else
						displayMessage(data.failed,'forgotPasswordMsg'); //dsiplay error


                  //window.location.reload();
                },
                failure: function(errMsg) {
                  displayMessage('There was an error','forgotPasswordMsg');
                }
            });

			}
			else {
			displayMessage('The passwords did not match. Please try again','forgotPasswordMsg');
			}
			} else {
			displayMessage('The email or mobile number is not valid','forgotPasswordMsg');
			}
            break;

      case 'resetPassword':
            forgotPasswordMobile = $('#forgotPasswordMobile').val();
            forgotPasswordEmail = $('#forgotPasswordEmail').val();

			if ((forgotPasswordMobile) || (forgotPasswordEmail))
			{
				if ((forgotPasswordMobile) && (forgotPasswordEmail))
				{
					displayMessage('Please select only one option','forgotPasswordMsg');
					break;
				}

				// console.log('mobile'+forgotPasswordMobile);
				// console.log('email'+forgotPasswordEmail);

            var dataJson = {'forgotPasswordMobile': forgotPasswordMobile,
                            'forgotPasswordEmail': forgotPasswordEmail,
                            'apikey': 'cd8f23a8e07a223e8d89f3fbf42c3874'};
            $.ajax({
                type: "POST",
                url: "api/resetPassword.php",
                data: dataJson,
                dataType: "json",
                success: function(data){
					//console.log(data);
					currentUserName = data.name;
                    currentUserSurname = data.surname;
                    currentUserId = data.id;
                    currentUserRole = data.role;
					sentto = data.sentto;
					successStatus = data.successStatus;

					if (successStatus > 0)
						loadPage(eltarget,loadUrl); //Load next page
					    //console.log(data.failed);
					else
						displayMessage(data.failed,'forgotPasswordMsg'); //dsiplay error


                  //window.location.reload();
                },
                failure: function(errMsg) {
                  displayMessage('There was an error','forgotPasswordMsg');
                }
            });


			} else {
			displayMessage('Please enter a valid email or mobile number','forgotPasswordMsg');
			}
            break;

      case 'stepContainerDetails':
            d = new Date().toISOString().slice(0, 19).replace('T', ' ');
            //currentPucNo = $('#stepContainerPuc').val();
            contianerDetails.number = $('#stepContainerContainerNo').val();
            contianerDetails.location = $('#stepContainerLocation').val();
            contianerDetails.date_created = d;
            //contianerDetails.puc[currentPucCount].number = $('#stepContainerPuc').val();
            contianerDetails.gpsLat = gpsLat;
            contianerDetails.gpsLon = gpsLon;

			if (checkInputs)
			{
				if (!checkInput(contianerDetails.number,'string',2))
					displayMessage('Please enter a valid container number','qcMsg');
				else if (!checkInput($('#stepContainerPuc').val(),'string',2))
					displayMessage('Please select a PUC','qcMsg');
				else
					loadPage(eltarget,loadUrl);

			} else
            loadPage(eltarget,loadUrl);
            break;

				case 'stepSummaryPuc':
						var pucId = $(this).attr('pucId');
						var pucNo = $(this).attr('pucNo');
						currentPucCount = pucId;
						currentPucNo = pucNo;
						contianerDetails.puc[currentPucCount].number = pucNo;
						loadPage(eltarget,loadUrl);
						break;

      case 'dashboardBack':

            loadPage(eltarget,loadUrl);
            break;

      case 'stepTypeNext':

            contianerDetails.puc[currentPucCount].fruit_type = $('#stepTypeFruitType').val();
            contianerDetails.puc[currentPucCount].fruit_variety = $('#stepTypeVariety').val();

			if (checkInputs)
			{
				if (!checkInput(contianerDetails.puc[currentPucCount].fruit_type,'string',2))
					displayMessage('Please enter a valid fruit type','qcMsg');
				else if (!checkInput(contianerDetails.puc[currentPucCount].fruit_variety,'string',2))
					displayMessage('Please enter a valid fruit variety','qcMsg');
				else
					loadPage(eltarget,loadUrl);

			} else

            loadPage(eltarget,loadUrl);
            break;

      case 'stepWeightNext':
			      var gradingValue = 0;
            if ($('#stepWeightGradingGreen').is(':checked')) {
              gradingValue = 1;
            }
            if ($('#stepWeightGradingAmber').is(':checked')) {
              gradingValue = 2;
            }
            if ($('#stepWeightGradingRed').is(':checked')) {
              gradingValue = 3;
            }
            contianerDetails.puc[currentPucCount].weight = $('#stepWeightFruit').val();
            contianerDetails.puc[currentPucCount].weight_grading = gradingValue;
            contianerDetails.puc[currentPucCount].weight_image_temp_name = $('#weightImgupload').prop('files')[0];
			      contianerDetails.puc[currentPucCount].weight_image_name = $('#weightFileToUploadSrc').val();
            contianerDetails.puc[currentPucCount].weight_image_orientation = $('#weightImageRotationValue').val();

			if (checkQuality('weight',checkInputs,checkImage,checkGrading,gradingValue))
				loadPage(eltarget,loadUrl);
            break;
      case 'stepPressureNext':
            var gradingValue = 0;
            if ($('#stepPressureGradingGreen').is(':checked')) {
              gradingValue = 1;
            }
            if ($('#stepPressureGradingAmber').is(':checked')) {
              gradingValue = 2;
            }
            if ($('#stepPressureGradingRed').is(':checked')) {
              gradingValue = 3;
            }
            contianerDetails.puc[currentPucCount].pressure = $('#stepPressureFruit').val();
            contianerDetails.puc[currentPucCount].pressure_grading = gradingValue;
            contianerDetails.puc[currentPucCount].pressure_image_temp_name = $('#pressureImgupload').prop('files')[0];
            contianerDetails.puc[currentPucCount].pressure_image_orientation = $('#pressureImageRotationValue').val();

			if (checkQuality('pressure',checkInputs,checkImage,checkGrading,gradingValue))
            loadPage(eltarget,loadUrl);
            break;

      case 'stepBrixNext':
            var gradingValue = 0;
            if ($('#stepBrixGradingGreen').is(':checked')) {
              gradingValue = 1;
            }
            if ($('#stepBrixGradingAmber').is(':checked')) {
              gradingValue = 2;
            }
            if ($('#stepBrixGradingRed').is(':checked')) {
              gradingValue = 3;
            }
            contianerDetails.puc[currentPucCount].brix = $('#stepBrixFruit').val();
            contianerDetails.puc[currentPucCount].brix_grading =  gradingValue;
            contianerDetails.puc[currentPucCount].brix_image_temp_name = $('#brixImgupload').prop('files')[0];
            contianerDetails.puc[currentPucCount].brix_image_orientation = $('#brixImageRotationValue').val();

			if (checkQuality('brix',checkInputs,checkImage,checkGrading,gradingValue))
            loadPage(eltarget,loadUrl);
            break;

      case 'stepTemperatureNext':
            var gradingValue = 0;
            if ($('#stepTemperatureGradingGreen').is(':checked')) {
              gradingValue = 1;
            }
            if ($('#stepTemperatureGradingAmber').is(':checked')) {
              gradingValue = 2;
            }
            if ($('#stepTemperatureGradingRed').is(':checked')) {
              gradingValue = 3;
            }
            contianerDetails.puc[currentPucCount].temperature = $('#stepTemperatureFruit').val();
            contianerDetails.puc[currentPucCount].temperature_grading = gradingValue;
            contianerDetails.puc[currentPucCount].temperature_image_temp_name = $('#temperatureImgupload').prop('files')[0];
            contianerDetails.puc[currentPucCount].temperature_image_orientation = $('#temperatureImageRotationValue').val();

			if (checkQuality('temperature',checkInputs,checkImage,checkGrading,gradingValue))
            loadPage(eltarget,loadUrl);
            break;

      case 'stepInternalNext':

            contianerDetails.puc[currentPucCount].internal_image_temp_name = $('#internalImgupload').prop('files')[0];
            contianerDetails.puc[currentPucCount].external_image_temp_name = $('#externalImgupload').prop('files')[0];
            contianerDetails.puc[currentPucCount].external_image_orientation = $('#externalImageRotationValue').val();
			contianerDetails.puc[currentPucCount].internal_image_orientation = $('#internalImageRotationValue').val();

			//console.log('Int:'+$('#internalImageRotationValue').val());
			//console.log('Ext:'+$('#externalImageRotationValue').val());

            loadPage(eltarget,loadUrl);
            break;

	  case  'stepCustomMetric':
            var gradingValue = 0;
            if ($('#stepCustomGradingGreen').is(':checked')) {
              gradingValue = 1;
			}
            if ($('#stepCustomGradingAmber').is(':checked')) {
              gradingValue = 2;
            }
            if ($('#stepCustomGradingRed').is(':checked')) {
              gradingValue = 3;
            }
            contianerDetails.puc[currentPucCount].custom = $('#stepCustomMetricValue').val();
            contianerDetails.puc[currentPucCount].custom_metric = $('#stepCustomMetricMetric').val();
            contianerDetails.puc[currentPucCount].custom_grading = gradingValue;
            donePuc[currentPucCount] = currentPucNo;
            loadPage(eltarget,loadUrl);
            break;


      case  'stepOverallGrading':
            var gradingValue = 0;
            if ($('#stepOverallGradingGreen').is(':checked')) {
              gradingValue = 1;
            }
            if ($('#stepOverallGradingAmber').is(':checked')) {
              gradingValue = 2;
            }
            if ($('#stepOverallGradingRed').is(':checked')) {
              gradingValue = 3;
            }
            contianerDetails.puc[currentPucCount].additional_comments = $('#stepOverallComment').val();
            contianerDetails.puc[currentPucCount].overall_grading = gradingValue;
            donePuc[currentPucCount] = currentPucNo;

			if (checkGrading)
			 {
				 if (gradingValue == 0)
					 displayMessage('Please select a grading','qcMsg');
				 else
					 loadPage(eltarget,loadUrl);
			 }
			 else
				loadPage(eltarget,loadUrl);

            break;

      case 'stepSend':
            var selected = [];

            contianerDetails.user_id = currentUserId;

            if ($('#stepSendOffice').prop('checked')) {
                contianerDetails.send_office = 1;
            }

            if ($('#stepSendExporter').prop('checked')) {
                contianerDetails.send_exporter = 1;
            }

            $('#checkboxes').each(function() {
              selected.push($(this).attr('name'));
              contianerDetails.send_Puc = selected;
            });

            var fd = new FormData();

			//console.log('Data:'+contianerDetails.puc[currentPucCount]);

            fd.append("weight_file", weight_file);
            fd.append("pressure_file", pressure_file);
            fd.append("brix_file", brix_file);
            fd.append("temperature_file", temperature_file);
            fd.append("internal_file", internal_file);
            fd.append("external_file", external_file);
            fd.append("contianerDetails", JSON.stringify(contianerDetails));

            $.ajax({
                type: "POST",
                url: "api/addContainer.php",
                dataType: "html",
                contentType: false,
                processData: false,
								cache : false,
                data: fd,
                success: function(data){
                  console.log(data);
                },
                failure: function(errMsg) {
                  console.log(errMsg);
                }
            });
						//clearCache();
            loadPage(eltarget,loadUrl);
            break;

            //logout / clear cookies
      case 'logout':
           logOut();
          break;

      default:
            loadPage(eltarget,loadUrl);
    }


  });

  /*
  first run files
    - check if person already logged in

  */
  checkLoggedinCookie();
  //clearCache();

});
