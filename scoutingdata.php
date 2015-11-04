<html>
  <head>
    <title><?php echo "FTC Team 4512 Scouting Data";?></title>
    <!-- Using SqliteStudio to make databases (http://sqlitestudio.pl/) -->
    
    <?php
    
    //Pull team numbers from database
    //Based on http://babbage.cs.qc.cuny.edu/courses/cs903/2013_02/using_sqlite3.html 
   
    $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

    $result = $db->query('SELECT * FROM TEAM_NAMES') or die('Query failed');  //query database, return all data
    $i=0;
    while ($row = $result->fetchArray())  //Read through returned data one line at a time
    {
      $teams[$i] = $row['TeamNumber'];        //Save each team number to a new php array
      //$teamnames[$i] = $row['TeamName'];
      $i++;
    }
    
    sort($teams);                         //Sort team number array so they appear in order
    $numteams = count($teams);            //Counts elements in $teams (number of teams at competition)
    
    
    //Process requested team
    if (!is_null($_POST['team']) && in_array($_POST['team'], $teams) )        //Search bar
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
      $result = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      while ($row = $result->fetchArray())  //Get latest input value.
      {
        $competition=$row['Competition'];
      }
    }
    else $competition = $_POST['competition']; 
    

    ?>
    
    <script>
    
    function changeTeam(team) {
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
      xmlhttp1.open("GET","displayteam.php?q="+team,true);
      xmlhttp1.send();
      returndata(team);
    }
    
    function displayteamlist(comp, team)
    {
      //team = document.getElementById('storeteam').value;
      if (comp=="") {
        document.getElementById("teamlist").innerHTML="";
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
          //Data to display is everything but last 5 digits of responseText
          var responsetext = xmlhttp2.responseText;
          document.getElementById("teamlist").innerHTML=responsetext.substring(0,responsetext.length-5);
          
          //Actual selected team is recorded as last 5 digits of responseText
          team=parseInt(responsetext.substring(responsetext.length-5),10);
          changeTeam(team);
          
          option = document.getElementById("options").value;
          returnsummaries(option);
        }
      }
      xmlhttp2.open("GET","getteamselect.php?team="+team+"&comp="+comp,true);
      xmlhttp2.send();
    }
    
    function returndata(team)
    {
      comp = document.getElementById('selectcomp').value;
      if (team=="") {
        document.getElementById("datadiv").innerHTML="";
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
          document.getElementById("datadiv").innerHTML=xmlhttp3.responseText;
        }
      }
      xmlhttp3.open("GET","returndata.php?team="+team+"&comp="+comp,true);
      xmlhttp3.send();
    }
    
    function returnsummaries(option)
    {
      comp = document.getElementById('selectcomp').value;
      if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        xmlhttp4=new XMLHttpRequest();
      } else { // code for IE6, IE5
        xmlhttp4=new ActiveXObject("Microsoft.XMLHTTP");
      }
      xmlhttp4.onreadystatechange=function() {
        if (xmlhttp4.readyState==4 && xmlhttp4.status==200) {
          document.getElementById("datasummaries").innerHTML=xmlhttp4.responseText;
        }
      }
      xmlhttp4.open("GET","returnsummary.php?option="+option+"&comp="+comp,true);
      xmlhttp4.send();
    }
    
    </script>
    
</head>
    
  </head>

<!-- ======================================================================= -->

  <body onload="displayteamlist(<?php echo "'".$competition."', '".$selectedteam."'"; ?>)">
  
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
  Competition:
    <?php
      $comps = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      echo '<select name="comp" id="selectcomp" onchange="displayteamlist(this.value)">';
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
     <tr>
        <td><div id="teamlist"/></td>
     </tr>
     <tr>
        <td> <div id="datadiv"/> </td>
     </tr>
     <tr><td><br><br></td></tr>
     <tr>
        <td> <font size="6"> Team Data Summaries </font> </td>
     </tr>
     <tr>
        <td> 
           Average:<input type="radio" name="options" id="options" value="average" onchange="returnsummaries(this.value)" checked="checked">
           High Score:<input type="radio" name="options" id="options" value="highscore" onchange="returnsummaries(this.value)">
           Comments:<input type="radio" name="options" id="options" value="comments" onchange="returnsummaries(this.value)">
           <br><br>
        </td>
     </tr>
     <tr>
        <td> <div id="datasummaries"/> </td>
     </tr>
  </table>
  <br>
  
  <form method="post" id="scout">
  <table cellspacing="1" cellpadding="2">
    <tr>
      <td><div id="teamlist"/><td/>
    </tr>
  </table>
  
   <!-- Hidden field to store values -->
   <input type="hidden" name="match" value=<?php echo $match; ?> id="storematch"> 
   <input type="hidden" name="team" value=<?php echo $selectedteam; ?> id="storeteam">
   <input type="hidden" name="competition" value="<?php echo $competition; ?>" id="storecomp">
        
    <br>
    <!--Data goes below here:-->
    <!--
    <table cellspacing="1" cellpadding="2">
      <tr>
        <td><div id="datadiv"/><td/>
      </tr>
    </table>
    -->
  
    
  </form>
   
   <?php
     $db->close();
   ?>
  </body>
</html>