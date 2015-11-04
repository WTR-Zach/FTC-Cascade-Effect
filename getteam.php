<?php
$q = intval($_GET['q']);

$db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

$result = $db->query('SELECT * FROM TEAM_NAMES') or die('Query failed');  //query database, return all data

$i=0;
while ($row = $result->fetchArray())  //Read through returned data one line at a time
{
  $teams[$i] = $row['TeamNumber'];        //Save each team number to a new php array
  //$teamnames[$i] = $row['TeamName'];
  $i++;
}

//Process requested team
if (!is_null($q) && in_array($q, $teams) )   
{
  $selectedteam = $q;     //Only if it's in the array
}     
else
{   
  //Placeholder: Probably would be reading off a match schedule for this.
  $selectedteam = 0;
}

echo "<font size=\"6\"> Scouting: Team " .  $selectedteam . " </font>";

//Look for team name associated with team number in TEAM_NAMES table
$result = $db->query('SELECT TeamName FROM TEAM_NAMES WHERE TeamNumber = \''. $selectedteam . '\'' ) or die('Query failed');
$teamname = $result->fetchArray();  //not quite sure what fetchArray() does, but it doesn't work otherwise
echo $teamname[0];

echo "<br>";

// Load robot picture, if it exists
// Pictures must be .JPG files to work
$availablepics = shell_exec('ls robot_pictures');
$rawpicteams = explode("\n", $availablepics);
$numpicteams = count($rawpicteams);

for( $i=0; $i < $numpicteams; $i++ )
{
  $teampics[$i] = substr($rawpicteams[$i], 0, -4);
}

if( in_array($selectedteam, $teampics) && $selectedteam != 0 )
{
  echo "<img src = \"robot_pictures/". $selectedteam . ".JPG\" height = 300 width = 400 />";
}
else
{
  echo "<img src = \"robot_pictures/No Image.png\" height = 300 width = 400 />";
}
      
echo "<br>";

$db->close();
?>