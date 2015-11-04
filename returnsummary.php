<?php

$option = $_GET['option'];
$comp = $_GET['comp'];

$db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

//get team numbers
$result = $db->query('SELECT TeamNumber FROM "COMPETITION_TEAM_LIST" WHERE Competition="'.$comp.'"') or die('Team list scouting data query failed');  //query database, return teams at comp
$i = 0;
while ($row = $result->fetchArray())  //Read out all the team numbers at the competition
{
   $teamlist[$i] = $row[0];
   $i++;
}
$numberofteams = $i;
echo '<table>';
//start getting team summaries

/* display high scores */

if( $option == "highscore" )
{
  echo "<tr><td>Team Number</td><td>Team Name</td><td>Auto Floor</td><td>Auto Balls</td><td>Auto Rolling</td><td>Auto Center</td><td>Auto Park Goal</td><td>30cm</td><td>60cm</td><td>90cm</td><td>Center Goal</td><td>Ramp End</td><td>Park End</td></tr>";
  
  for( $i=0; $i<$numberofteams; $i++ )
  {
    $team = $teamlist[$i];
    $result = $db->query('SELECT * FROM "COMPETITION_MATCH_DATA" WHERE TeamNumber="'.$team.'" AND Competition="'.$comp.'"') or die('Scouting data query failed');  //query database, return data
    
    $teamnameresult = $db->query('SELECT TeamName FROM "TEAM_NAMES" WHERE TeamNumber="'.$team.'"') or die('Scouting data team name query failed');  //query database, return name
    $teamnamearray = $teamnameresult->fetchArray();
    $teamname = $teamnamearray[0];
    
    $autofloor = 0;
    $autokickstand = 0;
    $autorollinggoal = 0;
    $autocentergoal = 0;
    $autoparkgoal = 0;
    $_30 = 0;
    $_60 = 0;
    $_90 = 0;
    $centerend = 0;
    $rampend = 0;
    $parkend = 0;
    
    while ($row = $result->fetchArray())  //Read through returned data one line at a time
    {
      if(!is_null($row['CenterGoalEnd'])) //Make sure data has been entered.  If it has, CenterGoalEnd won't be empty
      { 
        if($row['AutoFloor']>$autofloor) $autofloor = $row['AutoFloor'];
        if($row['AutoKickstand']>$autokickstand) $autokickstand = $row['AutoKickstand'];
        if($row['AutoRollingGoal']>$autorollinggoal) $autorollinggoal = $row['AutoRollingGoal'];
        if($row['AutoCenterGoal']>$autocentergoal) $autocentergoal = $row['AutoCenterGoal'];
        if($row['AutoParkGoal']>$autoparkgoal) $autoparkgoal = $row['AutoParkGoal'];
        if($row['30cm']>$_30) $_30 = $row['30cm'];
        if($row['60cm']>$_60) $_60 = $row['60cm'];
        if($row['90cm']>$_90) $_90 = $row['90cm'];
        if($row['CenterGoalEnd']>$centerend) $centerend = $row['CenterGoalEnd'];
        if($row['RampEnd']>$rampend) $rampend = $row['RampEnd'];
        if($row['ParkEnd']>$parkend) $parkend = $row['ParkEnd'];
      } //end if($row...)
    } //end while()
    echo '<tr>
      <td>'.$team.'</td>
      <td>'.$teamname.'</td>
      <td>'.$autofloor.'</td>
      <td>'.$autokickstand.'</td>
      <td>'.$autorollinggoal.'</td>
      <td>'.$autocentergoal.'</td>
      <td>'.$autoparkgoal.'</td>
      <td>'.$_30.'%</td>
      <td>'.$_60.'%</td>
      <td>'.$_90.'%</td>
      <td>'.$centerend.'%</td>
      <td>'.$rampend.'</td>
      <td>'.$parkend.'</td>
      </tr>';
  } //end for()
} //end if highscore

/* display average scores */

