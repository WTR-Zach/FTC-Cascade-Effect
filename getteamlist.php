<?php
  
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
  //var_dump($teams);
  $numberofteams=count($teams);
  
  echo "<h3>Teams At ".$competition."</h3>";
  echo '<table cellspacing="1" cellpadding="1">';
  
  for($i = 0; $i < $numberofteams; $i++)
  {
    $result = $db->query('SELECT TeamName FROM TEAM_NAMES WHERE TeamNumber = \''. $teams[$i] . '\'') or die('Team Name query failed');  //query database, return team name
    $teamname = $result->fetchArray();
    echo "<tr><td>".$teams[$i]."<td/><td>".$teamname[0]."<td/></tr>";
  }
  
  echo "</table>";
  
?>