<?php
session_start();

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<script>
$("#arssearchresults").hide();
$("#arssearchresultstable").hide();

function hidesearch()
{
        $("#arssearchresults").slideUp();
}

function showsearch()
{
	$("#arssearchresults").slideDown();
	$("#arssearchresultstable").slideDown();
	var formData = {
                    'mdsearch' : $('#arssearch').attr('value'),
                }
                $.ajax({
                    type        : 'POST',
                    url         : 'scripts/search_ars.php',
                    data        : formData,
                    encode      : true,
                    success     : function(data) {
			       $("#arssearchresultstable").html(data);
                        }
                })
}

$("#arslisttable").load("scripts/list_ars.php");
</script>

<div class="pull-constraint">
	<h2 style="font-weight: 300; font-size: 33px; line-height: 35px;">violations</h2>
	    <form align="center" id="demo-2" onsubmit="showsearch(this); return false;">
		<input type="search" placeholder="Search" id="arssearch"><br><h4>Search</h4>
    	    </form>
</div>
  <div class="div-table">
    <div class="div-table-row">
   </div>
   </div>
<br>

<div id="arssearchresults">
	<h4 style="font-weight: 300; font-size: 18px; line-height: 35px; display: inline-flex;">search results&nbsp;&nbsp;<img onclick="hidesearch();" src="images/ban.svg" style="height:32px; width:32px" class="form-submit"></h4>
	<div id="arssearchresultstable"></div>
</div>

<div id="arslisttable"></div>
