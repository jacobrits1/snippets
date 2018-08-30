$(document).ready(function() {



});


  
// clear error button
$(function(){
    $('#clear_error').click(function() {
	$.ajax({
	    url: 'index.php?c=core&m=xdelete_error',
	    success: function(data) {
		$('#error_bar').css('display','none');
	    }
	});
    });
});

function ajax_update(div, url){
    $.ajax({
	url: url,
	success: function(data) {
	    $('#'+div).html(data);
	}
    });
}

function ajax_call(url){
    $.ajax({
	url: url
    });
}

function ajax_refresh_modal(div, url, url2){
    $.ajax({
	url: url,
	success: function(data) {
	    ajax_update(div, url2);
	}
    });
}

function ajax_refresh(url){
    $.ajax({
	url: url,
	success: function(data) {
	    location.reload();
	}
    });
}

function ajax_request_refresh(url, div, url2){
    // init
    $.ajax({
	url: url,
	success: function() {
	    load_url(div, url2);
	}
    });
}

function ajax_form_refresh(url, div, url2, form){
    // init
    form = $('#'+form);

    $.ajax({
	url: url,
	type: 'POST',
	data: form.serialize(),
	success: function() {
	    load_url(div, url2);
	}
    });
}

function confirm_popup(text, url, url2){
    // ask verification
    $.SmartMessageBox({
	    title : "<i class='fa fa-warning txt-color-orangeDark'></i> Are you sure ?",
	    content : text,
	    buttons : '[No][Yes]'

    }, function(ButtonPressed) {
	    if (ButtonPressed == "Yes") {
		$.ajax({
		    url: url,
		    success: function() {
			load_url('content', url2);
		    }
		});
	    }
    });
}

function hide_modal(){
    $('#ignite_modal').removeClass('in');
    $('.modal-backdrop').removeClass('in');
}

function launch_modal(url){
    load_url('ignite_modal_content', url);

    $('#ignite_modal').removeClass('out').addClass('in');
    $('.modal-backdrop').removeClass('out').addClass('in');
    $('#ignite_modal').css("display", "block");
    $('.modal-backdrop').css("display", "block");
}

function del_cookie(name) {
    document.cookie = name + '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
}

function load_url(container, url) {
    // init
    container = $("#"+container);

    $.ajax({
	    type : "GET",
	    url : url,
	    dataType : 'html',
	    cache : true, // (warning: this will cause a timestamp and will call the request twice)
	    beforeSend : function() {
		container.html('<h1>&nbsp;&nbsp;<i class="fa fa-cog fa-lg fa-spin"></i> Loading...</h1>');

		if (container[0] == $("#content")[0]) {
		    drawBreadCrumb();
		    $("html").animate({ scrollTop : 0 }, "fast");
		}
	    },
	    success : function(data) {
		container.css({
		    opacity : '0.0'
		}).html(data).delay(50).animate({
		    opacity : '1.0'
		}, 300);

		// popup
		$.ajax({
		    dataType: 'json',
		    url: 'index.php?c=core&m=xcheck_popup',
		    success: function(data) {
			if(data[0] == "yes"){
			    launch_popup(data[1]);
			}
		    }
		});
	    },
	    async : false
    });
}

function launch_popup(text){
    $.bigBox({
	title : "Notification",
	content : text,
	color : "#739E73",
	icon : "fa fa-check  bounce animated",
	timeout : 2000
    });
}
