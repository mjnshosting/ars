<?php
session_start();

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<script>
$("#routessearchresults").hide();
$("#routessearchresultstable").hide();

function hidesearch()
{
        $("#routessearchresults").slideUp();
}

function showsearch()
{
	$("#routessearchresults").slideDown();
	$("#routessearchresultstable").slideDown();
	var formData = {
                    'routes_search' : $('#routessearch').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_routes.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
			       $("#routessearchresultstable").html(data);
                        }
                })
}

//There has got to be a better way, but for now I will do everything seperately
//casue it is taking me forever to get this figured out. I will ask for help later.

//Get Next
function get10scnr() {
        var formData = {
                    'routes_search' : $('#routessearch').attr('value'),
                    'get10number' : $('#get10search-arrow-next').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_routes.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-routes-search-10').empty();
                            $('#list-routes-search-10').html(data);
                    }
                })
          }

//Get Last
function get10sclr() {
        var formData = {
                    'routes_search' : $('#routessearch').attr('value'),
                    'get10number' : $('#get10search-arrow-last').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_routes.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-routes-search-10').empty();
                            $('#list-routes-search-10').html(data);
                    }
                })
          }

//Get Previous
function get10scpr() {
        var formData = {
                    'routes_search' : $('#routessearch').attr('value'),
                    'get10number' : $('#get10search-arrow-prev').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_routes.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-routes-search-10').empty();
                            $('#list-routes-search-10').html(data);
                    }
                })
          }

//Get First
function get10scfr() {
        var formData = {
                    'routes_search' : $('#routessearch').attr('value'),
                    'get10number' : $('#get10search-arrow-first').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_routes.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-routes-search-10').empty();
                            $('#list-routes-search-10').html(data);
                    }
                })
          }

$("#routeslisttable").load("scripts/list_routes_get100.php");
</script>

<div class="pull-constraint">
	<h2 style="font-weight: 300; font-size: 33px; line-height: 35px;">routes</h2>
	    <form align="center" id="demo-2" onsubmit="showsearch(this); return false;">
		<input type="search" placeholder="Search" id="routessearch"><br><h4>Search</h4>
    	    </form>
</div>
  <div class="div-table">
    <div class="div-table-row">
   </div>
   </div>
<br>

<div id="routessearchresults">
	<h4 style="font-weight: 300; font-size: 18px; line-height: 35px; display: inline-flex;">search results&nbsp;&nbsp;<img onclick="hidesearch();" src="images/ban.svg" style="height:32px; width:32px" class="form-submit"></h4>
	<div id="routessearchresultstable"></div>
</div>

<div id="routeslisttable"></div>
