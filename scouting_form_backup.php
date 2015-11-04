<html>
  <head>
    <title><?php echo "FTC Team 4512 Scouting Form";?></title>
    <!-- Using SqliteStudio to make databases (http://sqlitestudio.pl/) -->
    
    <?php
    
    //Pull team numbers from database
    //Based on http://babbage.cs.qc.cuny.edu/courses/cs903/2013_02/using_sqlite3.html 
   
    $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

    $result = $db->query('SELECT * FROM TEAM_NAMES') or die('Query failed');  //query database, return all data
    $i=0;
    while ($row = $result->fetchArray())  //Read through returned data one line at a time
    {
      $teams[$i] = $row['NUMBER'];        //Save each team number to a new php array
      //$teamnames[$i] = $row['NAME'];
      $i++;
    }
    
    sort($teams);                         //Sort team number array so they appear in order
    $numteams = count($teams);            //Counts elements in $teams (number of teams at competition)
    
    
    //Process requested team
    if (!is_null($_POST['searchteam']) && in_array($_POST['searchteam'], $teams) )        //Search bar
    {
      $selectedteam = $_POST['searchteam'];     //Search bar gets first priority
    }  
    else if (!is_null($_POST['selectteam']) && in_array($_POST['selectteam'], $teams) )   //Select menu
    {
      $selectedteam = $_POST['selectteam'];     //If no number in search bar or entered number is not a valid team.
    }     
    else
    {   
      //Placeholder.  No ideas for replacement yet, though.
      $selectedteam = 0;
    }
    
    //Record previous match value
    if(is_null($_POST['match'])) $match = 0;
    else $match = $_POST['match'];
    
    //Set current competition.  For testing, set as "Webb"
    if(is_null($_POST['competition']))
    {
      $result = $db->query('SELECT COMPETITION FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      while ($row = $result->fetchArray())  //Get latest input value.
      {
        $competition=$row['COMPETITION'];
      }
    }
    else $competition = $_POST['competition'];
    
    
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
      
      $updatedata = "Starting_Position='".$start_pos."', Auto_Floor='".$auto_floor."', Auto_Balls='".$auto_balls
              ."', Auto_Rolling='".$auto_rolling."', Auto_Center='".$auto_center."', Auto_Park_Goal='".$auto_park_goal
              ."', [30cm]='".$_30cm."', [60cm]='".$_60cm."', [90cm]='".$_90cm."', Center_End='".$center_end."', Ramp_End='".$ramp_end
              ."', Park_End='".$park_end."', Comments=\"".$comments."\"";
      $insertdata = '\''.$match.'\',\''.$start_pos.'\',\''.$auto_floor.'\',\''.$auto_balls.'\',\''
              .$auto_rolling.'\',\''.$auto_center.'\',\''.$auto_park_goal.'\','
              .$_30cm.','.$_60cm.','.$_90cm.','.$center_end.','.$ramp_end.','.$park_end.',"'.$comments.'"';
      
      $tablename = $selectedteam."_".$competition;
      $result = $db->query('SELECT MATCH FROM "'.$tablename.'";') or die('Query failed');  //query database, return match data
      
      $i=0;
      while ($row = $result->fetchArray())  //Read through returned data one line at a time
      {
        $matches[$i] = $row['Match'];        //Save each match number to a new php array
        $i++;
      }
      
      if (in_array($match, $matches))
        $ret =$db->exec("UPDATE '".$tablename."' SET ".$updatedata." WHERE Match=".$match.";");
      else
        $ret =$db->exec("INSERT INTO '".$tablename."' VALUES (".$insertdata.");");
      
    }
    

    ?>
    
    <script>
    
    function showTeam(team) {
      if (team=="") {
        document.getElementById("teamName").innerHTML="";
        return;
      }
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp1=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp1=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp1.onreadystatechange=function() {
        if (xmlhttp1.readyState==4 && xmlhttp1.status==200) {
          document.getElementById("teamName").innerHTML=xmlhttp1.responseText;
        }
      }
      xmlhttp1.open("GET","getteam.php?q="+team,true);
      xmlhttp1.send();
      document.getElementById("storeteam").value=team;
      getmatches(team);
    }
    
    function getmatches(team)
    {
      comp = document.getElementById("selectcomp").value;
      if (team=="") {
        document.getElementById("matchlist").innerHTML="";
        return;
      }
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp2=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp2=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp2.onreadystatechange=function() {
        if (xmlhttp2.readyState==4 && xmlhttp2.status==200) {
          document.getElementById("matchlist").innerHTML=xmlhttp2.responseText;
        }
      }
      xmlhttp2.open("GET","getmatches.php?team="+team+"&comp="+comp,true);
      xmlhttp2.send();
      document.getElementById("newmatch").innerHTML = '<input type="text" name="newmatchnumber" id="newmatchnumber" size=4>';
    }
    
    function getteams(comp, team)
    {
      //team = document.getElementById('storeteam').value;
      if (comp=="") {
        document.getElementById("teamlist").innerHTML="";
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
          document.getElementById("teamlist").innerHTML=xmlhttp3.responseText;
        }
      }
      xmlhttp3.open("GET","getteams.php?team="+team+"&comp="+comp,true);
      xmlhttp3.send();
      document.getElementById("storecomp").value=comp;
      showTeam(team);
    }
    
    function checksubmit()
    {
      match = document.getElementById("matches").value;
      if (match>0)
      {
        document.getElementById("storematch").value = match;
        document.getElementById("scout").submit();
      }
      else
      {
        if (match==-1 && document.getElementById("newmatchnumber").value>0)
        {
          document.getElementById("storematch").value = document.getElementById("newmatchnumber").value;
          document.getElementById("scout").submit();
        }
        else
        {
          document.getElementById("error").innerHTML = "Please input valid match number";
          //window.alert();
        }
      }
    }
    
    function newmatch()
    {
      if ( document.getElementById("matches").value >=0 )
        document.getElementById("newmatch").innerHTML = '';
      else
        document.getElementById("newmatch").innerHTML = '<input type="text" name="newmatchnumber" id="newmatchnumber" size=4>';  

    }
    
    function loadscoutingform(comp,team,match)
    {
      if (comp=="") {
        document.getElementById("teamlist").innerHTML="";
        return;
      }
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp4=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp4.onreadystatechange=function() {
        if (xmlhttp4.readyState==4 && xmlhttp4.status==200) {
          document.getElementById("scoutingform").innerHTML=xmlhttp3.responseText;
        }
      }
      xmlhttp4.open("GET","getteams.php?team="+team+"&comp="+comp,true);
      xmlhttp4.send();
      document.getElementById("storecomp").value=comp;
    }
    
    </script>
    
