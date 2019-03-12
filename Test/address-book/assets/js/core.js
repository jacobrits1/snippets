/*
Developer : Jaco Brits
Email : jaco@netstart.co.za

Core controller of view pages and load mainContent div



*/
//global veriables
var gApikey = '';
var gSiteId = '';
var gVendorId = '';
var gUrl = '';//Authorization/Register?apikey=814f7168-1876-480d-9d6a-68ec1ddc2f30&siteid=6188&vendorid=504'
var gVersion_header = 'Address V0.001';
var gDebugSys = 1;
var gStartup = 0;



//global controls setups
$.ajaxSetup({
  headers : {
      'CsrfToken': $('meta[name="csrf-token"]').attr('content'),
      'Access-Control-Allow-Origin': 'http://localhost:8888'
    }
});

// document.addEventListener('DOMContentLoaded', function() {
//     var elems = document.querySelectorAll('.sidenav');
//     var instances = M.Sidenav.init(elems, options);
// });

/*grobal define use functions
*/
/*
Load all user details and populate correct info to the append
*/
/*
*  Page Actions
*
*/

$( document ).ready(function() {

  //initalize
  //$('.sidenav').sidenav();


  //global functions
	if(gStartup == 0){

		loadPage("mainContent","views/login/login.html");
	}

  $('body').on('click', '.button', function(){
    var eltarget = $(this).attr('eltarget');
    var loadUrl = $(this).attr('url');
    var action = $(this).attr('action');
		startup = 1;

    switch(action) {
			case 'login':

			break;

      case 'dashboard':
        $('#title').html("Dashboard");
        loadPage(eltarget,loadUrl);
      break;


      case 'logout':
           loadPage("mainContent","views/login/login.html");
          break;

      default:
            loadPage(eltarget,loadUrl);
    }


  });

});
