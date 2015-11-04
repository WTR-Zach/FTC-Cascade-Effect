<?php

$competition=$_GET['comp'];
$match=$_GET['match'];
$allianceposition=$_GET['allianceposition'];

$db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

$alliance = substr($allianceposition, 0, -1);
$position = substr($allianceposition, -1, 1);
 
$result = $db->query('SELECT TeamNumber FROM "COMPETITION_MATCH_DATA" WHERE Competition = "'. $competition . '" AND Match="'.$match.'" AND Alliance="'.$alliance.'" AND Position="'.$position.'"') or die('Team query failed');  //query database, return team numbers
$data = $result->fetchArray();
echo $data[0];
//echo $competition.$match.$allianceposition;
?>