elseif( $option == "average" )
{
  echo "<tr><td>Team Number</td><td>Team Name</td><td>Starting Position</td><td>Auto Floor</td><td>Auto Balls</td><td>Auto Rolling</td><td>Auto Center</td><td>Auto Park Goal</td><td>30cm</td><td>60cm</td><td>90cm</td><td>Center Goal</td><td>Ramp End</td><td>Park End</td></tr>";
  
  for( $i=0; $i<$numberofteams; $i++ )
  {
    $team = $teamlist[$i];
    $result = $db->query('SELECT * FROM "COMPETITION_MATCH_DATA" WHERE TeamNumber="'.$team.'" AND Competition="'.$comp.'"') or die('Scouting data query failed');  //query database, return data
    
    $teamnameresult = $db->query('SELECT TeamName FROM "TEAM_NAMES" WHERE TeamNumber="'.$team.'"') or die('Scouting data team name query failed');  //query database, return name
    $teamnamearray = $teamnameresult->fetchArray();
    $teamname = $teamnamearray[0];
    
    $startpos = 0;
    $autofloor = 0;
    $autokickstand = 0;
    $autorollinggoal = 0;
    $autocentergoal = 0;
    $autoparkgoal = 0;
    $_30 = 0;
    $_60 = 0;
    $_90 = 0;
    $centerend = 0;
    $rampend = 0;
    $parkend = 0;
    
    $j = 0;
    $k = 0;
    while ($row = $result->fetchArray())  //Read through returned data one line at a time
    {
      if(!is_null($row['CenterGoalEnd'])) //Make sure data has been entered.  If it has, CenterGoalEnd won't be empty
      {
        if( $row['StartingPosition'] == 'ramp') $startpos += 1;
        $autofloor += $row['AutoFloor'];
        $autokickstand += $row['AutoKickstand'];
        $autorollinggoal += $row['AutoRollingGoal'];
        $autocentergoal += $row['AutoCenterGoal'];
        $autoparkgoal += $row['AutoParkGoal'];
        $_30 += $row['30cm'];
        $_60 += $row['60cm'];
        $_90 += $row['90cm'];
        if($row['CenterGoalEnd']!=-1){$centerend += $row['CenterGoalEnd'];$k++;}
        $rampend += $row['RampEnd'];
        $parkend += $row['ParkEnd'];
        
        $j++;
      } //end if($row...)
    } //end while()
    
    if ($startpos/$j >= 0.5) $avgstartpos = 'Ramp'; else $avgstartpos = 'Floor';
    echo '<tr>
      <td>'.$team.'</td>
      <td>'.$teamname.'</td>
      <td>'.$avgstartpos.'</td>
      <td>'.round($autofloor/$j,2).'</td>
      <td>'.round($autokickstand/$j,2).'</td>
      <td>'.round($autorollinggoal/$j,2).'</td>
      <td>'.round($autocentergoal/$j,2).'</td>
      <td>'.round($autoparkgoal/$j,2).'</td>
      <td>'.round($_30/$j,2).'%</td>
      <td>'.round($_60/$j,2).'%</td>
      <td>'.round($_90/$j,2).'%</td>
      <td>'.round($centerend/$k,2).'%</td>
      <td>'.round($rampend/$j,2).'</td>
      <td>'.round($parkend/$j,2).'</td>
      </tr>';
  } //end for()
} //end elseif average
  

  /* display comments */
  
elseif( $option == "comments" )
{
  echo "<tr><td>Team Number</td><td>Team Name</td><td>Comments</td></tr>";
  for( $i=0; $i<$numberofteams; $i++ )
  {
    echo "<tr>";
    $team = $teamlist[$i];
    $result = $db->query('SELECT * FROM "COMPETITION_MATCH_DATA" WHERE TeamNumber="'.$team.'" AND Competition="'.$comp.'"') or die('Scouting data query failed');  //query database, return data
    
    $teamnameresult = $db->query('SELECT TeamName FROM "TEAM_NAMES" WHERE TeamNumber="'.$team.'"') or die('Scouting data team name query failed');  //query database, return name
    $teamnamearray = $teamnameresult->fetchArray();
    $teamname = $teamnamearray[0];
    
    echo "<tr><td>".$team."</td><td>".$teamname."</td><td><table>";
    while ($row = $result->fetchArray())  //Read through returned data one line at a time
    {
      if(!is_null($row['CenterGoalEnd'])) //Make sure data has been entered.  If it has, CenterGoalEnd won't be empty
      {
        echo "<tr><td>-".$row['Comments']."</td></tr>";
      } //end if($row...)
    } //end while()
    echo "</table></td></tr>";
  } //end for()
} //end elseif comments*/

echo '</table>';

$db->close();

?>