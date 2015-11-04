<?php
  //Needs editing
  $team = intval($_GET['team']);
  $competition = $_GET['comp'];
  $selectedmatch = $_GET['match'];
  
  $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"
  
  
  $result = $db->query('SELECT Match FROM COMPETITION_MATCH_DATA WHERE TeamNumber="'.$team.'" AND Competition="'.$competition.'"') or die('Match query failed');  //query database, return match numbers
  
  $i=0;
  while ($row = $result->fetchArray())  //Read through returned data one line at a time
  {
    $matches[$i] = $row['Match'];        //Save each match number to a new php array
    $i++;
  }
  sort($matches);
  $numberofmatches=count($matches);
  
  //If the "suggested" match is not valid, change $selectedteam to be the first team on the list
  if (!in_array($selectedmatch,$matches))
  {
    $selectedmatch = $matches[0];
  }
  
  echo "Match:";
  echo '<select name="match" id="matches" onChange="changeMatch(this.value)">';
  
  for($i = 0; $i < $numberofmatches; $i++)
  {
      echo "<option value=".$matches[$i];
      if( $matches[$i] == $selectedmatch)
      {
        echo ' selected="selected"';
      }
      echo " >".$matches[$i]."</option>";
  }
  
  echo "</select>";
  
  //return actual selected match in last 2 characters of returned text
  $numberofleadingzeros = 2 - strlen($selectedmatch);
  for($i = 0; $i < $numberofleadingzeros; $i++)
  {
    echo '0'; //add in leading zeros to fill space
  }
  echo $selectedmatch;
  
  $db->close();
  
?>