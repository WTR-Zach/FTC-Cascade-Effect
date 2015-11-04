<html>
  <head>
    <title>FTC Team 4512 West Torrance Robotics Scouting System</title>
    
    <?php
      
      $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"
      $result = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      
      $newcompetition = $_POST['newcompetition'];
      if ($_POST['hasinput']=='true')
      {
        $ret =$db->exec("INSERT INTO COMPETITIONS (Competition) VALUES ('".$newcompetition."');");
      }
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
            if(xmlhttp0.responseText)
              document.getElementById("competition_input").submit();
            else
              document.getElementById("error").innerHTML = "<font color='RED'>Incorrect Password</font>";              
          }
        }
        xmlhttp0.open("GET","passwordcheck.php?password="+password,true);
        xmlhttp0.send();
      }
    </script>
    
  </head>
  
  <body>
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
  
  Competitions Stored: <br>
  <table cellspacing="2" cellpadding="2">
    <?php
      $result = $db->query('SELECT Competition FROM COMPETITIONS') or die('Query failed');  //query database, return all data
      while ($row = $result->fetchArray())  //Read through returned data one line at a time
      {
        echo "<tr><td>";
        echo $row['Competition'];
        echo "<td/></tr>";   //Write to simple table
      }
    ?>
  </table>
  
  <br>
  <form method="post" id="competition_input">
    <input type="hidden" name="hasinput" value="true">
    New Competition: <input type="text" name="newcompetition" id="newcompetition" /><br>
    Password: <input type="password" id="psw"><br>
    <!-- Submit data -->
    <button type="button" onclick="passwordcheck()" >Submit</button>
    <br>
    <table><tr><td> <div id="error"/> </td></tr></table>
    
  </form>
  
  </body>
</html>