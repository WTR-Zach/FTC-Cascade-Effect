<html>
  <head>
    <title><?php echo "FTC Team 4512 Scouting Match List Input Form";?></title>
    <!-- Using SqliteStudio to make databases (http://sqlitestudio.pl/) -->
    
    
    <!-- CSS -->
    <style>
      table{ text-align: right; }
    </style>
    
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
    
    
    if($_POST['inputdata'])
    {  
      //Update *_Matchlist table
      
      $alliancearray = array('Red', 'Red', 'Blue', 'Blue');
      $positionarray = array(1, 2, 1, 2);
      $inputcellarray = array('red1_', 'red2_', 'blue1_', 'blue2_');
      
      $i=1;
      //Loop until all inputs in one line are null
      while(!(($_POST['red1_'.$i]=='')&&($_POST['red2_'.$i]=='')&&($_POST['blue1_'.$i]=='')&&($_POST['blue2_'.$i]=='')))
      {
        
        for($j=0; $j<4; $j++)
        {
          //Uses database engine to keep from duplicating Competition-Match-Alliance-Position set
          $ret = $db->exec('INSERT OR REPLACE INTO COMPETITION_MATCH_DATA ( Competition, Match, TeamNumber, Alliance, Position ) VALUES ("'.$competition.'", "'.$i.'", "'.$_POST[$inputcellarray[$j].$i].'", "'.$alliancearray[$j].'", '.$positionarray[$j].')');
        }
        $i++;
      } //end while
      $ret = $db->exec('UPDATE COMPETITION_MATCH_DATA SET TeamNumber=null WHERE Competition="'.$competition.'" AND Match>='.$i.' AND TeamNumber IS NOT NULL');
      
    } //end update tables

    ?>
    
    <script> 
      
      function passwordcheck()
      {
        password=document.getElementById("psw").value;
        if (window.XMLHttpRequest) {
          // code for IE7+, Firefox, Chrome, Opera, Safari
          xmlhttp0=new XMLHttpRequest();
        } else { // code for IE6, IE5
          xmlhttp0=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp0.onreadystatechange=function() {
          if (xmlhttp0.readyState==4 && xmlhttp0.status==200) {
            if(xmlhttp0.responseText=='true')
              document.getElementById("matchlist_input_form").submit();
            else
              document.getElementById("error").innerHTML = "<font color='RED'>Incorrect Password</font>";              
          }
        }
        xmlhttp0.open("GET","passwordcheck.php?password="+password,true);
        xmlhttp0.send();
      }
       
      function loadmatchlist(numberofmatches)
      {
        comp=document.getElementById("selectcomp").value;
        if (comp=="") {
          document.getElementById("matchlist_input").innerHTML="<br>";
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
            document.getElementById("matchlist_input").innerHTML=xmlhttp1.responseText;
          }
        }
        xmlhttp1.open("GET","loadmatchlist.php?numberofmatches="+numberofmatches+"&comp="+comp,true);
        xmlhttp1.send();
      }
      
    </script>
    
  </head>

<!-- ======================================================================= -->

  <body onload="loadmatchlist(0)">
  
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
    <form method="post" id="matchlist_input_form">
      Competition:
      <?php
        $comps = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
        echo '<select name="competition" id="selectcomp" onchange="loadmatchlist(0)">'."\n";
        while ($row = $comps->fetchArray())  //Read through returned data one line at a time
        {
          echo "\t\t".'<option value="'.$row['Competition'].'"';
          if ($competition == $row['Competition']) echo 'selected="selected"';
          echo " >" . $row['Competition'] . " </option>\n";
        }
        echo "\t</select>\n";
      
      
      ?>
    
      <br><br>
      Invalid team numbers will be marked in <font color="red">red</font>.<br>
      Do not change number of matches before submitting after entering data.
      <br><br>
  
      <!-- Request number of qualifier matches -->
      Number of matches:<input type='number' onchange='loadmatchlist(this.value)' min=1 max=99 value=30>
      <br>
      
      
      <!--Flag to say scouting data has been entered-->
      <input type="hidden" name="inputdata" value="true" >
    
      <table><tr><td><div id="matchlist_input"/></td></tr></table>
      
      <br>
      <!-- Submit data -->
      Password: <input type="password" id="psw">
      <button type="button" onclick="passwordcheck()" >Submit</button>
      <br>
      <table><tr><td> <div id="error"/> </td></tr></table>
  
    </form>
      
    <?php
      $db->close();
    ?>
   
  </body>
</html>