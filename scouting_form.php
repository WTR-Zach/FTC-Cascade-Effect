<html>
  <head>
    <title><?php echo "FTC Team 4512 Scouting Form";?></title>
    <!-- Using SqliteStudio to make databases (http://sqlitestudio.pl/) -->
    
    <?php
    
    //Pull team numbers from database
    //Based on http://babbage.cs.qc.cuny.edu/courses/cs903/2013_02/using_sqlite3.html 
   
    $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"
    
    
    //Set current competition.
    if(is_null($_POST['competition']))
    {
      $result = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      while ($row = $result->fetchArray())  //Get latest input value.
      {
        $competition=$row['Competition'];
      }
    }
    else $competition = $_POST['competition'];
    
    
    //Record previous match value
    if(is_null($_POST['match'])) $match = 1;
    else $match = $_POST['match'];
    
    
    //Get list of acceptable names
    $result = $db->query('SELECT TeamNumber FROM COMPETITION_TEAM_LIST WHERE Competition="'.$competition.'"') or die('Query failed');  //query database, return all data
    $i=0;
    while ($row = $result->fetchArray())  //Read through returned data one line at a time
    {
      $teams[$i] = $row[0];        //Save each team number to a new php array
      $i++;
    }
    
    
    //Process requested team
    if ( $_POST['AutoLoad']!='true' && in_array($_POST['selectteam'], $teams) ) //If not told to autoload, and selectteam is a valid team, use selectteam
    {
      $selectedteam = $_POST['selectteam'];     //Get value from select input
      
    }
    else  // We have been told to autoload, or selectteam value was invalid
    {
      //Autoload.  Placeholder.
      $selectedteam = 0;
    }
        
    
    //Record data from scouting
    if (!is_null($_POST['scoutdata']) && $match!='')
    {
      // Starting Position
      $start_pos = $_POST['start_pos'];
      
      // Drive from ramp to floor
      if ( $_POST['auto_floor'] == "true" && $start_pos == "ramp" )
      {
        $auto_floor = 1;
      }
      else
      {
        $auto_floor = 0;
      }
      
      // Autonomous kickstand
      if ( $_POST['auto_balls'] == "true" )
      {
        $auto_balls = 1;
      }
      else
      {
        $auto_balls = 0;
      }
      
      // Score balls in Rolling Goal
      if ( $_POST['auto_rolling1'] == "true" && $_POST['auto_rolling2'] == "true" )
      {
        $auto_rolling = 2;
      }
      else if ( $_POST['auto_rolling1'] == "true" xor $_POST['auto_rolling2'] == "true" )
      {
        $auto_rolling = 1;
      }
      else
      {
        $auto_rolling = 0;
      }
      
      // Score in Center Goal
      if ( $_POST['auto_center'] == "true" )
      {
        $auto_center = 1;
      }
      else
      {
        $auto_center = 0;
      }
      
      // Moves Goals to Parking Zone
      $auto_park_goal = 0;
      if ( $_POST['auto_park_goal1'] == "true" )
      {
        $auto_park_goal ++;
      }
      if ( $_POST['auto_park_goal2'] == "true" )
      {
        $auto_park_goal ++;
      }
      if ( $_POST['auto_park_goal3'] == "true" )
      {
        $auto_park_goal ++;
      }
      
      $_30cm = $_POST['_30cm'];
      if ( $_30cm == '' ) $_30cm = 0; 
      $_60cm = $_POST['_60cm'];
      if ( $_60cm == '' ) $_60cm = 0; 
      $_90cm = $_POST['_90cm'];
      if ( $_90cm == '' ) $_90cm = 0; 
      
      $center_end = $_POST['center_end'];
      if ( $center_end == '' ) $center_end = 0;
      
      // Moves Robots/Goals up ramp
      $ramp_end = 0;
      if ( $_POST['ramp_end1'] == "true" )
      {
        $ramp_end ++;
      }
      if ( $_POST['ramp_end2'] == "true" )
      {
        $ramp_end ++;
      }
      if ( $_POST['ramp_end3'] == "true" )
      {
        $ramp_end ++;
      }
      if ( $_POST['ramp_end4'] == "true" )
      {
        $ramp_end ++;
      }
      
      // Moves Robots/Goals into parking zone
      $park_end = 0;
      if ( $_POST['park_end1'] == "true" )
      {
        $park_end ++;
      }
      if ( $_POST['park_end2'] == "true" )
      {
        $park_end ++;
      }
      if ( $_POST['park_end3'] == "true" )
      {
        $park_end ++;
      }
      if ( $_POST['park_end4'] == "true" )
      {
        $park2_end ++;
      }
       
      
      $comments = $_POST['comments'];
      
      /*---------------------------*/
      
      //Submit information to database
      $updatedata = "StartingPosition='".$start_pos."', AutoFloor='".$auto_floor."', AutoKickstand='".$auto_balls
              ."', AutoRollingGoal='".$auto_rolling."', AutoCenterGoal='".$auto_center."', AutoParkGoal='".$auto_park_goal
              ."', [30cm]='".$_30cm."', [60cm]='".$_60cm."', [90cm]='".$_90cm."', CenterGoalEnd='".$center_end."', RampEnd='".$ramp_end
              ."', ParkEnd='".$park_end."', Comments=\"".$comments."\"";
      
      $ret =$db->exec("UPDATE 'COMPETITION_MATCH_DATA' SET ".$updatedata." WHERE Competition='".$competition."' AND Match='".$match."' AND TeamNumber='".$selectedteam."';"); //May edit to store based on Alliance and AlliancePosition
      //echo "UPDATE 'COMPETITION_MATCH_DATA' SET ".$updatedata." WHERE Competition='".$competition."' AND Match='".$match."' AND TeamNumber='".$selectedteam."';";
    }
    

    ?>
    
    <!-- CSS -->
    <style>
    table#t01 td
    {
      vertical-align: top;
    }
    </style>
    
    <script>
    
    
    function initialize()
    {
      
      match = document.getElementById('storematch').value;
      autoloadteam(match);
      
      //AutoLoad
      
      //Set default autoload select menu values
      var allianceposition = getCookie('allianceposition');
      if (allianceposition == "Red2") document.getElementById('autoloadallianceposition').value="Red2";
      else if (allianceposition == "Blue1") document.getElementById('autoloadallianceposition').value="Blue1";
      else if (allianceposition == "Blue2") document.getElementById('autoloadallianceposition').value="Blue2";
      else
      {
        document.getElementById('autoloadallianceposition').value="Red1";  //default value
        setCookie('allianceposition', 'Red1', 2);
      }
    }
    
    
    function getteamselect(team_,match_)  //_ at end of name means suggestion.
    {
      comp = document.getElementById('selectcomp').value;
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp1=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp1.onreadystatechange=function() {
        if (xmlhttp1.readyState==4 && xmlhttp1.status==200)
        {
          //Data to display is everything but last 5 digits of responseText
          var responsetext = xmlhttp1.responseText;
          document.getElementById("teamlist").innerHTML=responsetext.substring(0,responsetext.length-5);
          
          //Actual selected team is recorded as last 5 digits of responseText
          team=parseInt(responsetext.substring(responsetext.length-5),10);
          document.getElementById("storeteam").value=team;
          
          getmatches(team, match_);
          showTeam(team);
        }
      }
      xmlhttp1.open("GET","getteamselect.php?team="+team_+"&comp="+comp,true);
      xmlhttp1.send();
      
    }
    
    function getmatches(team, match_)
    {
      comp = document.getElementById("selectcomp").value;
      
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp2=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp2.onreadystatechange=function() {
        if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
          //Data to display is everything but last 2 digits of responseText
          var responsetext = xmlhttp2.responseText;
          document.getElementById("matchlist").innerHTML=responsetext.substring(0,responsetext.length-2);
          
          //Actual selected team is recorded as last 2 digits of responseText
          match=parseInt(responsetext.substring(responsetext.length-2),10);
          document.getElementById("autoloadmatch").value=match;
          document.getElementById("storematch").value=match;
          
          loadscoutingform(team, match);
        }
      }
      xmlhttp2.open("GET","getmatches.php?team="+team+"&match="+match_+"&comp="+comp,true);
      xmlhttp2.send();
    }
    
    function showTeam(team) {
      if (team=="") {
        document.getElementById("teamName").innerHTML="";
        return;
      }
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp3=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp3=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp3.onreadystatechange=function() {
        if (xmlhttp3.readyState==4 && xmlhttp3.status==200) {
          document.getElementById("teamName").innerHTML=xmlhttp3.responseText;
        }
      }
      xmlhttp3.open("GET","getteam.php?q="+team,true);
      xmlhttp3.send();
    }
    
    function loadscoutingform(team, match)
    {
      comp = document.getElementById("selectcomp").value;
      var allianceposition = getCookie('allianceposition');
      
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp4=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp4.onreadystatechange=function() {
        if (xmlhttp4.readyState==4 && xmlhttp4.status==200) {
          document.getElementById("scoutingform").innerHTML=xmlhttp4.responseText;
        }
      }
      xmlhttp4.open("GET","loadscoutingform.php?comp="+comp+"&allianceposition="+allianceposition+"&match="+match,true);
      xmlhttp4.send();
    }
    
    function changeTeam(team)
    {
      comp = document.getElementById("selectcomp").value;
      match = document.getElementById("matches").value;
      
      showTeam(team);
      getmatches(team, match);
    }
    
    function changeMatch(match)
    {
      comp = document.getElementById("selectcomp").value;
      team = document.getElementById("selectteam").value;
      document.getElementById("autoloadmatch").value=match;
      document.getElementById("storematch").value=match;
      
      loadscoutingform(team, match);
    }
    
    
    function checksubmit()
    {
      match = document.getElementById("matches").value;
      startpos = document.getElementsByName("start_pos");
      if ( match!=null && match>0 )
      {
        if ( true )//document.getElementsById("start_pos1").checked || document.getElementsById("start_pos2").checked )
        {
          document.getElementById("scout").submit();
        }
        else  // seems to be working now?
        {
          document.getElementById("error").innerHTML = "<font color='RED'>Please indicate starting position</font>";
          //window.alert();
        }
      }
      
    }
    
    
    // Cookies (from http://www.w3schools.com/js/js_cookies.asp)
    function setCookie(cname, cvalue, exdays)
    {
      var d = new Date();
      d.setTime(d.getTime() + (exdays*24*60*60*1000));
      var expires = "expires="+d.toUTCString();
      document.cookie = cname + "=" + cvalue + "; " + expires;
    }

    function getCookie(cname)
    {
        var name = cname + "=";
        var ca = document.cookie.split(';');
        for(var i=0; i<ca.length; i++) {
            var c = ca[i];
            while (c.charAt(0)==' ') c = c.substring(1);
            if (c.indexOf(name) == 0) return c.substring(name.length, c.length);
        }
        return "";
    }

    
    //AutoLoad Stuff
    function movetonextmatch()
    {
      var newmatch = parseInt(document.getElementById("autoloadmatch").value,10) + 1;
      if (newmatch <= 99)
      {
        document.getElementById("autoloadmatch").value = newmatch;
        autoloadteam(newmatch);
      }
    }
    
    function movetoprevmatch()
    {
      var newmatch = parseInt(document.getElementById("autoloadmatch").value,10) - 1;
      if (newmatch >= 1)
      {
        document.getElementById("autoloadmatch").value = newmatch;
        autoloadteam(newmatch);
      }
    }
    
    function autoloadteam(match)
    {
      comp=document.getElementById("selectcomp").value;
      allianceposition=getCookie('allianceposition');
      
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        autoloaddata=new XMLHttpRequest();
      } else { // code for IE6, IE5
        autoloaddata=new ActiveXObject("Microsoft.XMLHTTP");
      }
      autoloaddata.onreadystatechange=function() {
        if (autoloaddata.readyState==4 && autoloaddata.status==200)
        {
          getteamselect(autoloaddata.responseText,match);
        }
      }
      autoloaddata.open("GET","getautoloaddata.php?comp="+comp+"&allianceposition="+allianceposition+"&match="+match,true);
      autoloaddata.send();
      
    }
    
    function setautoload(allianceposition)
    {
      //Alliance and position given as red1, red2, blue1, or blue2
      setCookie('allianceposition', allianceposition, 2);
      
      //Change the team you're currently scouting
      autoloadteam(document.getElementById("autoloadmatch").value);
    }
    
    
    </script>
    
