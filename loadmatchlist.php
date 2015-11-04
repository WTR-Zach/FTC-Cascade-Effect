<?php

$defaultnumberofmatches=30;

$numberofmatches = intval($_GET['numberofmatches']);
$comp = $_GET['comp'];

$db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "CascadeEffect"

//Load the list of teams that are present at this competition
$teamsresult = $db->query('SELECT TeamNumber FROM COMPETITION_TEAM_LIST WHERE Competition = \''. $comp . '\'') or die('Query failed');  //query database for teams at this competition
$i=0;
while($row = $teamsresult->fetchArray())  //doesn't work without this.  Converts the query result into a PHP array.
{
  $teamlist[$i] = $row[0];        //Save each team number to a new php array
  $i++;
}

//Load previously saved matchlist for this competition
$result = $db->query("select distinct c1.Match as 'Match', c1.TeamNumber as 'Red1', c2.TeamNumber as 'Red2', c3.TeamNumber as 'Blue1', c4.TeamNumber as 'Blue2'
                      from COMPETITION_MATCH_DATA c1, COMPETITION_MATCH_DATA c2, COMPETITION_MATCH_DATA c3, COMPETITION_MATCH_DATA c4
                      where 
                      c1.Alliance='Red' and c1.Position=1 and c1.Competition='".$comp."' and
                      c2.Alliance='Red' and c2.Position=2 and
                      c3.Alliance='Blue' and c3.Position=1 and
                      c4.Alliance='Blue' and c4.Position=2 and
                      c1.Match=c2.Match and c2.Match=c3.Match and c3.Match=c4.Match and
                      c1.Competition=c2.Competition and c2.Competition=c3.Competition and c3.Competition=c4.Competition
                      order by c1.Match") or die('Scouting data query failed');  //query database, return data

//Start displaying table
$i=1; //number of rows displayed
echo '<table cellspacing="2" cellpadding="2">';
echo "<tr><td>Match</><td>Red 1</><td>Red 2</><td>Blue 1</><td>Blue 2</></tr>"; //table headers


//If we've been asked to list the default number of values or entire content of table, whichever is higher
if ($numberofmatches == 0)
{
  $numberofmatches = $defaultnumberofmatches; //required for "add more lines" section/loop
  while ($row = $result->fetchArray())  //Read through returned data one line at a time, no truncation
  {
    echo "<tr>\n";
  
    echo "<td>".$row['Match']."</>\n"; 
    
    echo '<td><input type="text" size="5" maxlength="5" name="red1_'.$i.'" ';if( !(is_null($row['Red1'])||in_array($row['Red1'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Red1'].'" ></>'."\n";
    echo '<td><input type="text" size="5" maxlength="5" name="red2_'.$i.'" ';if( !(is_null($row['Red2'])||in_array($row['Red2'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Red2'].'" ></>'."\n";
    echo '<td><input type="text" size="5" maxlength="5" name="blue1_'.$i.'" ';if( !(is_null($row['Blue1'])||in_array($row['Blue1'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Blue1'].'" ></>'."\n";
    echo '<td><input type="text" size="5" maxlength="5" name="blue2_'.$i.'" ';if( !(is_null($row['Blue2'])||in_array($row['Blue2'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Blue2'].'" ></>'."\n";
  
    echo "</tr>\n";
    $i++;
  }
}
else  //If given a non-zero value
{
  while (($row = $result->fetchArray()) && ($i<=$numberofmatches))  //Read through returned data one line at a time, and possibly truncate
  {
    echo "<tr>\n";
    
    echo "<td>".$row['Match']."</>\n"; 
    
    echo '<td><input type="text" size="5" maxlength="5" name="red1_'.$i.'" ';if( !(is_null($row['Red1'])||in_array($row['Red1'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Red1'].'" ></>'."\n";
    echo '<td><input type="text" size="5" maxlength="5" name="red2_'.$i.'" ';if( !(is_null($row['Red2'])||in_array($row['Red2'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Red2'].'" ></>'."\n";
    echo '<td><input type="text" size="5" maxlength="5" name="blue1_'.$i.'" ';if( !(is_null($row['Blue1'])||in_array($row['Blue1'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Blue1'].'" ></>'."\n";
    echo '<td><input type="text" size="5" maxlength="5" name="blue2_'.$i.'" ';if( !(is_null($row['Blue2'])||in_array($row['Blue2'],$teamlist)) )echo 'style="color:#ff0000" '; echo 'value="'.$row['Blue2'].'" ></>'."\n";
    
    echo "</tr>\n";
    $i++;
  }
}

//Add more lines if more have been requested
while ($i<=$numberofmatches)
{
  echo "<tr>\n";
  
  echo "<td>".$i."</>\n";
  
  echo '<td><input type="text" size="5" maxlength="5" style="color:#000000" name="red1_'.$i.'" ></>'."\n";
  echo '<td><input type="text" size="5" maxlength="5" style="color:#000000" name="red2_'.$i.'" ></>'."\n";
  echo '<td><input type="text" size="5" maxlength="5" style="color:#000000" name="blue1_'.$i.'" ></>'."\n";
  echo '<td><input type="text" size="5" maxlength="5" style="color:#000000" name="blue2_'.$i.'" ></>'."\n";
  
  echo "</tr>\n";
  $i++;
}

//Close table
echo "</table>";


$db->close();

?>