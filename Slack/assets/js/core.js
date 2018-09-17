/*

Core control js. 
Here is all the event triggered actions.
Also 30 second interval functions included


*/
//global veriables
var csrfToken = '<?php echo $csrf_token?>';
//global controls
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

function guid() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
      .toString(16)
      .substring(1);
  }
  return s4() + s4() + '-' + s4() + '-' + s4() + '-' + s4() + '-' + s4() + s4() + s4();
}

function checkAlarm(){
  $.ajax({
      type: "POST",
      url: "api/action.php/alarm/check",
      dataType: "json",
      success: function(data){
        $("#currentTime").html(data);
      },
      failure: function(errMsg) {
        
      }
  }); 
}

function listAlarms(){

  for(var i = 0; i <document.getElementById("alarmlist").rows.length; i++)
  {
    document.getElementById("alarmlist").deleteRow(i -1);
  }

  $.ajax({
      type: "POST",
      url: "api/action.php/alarm",
      dataType: "html",
      success: function(data){
          var obj = JSON.parse(data);
          $.each(obj, function(i, item) {
            $('<tr>').html(
            "<td>" + obj[i].id + "</td><td>" + obj[i].name + "</td><td>" + obj[i].alarm_at + "</td>" + "</tr>").appendTo('#alarmlist tbody');
          });
      },
      failure: function(errMsg) {
        
      }
  }); 
}

function setAlarm(){
  var dataJson = { 'uuid': guid(),
                    'name': $('#setName').val(),
                    'alarm_at': $('#setTime').val()};

  $.ajax({
    type: "POST",
    url: "api/action.php/alarm",
    data: dataJson,
    dataType: "json",
    success: function(data){
      console.log(data);
    },
    failure: function(errMsg) {
                
    }
  });
}

/**
 * Load js function and vars when the DOM is ready
 */

$( document ).ready(function() {

  $("#submitForm").click(function(){
    console.log("click");
    setAlarm();
  });

  checkAlarm();
  listAlarms();

  setInterval(function(){
    checkAlarm();
    listAlarms();
  }, 30000)

});


