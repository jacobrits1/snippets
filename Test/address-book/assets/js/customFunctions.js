/*
Developer : Jaco Brits
Email : jaco@netstart.co.za

Custom functions to help controller
Some Android functions


*/

/*initial intialition functions
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

function addPuc (key){
  listPuc[key]= $(this).val();

}

function loadPage(container,page){
	if (gDebugSys == 1){
		console.log(page);
    console.log(container);
	}

  $('#'+container).load(page);
  $('html,body').scrollTop(0);
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
