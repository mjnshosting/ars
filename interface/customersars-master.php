<?php
session_start();

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<script>
$("#customerssearchresults").hide();
$("#customerssearchresultstable").hide();

function hidesearch()
{
        $("#customerssearchresults").slideUp();
}

function showsearch()
{
	$("#customerssearchresults").slideDown();
	$("#customerssearchresultstable").slideDown();
	var formData = {
                    'customer_search' : $('#customerssearch').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_customers.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
			       $("#customerssearchresultstable").html(data);
                        }
                })
}

//There has got to be a better way, but for now I will do everything seperately
//casue it is taking me forever to get this figured out. I will ask for help later.

//Get Next
function get10scnc() {
	var formData = {
                    'customer_search' : $('#customerssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-next').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_customers.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-customers-search-10').empty();
                            $('#list-customers-search-10').html(data);
                    }
                })
          }

//Get Last
function get10sclc() {
	var formData = {
                    'customer_search' : $('#customerssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-last').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_customers.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-customers-search-10').empty();
                            $('#list-customers-search-10').html(data);
                    }
                })
          }

//Get Previous
function get10scpc() {
	var formData = {
                    'customer_search' : $('#customerssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-prev').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_customers.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-customers-search-10').empty();
                            $('#list-customers-search-10').html(data);
                    }
                })
          }

//Get First
function get10scfc() {
	var formData = {
                    'customer_search' : $('#customerssearch').attr('value'),
                    'get10number' : $('#get10search-arrow-first').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars_customers.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
                            $('#list-customers-search-10').empty();
                            $('#list-customers-search-10').html(data);
                    }
                })
          }

$("#customerslisttable").load("scripts/list_customers_get100.php");
</script>

<div class="pull-constraint">
	<h2 style="font-weight: 300; font-size: 33px; line-height: 35px;">customers</h2>
	    <form align="center" id="demo-2" onsubmit="showsearch(this); return false;">
		<input type="search" placeholder="Search" id="customerssearch"><br><h4>Search</h4>
    	    </form>
</div>
  <div class="div-table">
    <div class="div-table-row">
   </div>
   </div>
<br>

<div id="customerssearchresults">
	<h4 style="font-weight: 300; font-size: 18px; line-height: 35px; display: inline-flex;">search results&nbsp;&nbsp;<img onclick="hidesearch();" src="images/ban.svg" style="height:32px; width:32px" class="form-submit"></h4>
	<div id="customerssearchresultstable"></div>
</div>

<div id="customerslisttable"></div>
