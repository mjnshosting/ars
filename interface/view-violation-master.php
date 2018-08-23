<?php
session_start();

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<script>
</script>

<div class="pull-constraint">
	<img id="get10search-arrow-first" onclick="" src="images/first-arrow-left.svg" height="24" width="24" alt="first arrow"><h2 style="font-weight: 300; font-size: 33px; line-height: 35px;">violation</h2>
</div>

<div id="view-violation"></div>
