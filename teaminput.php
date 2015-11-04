<html>
  <head>
    <title><?php echo "FTC Team 4512 Scouting Team Input Form";?></title>
    <!-- Using SqliteStudio to make databases (http://sqlitestudio.pl/) -->
    
    <?php
    
    //Pull team numbers from database
    //Based on http://babbage.cs.qc.cuny.edu/courses/cs903/2013_02/using_sqlite3.html 
   
    $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

    $result = $db->query('SELECT * FROM TEAM_NAMES') or die('Query failed');  //query database, return all data
    $i=0;
    while ($row = $result->fetchArray())  //Read through returned data one line at a time
    {
      $oldnumbers[$i] = $row['TEAMNUMBER'];        //Save each team number to a new php array
      $oldnames[$i] = $row['TEAMNAME'];
      $i++;
    }
    
    
    $numteams = count($teams);            //Counts elements in $teams (number of teams at competition)
    
    
    
    //Set current competition.
    if(is_null($_POST['competition']))  //if no competition given
    {
      $result = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      while ($row = $result->fetchArray())  //Get latest input value.
      {
        $competition=$row['Competition'];
      }
    }
    else $competition = $_POST['competition'];  //if competition is given
    
    
    if($_POST['inputdata'])
    {
      //Update TEAM_NAMES table
      for($i=1;$i<=40;$i++)
      {
        $numberinput = "numberinput".$i;
        $nameinput = "nameinput".$i;
        $newnumbers[$i] = $_POST[$numberinput];
        $newnames[$i] = $_POST[$nameinput];
        
        if($newnumbers[$i]!=null)
        {
          if(!in_array($newnumbers[$i],$oldnumbers))
          {
            $addteamsstring = '"'.$newnumbers[$i].'","'.$newnames[$i].'"';
            $ret =$db->exec("INSERT INTO TEAM_NAMES VALUES (".$addteamsstring.");");
          }
          elseif($newnames[$i]!=null)  //the number is already stored, and the new name is not blank
          {
            $ret =$db->exec('UPDATE TEAM_NAMES SET TEAMNAME="'.$newnames[$i].'" WHERE TEAMNUMBER="'.$newnumbers[$i].'";');
          }
        }
      }
      
      
      //Update COMPETITION_TEAM_LIST table
      $result = $db->query('SELECT TEAMNUMBER FROM COMPETITION_TEAM_LIST WHERE COMPETITION = "'.$competition.'"') or die('Team query failed');  //query database, return team numbers at this competition
  
      $i=0;
      while($row = $result->fetchArray())  //Converts from sqlite array to php array.
      {
        $oldcompteams[$i] = $row[0];  //Save each team number to a new php array
        $i++;
      }
      //var_dump($oldcompteams);
      
      
      
      //$tablestuff = "[Match] INT PRIMARY KEY NOT NULL, Starting_Position VARCHAR, Auto_Floor INT, Auto_Balls INT, Auto_Rolling INT, Auto_Center INT, Auto_Park_Goal INT, [30cm] INT, [60cm] INT, [90cm] INT, Center_End INT, Ramp_End INT, Park_End INT, Comments VARCHAR";
      
      for($i = 0; $i <= 40; $i++)
      {
        $numberinput = "numberinput".$i;
        $newnumbers[$i] = $_POST[$numberinput];
        
        if($newnumbers[$i]!=null)
        {
          if(!in_array($newnumbers[$i],$oldcompteams))
          {
            
            //$ret =$db->exec('CREATE TABLE ['.$newnumbers[$i].'_'.$competition.'] ('.$tablestuff.');');  //old method
            $ret = $db->exec('INSERT INTO "COMPETITION_TEAM_LIST" VALUES ("'.$competition.'","'.$newnumbers[$i].'")');
            
            //$ret =$db->exec('UPDATE COMPETITIONS SET Team'.$j.'="'.$newnumbers[$i].'" WHERE COMPETITION="'.$competition.'";');
          }
          elseif($newnames[$i]=='-remove-')  //the number is already stored.  Remove it
          {
            $ret =$db->exec('DELETE FROM TEAM_NAMES WHERE TEAMNUMBER="'.$newnumbers[$i].'";');
            $ret =$db->exec('DELETE FROM COMPETITION_TEAM_LIST WHERE TEAMNUMBER="'.$newnumbers[$i].'" AND COMPETITION="'.$competition.'";');
          }
          
        } //end if(!null)
        
      } //end for
      
      
    } //end update tables
    

    ?>
    
    <script>
    
    function getteamlist(comp)
    {
      if (comp=="") {
        document.getElementById("teamlist").innerHTML="";
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
          document.getElementById("teamlist").innerHTML=xmlhttp1.responseText;
        }
      }
      xmlhttp1.open("GET","getteamlist.php?comp="+comp,true);
      xmlhttp1.send();
      document.getElementById("storecomp").value=comp;
    }
    
    </script>
    
</head>
    
  </head>

<!-- ======================================================================= -->

  <body onload="getteamlist(<?php echo "'".$competition."'"; ?>)">
  
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
      echo '<select name="comp" id="selectcomp" onchange="getteamlist(this.value)" onfocus="getteamlist(this.value)">';
      while ($row = $comps->fetchArray())  //Read through returned data one line at a time
      {
        echo '<option value="'.$row['Competition'].'"';
        if ($competition == $row['Competition']) echo 'selected="selected"';
        echo " >" . $row['Competition'] . " </option>";
      }
      echo "</select>";
      
      
    ?>
    
    <!-- Display teams already in competition (javascript) -->
    <table cellspacing="2" cellpadding="1">
      <tr>
        <td> <div id="teamlist"/> <td/>
      </tr>
    </table>
    <br>
    
    
  <!--Put it in a table for formatting purposes-->

  <form method="post" id="teaminput">
  
   <!-- Hidden field to store values -->
   <input type="hidden" name="competition" value="<?php echo $competition; ?>" id="storecomp">
    
    <!--Flag to say scouting data has been entered-->
    <input type="hidden" name="inputdata" value="true" >
  
    <h3> Team Input: </h3>
    
    <!-- New Teams Input Form (php) -->
    <table cellspacing="1" cellpadding="1">
      <tr>
        <td>Number<td/>
        <td>Team Name (optional)<td/>
      </tr>
      <?php
        
        for($i=1;$i<=40;$i++)
        {
          echo "<tr>";
          $numberinput = "numberinput".$i;
          $nameinput = "nameinput".$i;
          echo '<td><input type="text" name="'.$numberinput.'" size=5><td/>';
          echo '<td><input type="text" name="'.$nameinput.'" size=20><td/>';
          echo "</tr>";
        }
        
      ?>
    </table>

    
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td> <div id="error"/> </td>
      </tr>
    </table>
    
    <!-- Submit data -->
    <input type="submit" value="Submit" />
    
  </form>
   
   <?php
     $db->close();
   ?>
  </body>
</html>