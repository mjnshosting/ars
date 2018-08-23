function AddTopmenu(){
$("#topmenu").html("<div class='container'> \
                <div align='center' ><img class='logo' src='images/mjns-ars-logo.png' alt='MJNS ARS'></div> \
    </div>");
}

function AddFooter(){
$("#footer").html(
    '<div class="container">'+
        '<div class="row">'+
	    '<div align="center" class="socials">'+
	         '<br><br><a href="http://www.mjns.it" class="logo" target="_blank">MJ Network Solutions &copy; | <a href="https://github.com/mjnshosting" target="_blank"><i class="fa-socials fa fa-github-square"></i></a> <a href="http://twitter.com/mjnshosting" target="_blank"><i class="fa-socials fa fa-twitter-square"></i></a> <a href="http://facebook.com/mjnshosting" target="_blank"><i class="fa-socials fa fa-facebook-square"></i></a><a href="http://www.mjns.it/index-6.html" class="logo" target="_blank"> | Privacy Policy | </a><a href="#" id="license2" class="logo">Credit/License</a>'+
	    '</div>'+
        '</div>'+
    '</div>'
)}

$(AddTopmenu);
$(AddFooter);

function hidealldivs() {
	$("#master-arslist").hide();
	$("#master-license").hide();
	$("#master-arsitem").hide();
	$("#master-arscustomers").hide();
	$("#master-arsmatched").hide();
	$("#master-arsroutes").hide();
	$("#master-arssettings").hide();
}

// From: http://media.boingboing.net/wp-content/uploads/2016/01/corgiswimflip.gif
var progress_image = "<img class='logo' src='images/arsprocessing.gif' alt='Loading...' style='height:300px; width:300px;' />";

function statusall() {
//	var dashint = $("#master-dashboard").load("scripts/dashboard.php");
//	var mdtint = $("#mossdevicestable").load("scripts/list_moss.php");
}

function viewvio(arsid) {
                $(hidealldivs);
		$('#topmenu').slideDown("slow");

                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/view_violation.php',
                    data        : { arsid : arsid },
                    encode      : true,
                    success     : function(data) {
	                $("#master-arsitem").slideDown("slow");
        	        $("#master-arsitem").html(data);
                    }
                })
          }

function retrieve() {
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/email_fetching_ars.php',
                    encode      : true,
		    beforeSend  : function() {
				$('#list-violations-100').html($(progress_image));
		    },
                    success    : function(data) {
		        	$('#list-violations-100').fadeIn('slow');
		        	$('#list-violations-100').load("scripts/list_violations_get100.php");
		        	$('#fetchlog').html(data);
                    }
                })
          }	

function backtoviolations() {
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-arslist").slideDown("slow");
                $("#master-arslist").load("violations-master.php");
         }

function get100violation(get100number) {
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/list_violations_get100.php',
                    data        : { get100number : get100number },
                    encode      : true,
                    success     : function(data) {
       		            $('#list-violations-100').empty();
			    $('#list-violations-100').html(data);
                    }
                })
          }

function get100customer(get100number) {
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/list_customers_get100.php',
                    data        : { get100number : get100number },
                    encode      : true,
                    success     : function(data) {
       		            $('#list-customers-100').empty();
			    $('#list-customers-100').html(data);
                    }
                })
          }

function get100route(get100number) {
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/list_routes_get100.php',
                    data        : { get100number : get100number },
                    encode      : true,
                    success     : function(data) {
       		            $('#list-routes-100').empty();
			    $('#list-routes-100').html(data);
                    }
                })
          }

function get100matched(get100number) {
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/list_matched_get100.php',
                    data        : { get100number : get100number },
                    encode      : true,
                    success     : function(data) {
       		            $('#list-matched-100').empty();
			    $('#list-matched-100').html(data);
                    }
                })
          }

function ece(cusid) {
$("#editemail-" + cusid).click(function(){
        $("#ei-" + cusid).css('display', 'inline');
        $("#editemail-" + cusid).hide();
    });
}

function sce(cusid) {
        var editcusemail = $("input[name=" + cusid + "]").val();
        $("#ei-" + cusid).css('display', 'none');
        $("#editemail-" + cusid).show();
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/edit_email_ars_customers.php',
                    data        : {cusid:cusid, editcusemail:editcusemail},
                    encode      : true,
                    success     : function(data) {
                            $("#editemail-" + cusid).html(data);
                    }
                })
}

function dce(cusid) {
        var editcusemail = $("input[name=" + cusid + "]").val();
        $("#ei-" + cusid).css('display', 'none');
        $("#editemail-" + cusid).show();
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/del_email_ars_customers.php',
                    data        : {cusid:cusid, editcusemail:editcusemail},
                    encode      : true,
                    success     : function(data) {
                            $("#editemail-" + cusid).html(data);
                    }
                })
}

function delvio(vioid) {
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/del_ars_violation.php',
                    data        : {vioid:vioid},
                    encode      : true,
                    success     : function(data) {
			                $(hidealldivs);
					$('#topmenu').slideDown("slow");
			                $("#master-arslist").slideDown("slow");
			                $("#master-arslist").load("violations-master.php");
                    }
                })
}

$(document).ready(function(){
	document.addEventListener("touchstart", function(){}, true);
        $("#master-arslist").slideDown("slow");
        $("#master-arslist").load("violations-master.php");
/*
        $("#master-dashboard").load("listars-master.php");
	setInterval(statusall, 30000);
        $("#dashboard").click(function(){
                $(hidealldivs);
		$('#topmenu').show();
                $("#master-dashboard").show();
                $("#master-dashboard").load("scripts/dashboard.php");
        });
*/
        $("#license").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-license").slideDown("slow");
                $("#master-license").load("license-master.php");
        });
        $("#license2").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-license").slideDown("slow");
                $("#master-license").load("license-master.php");
        });
	$("#ars-list").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-arslist").slideDown("slow");
                $("#master-arslist").load("violations-master.php");
        });
	$("#return-arslist").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-arslist").slideDown("slow");
                $("#master-arslist").load("view-violation-master.php");
        });
	$("#ars-customers").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-arscustomers").slideDown("slow");
                $("#master-arscustomers").load("customersars-master.php");
        });
	$("#ars-routes").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-arsroutes").slideDown("slow");
                $("#master-arsroutes").load("routesars-master.php");
        });
	$("#ars-matched-customers").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-arsmatched").slideDown("slow");
                $("#master-arsmatched").load("matchedars-master.php");
        });
	$("#ars-settings").click(function(){
                $(hidealldivs);
		$('#topmenu').slideDown("slow");
                $("#master-arssettings").slideDown("slow");
                $("#master-arssettings").load("settingsars-master.php");
        });
});

