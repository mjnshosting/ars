<?php
session_start();

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<div id="scroll"></div>
<div style="text-align:left; display: block; font: 'Comic Sans', sans-serif; background: #fff;padding:50px;">
                <h2>License</h2>
		<p>This software and those integrated within it are subject to their respective lincense that have been determined by their authors. This project shall not be sold or modified in any way that opposes their license</p>
                <h2>Credit</h2>
		<p><a href="https://www.iconfinder.com/iconsets/small-n-flat" target="_blank">Small-N-Flat on IconFinder</a> by <a href="https://www.iconfinder.com/paomedia" target="_blank">Paomedia</a> <a href="http://creativecommons.org/licenses/by/3.0/" target="_blank">(License)</a> - The use of this iconset and images therein are licensed under the <a href="http://creativecommons.org/licenses/by/3.0/" target="_blank">Creative Commons (Attribution 3.0 Unported)</a>.
		<p><a href="https://garrettstjohn.com/article/reading-emails-with-php/" target="_blank">Reading Emails with PHP</a> by <a href="https://garrettstjohn.com/" target="_blank">Garrett St. John</a> - I used the information and code examples in the aricle as the base for my email fetching code. </p>
		<h2>Thanks</h2>
		<p>Special thanks to my awesome business partners (J Boss and Silk McVelvet) for always being there both in business and personally. They say its lonely at the top but thats usually because you dont bring anyone with you. Of course our families that always have our back and give us the motivation to continue working like we do.</p>
</div>