</head>
    
  </head>

<!-- ======================================================================= -->

  <body onload="getteams(<?php echo "'".$competition."', '".$selectedteam."'"; ?>)">
  
  <!-- Top row buttons for crude navigation -->  
  <table cellspacing="2" cellpadding="2">
    <tr>
      <td><a href="index.html">Home</a><td/>
      <td><a href="scouting_form.php">Scouting Form</a><td/>
      <td><a href="teaminput.php">Team Input</a><td/>
      <td><a href="scoutingdata.php">Scouting Data</a><td/>
      <td><a href="setcompetition.php">Set Competition</a><td/>
    </tr>
  </table>
  <br>
  Competition:
    <?php
      $comps = $db->query('SELECT COMPETITION FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      echo '<select name="comp" id="selectcomp" onchange="getteams(this.value)" onfocus="getteams(this.value)">';
      while ($row = $comps->fetchArray())  //Read through returned data one line at a time
      {
        echo '<option value="'.$row['COMPETITION'].'"';
        if ($competition == $row['COMPETITION']) echo 'selected="selected"';
        echo " >" . $row['COMPETITION'] . " </option>";
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
  
  <form method="post" id="scout">
  <table cellspacing="1" cellpadding="2">
    <tr>
      <td><div id="teamlist"/><td/>
      <td><div id="matchlist"/><td/>
      <td><div id="newmatch"/><td/>
    </tr>
  </table>
  
   <!-- Hidden field to store values -->
   <input type="hidden" name="match" value=<?php echo $match; ?> id="storematch"> 
   <input type="hidden" name="team" value=<?php echo $selectedteam; ?> id="storeteam">
   <input type="hidden" name="competition" value="<?php echo $competition; ?>" id="storecomp">
        
    <br><br>

    
    <!--Flag to say scouting data has been entered-->
    <input type="hidden" name="scoutdata" value="true" >
  
    <font size="5"> Autonomous: </font>  <!--Add "I Tried" options?-->
    <br/><br/>
    
    Starting Position:
    <input type="radio" name="start_pos" value="ramp" checked="checked">Ramp
    <input type="radio" name="start_pos" value="park">Parking Zone
    <br><br>
    
    <table cellspacing="1" cellpadding="1">
      <tr>
        <td>Drives from Ramp to Floor<td/>
        <td><input type="checkbox" name="auto_floor" value="true"><td/>
      </tr>
      <tr>
        <td>Knocks over Kickstand<td/>
        <td><input type="checkbox" name="auto_balls" value="true"><td/>
      </tr>
      <tr>
        <td>Scores Balls in Rolling Goal<td/>
        <td><input type="checkbox" name="auto_rolling1" value="true">
            <input type="checkbox" name="auto_rolling2" value="true"> <!--Will need processing before entered to db-->
        <td/>
      </tr>
      <tr>
        <td>Scores Balls in Center Goal<td/>
        <td><input type="checkbox" name="auto_center" value="true"><td/>
      </tr>
      <tr>
        <td>Moves Goals to Parking Zone<td/>
        <td><input type="checkbox" name="auto_park_goal1" value="true">
            <input type="checkbox" name="auto_park_goal2" value="true">
            <input type="checkbox" name="auto_park_goal3" value="true">
        <td/>
      </tr>
    </table>
    <br><br>

    
    
    <font size="5"> Tele-Op: </font>
    <br>
    
    <table cellspacing="2" cellpadding="3">
      <tr>
        <td> </td>
        <td> 0% <td/>
        <td> 25% <td/>
        <td> 50% <td/>
        <td> 75% <td/>
        <td> 100% <td/>
      </tr>
      <tr>
        <td> 30cm Goal </td>
        <td> <input type="radio" name="_30cm" value="0" checked="checked"> <td/>
        <td> <input type="radio" name="_30cm" value="25"> <td/>
        <td> <input type="radio" name="_30cm" value="50"> <td/>
        <td> <input type="radio" name="_30cm" value="75"> <td/>
        <td> <input type="radio" name="_30cm" value="100"> <td/>
      </tr>
      <tr>
        <td> 60cm Goal </td>
        <td> <input type="radio" name="_60cm" value="0" checked="checked"> <td/>
        <td> <input type="radio" name="_60cm" value="25"> <td/>
        <td> <input type="radio" name="_60cm" value="50"> <td/>
        <td> <input type="radio" name="_60cm" value="75"> <td/>
        <td> <input type="radio" name="_60cm" value="100"> <td/>
      </tr>
      <tr>
        <td> 90cm Goal </td>
        <td> <input type="radio" name="_90cm" value="0" checked="checked"> <td/>
        <td> <input type="radio" name="_90cm" value="25"> <td/>
        <td> <input type="radio" name="_90cm" value="50"> <td/>
        <td> <input type="radio" name="_90cm" value="75"> <td/>
        <td> <input type="radio" name="_90cm" value="100"> <td/>
      </tr>
    </table>
    
    <br><br>
    
    
    <font size="5"> End Game: </font>
    <br>
    
    <table cellspacing="2" cellpadding="3">
      <tr>
        <td> </td>
        <td> DNA <td/>
        <td> 0% <td/>
        <td> 25% <td/>
        <td> 50% <td/>
        <td> 75% <td/>
        <td> 100% <td/>
      </tr>
      <tr>
        <td> Center Goal </td>
        <td> <input type="radio" name="center_end" value="-1" checked="checked"> <td/>
        <td> <input type="radio" name="center_end" value="0" > <td/>
        <td> <input type="radio" name="center_end" value="25"> <td/>
        <td> <input type="radio" name="center_end" value="50"> <td/>
        <td> <input type="radio" name="center_end" value="75"> <td/>
        <td> <input type="radio" name="center_end" value="100"> <td/>
      </tr>
    </table>
    <br>
    
    <table cellspacing="1" cellpadding="1">
      <tr>
        <td>Rolling Goals/Robots on Ramp<td/>
        <td><input type="checkbox" name="ramp_end1" value="true">
            <input type="checkbox" name="ramp_end2" value="true">
            <input type="checkbox" name="ramp_end3" value="true">
            <input type="checkbox" name="ramp_end4" value="true">
        <td/>
      </tr>
      <tr>
        <td>Rolling Goals/Robots in Parking Zone<td/>
        <td><input type="checkbox" name="park_end1" value="true">
            <input type="checkbox" name="park_end2" value="true">
            <input type="checkbox" name="park_end3" value="true">
            <input type="checkbox" name="park_end4" value="true">
        <td/>
      </tr>
    </table>
    <br><br>
    
    <font size="5"> Comments: </font>
    <br>
    <textarea name="comments" rows="10" cols="50" ></textarea>
    <br><br>
    
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td> <div id="error"/> </td>
      </tr>
    </table>
    
    <!-- Submit data -->
    <button type="button" onclick="checksubmit()" >Submit</button>
    
  </form>
   
   <?php
     $db->close();
   ?>
  </body>
</html>