</head>


<!-- ======================================================================= -->

  <body onload="initialize()">
  
  <!-- Top row buttons for crude navigation -->  
    <table cellspacing="2" cellpadding="5">
      <tr>
        <td><a href="index.html">Home</a></>
        <td><a href="scouting_form.php">Scouting Form</a></>
        <td><a href="teaminput.php">Team Input</a></>
        <td><a href="inputmatchlist.php">Matchlist Input</a></>
        <td><a href="scoutingdata.php">Scouting Data</a></>
        <td><a href="setcompetition.php">Competition Input</a></>
      </tr>
    </table>
  <br>
  
  <form method="post" id="scout">
  
  <table id="t01">
  <tr><td>
  Competition:
    <?php
      $comps = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      echo '<select name="competition" id="selectcomp" onChange="initialize(this.value)" >';
      while ($row = $comps->fetchArray())  //Read through returned data one line at a time
      {
        echo '<option value="'.$row['Competition'].'"';
        if ($competition == $row['Competition']) echo 'selected="selected"';
        echo " >" . $row['Competition'] . " </option>";
      }
      echo "</select>";
      
     
    ?>
    
    
  <!--Put it in a table for formatting purposes-->
  
  <table cellspacing="0" cellpadding="0">
     <tr>
        <td> <div id="teamName"/> </td>
     </tr>
  </table>
  <br>

  <!-- Hidden field to store values -->
  <input type="hidden" name="match" id="storematch" value="<?php echo $match; ?>" > 
  <input type="hidden" name="team" id="storeteam" value="<?php echo $selectedteam; ?>" >
  
  <table cellspacing="1" cellpadding="2">
    <tr>
      <td><div id="teamlist"/></td>
      <td><div id="matchlist"/></td>
    </tr>
  </table>
  
  <br>
  
  <table cellspacing="1" cellpadding="2">
    <tr>
      <td>
        AutoLoad:
      </td>
    </tr>
    <tr>
      <td>
        <select id="autoloadallianceposition" onChange="setautoload(this.value)">
          <option value="Red1">Red 1</option>
          <option value="Red2">Red 2</option>
          <option value="Blue1">Blue 1</option>
          <option value="Blue2">Blue 2</option>
        </select>
      </td>
      <td>
        Match:<input type="number" name="autoloadmatch" id="autoloadmatch" onChange="autoloadteam(this.value)" min=1 max=99 value="<?php echo $match; ?>">
      </td>
      <td>
        <button type="button" onclick="movetoprevmatch()"><< Prev</button><button type="button" onclick="movetonextmatch()">Next >></button>
      </td>/
    </tr>
  </table>
  
        /
    <br><br>

    
    <!--Flag to say scouting data has been entered-->
    <input type="hidden" name="scoutdata" value="true" >
  
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td> <div id="scoutingform"/> </td>
      </tr>
    </table>
    
    
  </form>
  </td>
  <!-- Pull up matchlist here -->
  </tr>
  </table>
   
   <?php
     $db->close();
   ?>
  </body>
</html>