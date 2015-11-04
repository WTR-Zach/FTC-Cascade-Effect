<?php

$team = intval($_GET['team']);
$comp = $_GET['comp'];

$db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

//$tablename="4512_Webb";
$result = $db->query('SELECT * FROM "COMPETITION_MATCH_DATA" WHERE TeamNumber="'.$team.'" AND Competition="'.$comp.'"') or die('Scouting data query failed');  //query database, return data


echo '<table cellspacing="2" cellpadding="2">';
echo "<tr><td>Match<td/><td>Start Pos<td/><td>Auto Floor<td/><td>Auto Balls<td/><td>Auto Rolling<td/><td>Auto Center<td/><td>Auto Park Goal<td/><td>30cm<td/><td>60cm<td/><td>90cm<td/><td>Center Goal<td/><td>Ramp End<td/><td>Park End<td/><td>Comments<td/></tr>";
while ($row = $result->fetchArray())  //Read through returned data one line at a time
{
  if(!is_null($row['CenterGoalEnd'])) //Make sure data has been entered.  If it has, CenterGoalEnd won't be empty
  {
    echo "<tr>";

    echo "<td>".$row['Match']."<td/>"; 
    echo "<td>".$row['StartingPosition']."<td/>";
    if($row['AutoFloor']==1) echo "<td>Yes<td/>";else echo "<td>No<td/>";
    if($row['AutoKickstand']==1) echo "<td>Yes<td/>";else echo "<td>No<td/>";
    echo "<td>".$row['AutoRollingGoal']."<td/>";
    if($row['AutoCenterGoal']==1) echo "<td>Yes<td/>";else echo "<td>No<td/>";
    echo "<td>".$row['AutoParkGoal']."<td/>";
    echo "<td>".$row['30cm']."%<td/>";
    echo "<td>".$row['60cm']."%<td/>";
    echo "<td>".$row['90cm']."%<td/>";
    if($row['CenterGoalEnd']==-1) echo "<td>DNA<td/>";else echo "<td>".$row['CenterGoalEnd']."%<td/>";
    echo "<td>".$row['RampEnd']."<td/>";
    echo "<td>".$row['ParkEnd']."<td/>";
    echo "<td>".$row['Comments']."<td/>";

    echo "</tr>";
  }
}
echo "</table>";

$db->close();

?>