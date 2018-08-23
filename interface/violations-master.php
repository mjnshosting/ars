<?php
session_start();

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<script>
$("#violationssearchresults").hide();
$("#fetchlogdiv").hide();
$("#violationssearchresultstable").hide();

function hidesearch()
{
        $("#violationssearchresults").slideUp();
}

function hidefetchlog()
{
        $("#fetchlogdiv").slideUp();
}

function showsearch()
{
	$("#violationssearchresults").slideDown();
	$("#violationssearchresultstable").slideDown();
	var formData = {
                    'violation_search' : $('#violationssearch').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_violations.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
			       $("#violationssearchresultstable").html(data);
                        }
                })
}

function retrievelog()
{
	$("#fetchlogdiv").toggle("display");
}


//There has got to be a better way, but for now I will do everything seperately
//casue it is taking me forever to get this figured out. I will ask for help later.

//Get Next
function get10scnv() {
	var formData = {
                    'violation_search' : $('#violationssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-next').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_violations.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-violations-search-10').empty();
                            $('#list-violations-search-10').html(data);
                    }
                })
          }

//Get Last
function get10sclv() {
	var formData = {
                    'violation_search' : $('#violationssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-last').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_violations.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-violations-search-10').empty();
                            $('#list-violations-search-10').html(data);
                    }
                })
          }

//Get Previous
function get10scpv() {
	var formData = {
                    'violation_search' : $('#violationssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-prev').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_violations.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-violations-search-10').empty();
                            $('#list-violations-search-10').html(data);
                    }
                })
          }

//Get First
function get10scfv() {
	var formData = {
                    'violation_search' : $('#violationssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-first').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_violations.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-violations-search-10').empty();
                            $('#list-violations-search-10').html(data);
                    }
                })
          }

$("#violationslisttable").load("scripts/list_violations_get100.php");
</script>

<!-- Fetch Icon -->
        <input onclick="retrieve();" type="button" id="retrieve" class="retrieve">
        <label for="retrieve"></br></br>Fetch</label>
<!-- Fetch Icon -->

<!-- Fetch Log Icon -->
        <input onclick="retrievelog();" type="button" id="retrievelog" class="retrievelog">
        <label for="retrievelog"></br></br>Log</label>
<!-- Fetch Log Icon -->

<div class="pull-constraint">
	<h2 style="font-weight: 300; font-size: 33px; line-height: 35px;">violations</h2>
	    <form align="center" id="demo-2" onsubmit="showsearch(this); return false;">
		<input type="search" placeholder="Search" id="violationssearch"><br><h4>Search</h4>
    	    </form>
</div>
  <div class="div-table">
    <div class="div-table-row">
   </div>
   </div>
<br>

<div id="violationssearchresults">
	<h4 style="font-weight: 300; font-size: 18px; line-height: 35px; display: inline-flex;">search results&nbsp;&nbsp;<img onclick="hidesearch();" src="images/ban.svg" style="height:32px; width:32px" class="form-submit"></h4>
	<div id="violationssearchresultstable"></div>
</div>

<div id="fetchlogdiv">
	<h4 style="font-weight: 300; font-size: 18px; line-height: 35px; display: inline-flex;">fetch log</h4>
	</br>
	</br>
	<div style="text-align: left; max-width: 80%; font-size: 84%;" id="fetchlog">
		<div style="text-align: center;"><h3 style="color:#3c88c6;display:block;">Please press the <img style="height:36px;width:36;vertical-align:bottom;" src="images/retrieve.svg"> icon above to the right.</h3></br></div>
	</div>
</div>
<div id="violationslisttable"></div>
