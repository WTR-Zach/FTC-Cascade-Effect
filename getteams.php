<?php
  
  $selectedteam = intval($_GET['team']);
  $competition = $_GET['comp'];
  $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"
  
  $result = $db->query('SELECT TeamNumber FROM "COMPETITION_TEAM_LIST" WHERE Competition = \''. $competition . '\'') or die('Team query failed');  //query database, return team numbers
  
  $i=0;
  while($row = $result->fetchArray())
  {
    $teams[$i] = $row[0];        //Save each team number to a new php array
    $i++;
  }
  
  sort($teams);
  $numberofteams=count($teams);
  
  echo "Team:";
  echo '<select name="selectteam" onchange="showTeam(this.value)" onfocus="showTeam(this.value)" onload="showTeam(this.value)" id="selectteam">';
  
  for($i = 0; $i < $numberofteams; $i++)
  {
      echo "<option value=".$teams[$i];
      if( $teams[$i] == $selectedteam)
      {
        echo ' selected="selected"';
      }
      echo " >" . $teams[$i] . " </option>";
  }
  
  echo "</select>";
?>