<?php
session_start();

if (empty($_SESSION['user_name'])) {
    header('Location: index.php');
    exit;
    }
?>

<script>

function changeIt(img)
{
        var getUrl = window.location;
        var baseUrl = getUrl.protocol + "//" + getUrl.host + "/";
        var name = img.src;
                switch (name) {
                        case baseUrl + "images/edit.svg":
                                $("#showeditars").attr('src',"images/ban.svg");
                                $("#editarsmenu").slideDown();
                                break;
                        case baseUrl + "images/ban.svg":
                                $("#showeditars").attr('src',"images/edit.svg");
                                $("#editarsmenu").slideUp();
                                break;
                        default:
                                $("#editarsmenu").hide();
                                break;
                }
}
$("#editarsmenu").hide();

//$("#arscurrentsettings").load("scripts/list_ars_settings.php");
</script>

<div> 
        <h2 style="font-weight: 300; font-size: 33px; line-height: 35px;">settings <img onclick="changeIt(this);" id="showeditars" src="images/edit.svg" alt="Show/Hide Moss" class="form-submit" style="height:32px; width:32px"><br></h2>
</div>

<div id="editarsmenu">
  <div class="div-table">
    <div class="div-table-row">
     <h4 style="font-weight: 300; font-size: 18px; line-height: 35px;">add new moss devices</h4>
    </div>

    <div class="div-table-row">
      <div class="div-table-col" align="left"><img src="images/home.svg" style="height:128px; width:128px"></div>
      <div class="div-table-col" align="left">
        <div id="scroll-ptm"></div>
        <div style="font-weight: bold;">Enter MOSS Device Credentials:</div>
        <div class="div-table">
          <div class="div-table-row">
            <div class="div-table-col" align="left">
              <label for="server">Device Name:</label>
              <br>
              <label for="user">Username:</label>
              <br>
              <label for="password">Password:</label>
              <br>
              <label for="assignedip">Assigned IP:</label>
              <br>
              <label for="assignedip">Notes:</label>
              <br>
            </div>
            <div class="div-table-col" align="left">
              <input type="text" name="amddn">
              <br>
              <input type="text" name="amdun">
              <br>
              <input type="password" name="amdpw">
              <br>
              <input type="text" name="amdip">
              <br>
              <div id="textarea" name="amdnotes" contenteditable></div>
              <br>
              <div style="text-align: right">
                <img onclick="editars();" src="images/save.svg" class="form-submit" style="height:32px; width:32px">
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="div-table-col" align="left"><img src="images/tools.svg" style="height:128px; width:128px">
      </div>
      <div class="div-table-col" align="left">
        <div style="font-weight: bold;">Tool Description:</div>
        <div>The information and credentials entered here <br>will allow a remote MOSS devices to connect <br> with this EPM server.<br><p style="font-weight:bold; margin:0px">*If no IP is entered then one will be dynamically<br>assigned.*</p></div>
      </div>
    </div>
  </div>
</div>
</div>

<div id="arscurrentsettings"></div>
