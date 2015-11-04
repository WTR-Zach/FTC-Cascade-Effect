<?php
  
  $comp = $_GET['comp'];
  $match = $_GET['match'];
  $allianceposition = $_GET['allianceposition'];
  
  $alliance = substr($allianceposition, 0, -1);
  $position = substr($allianceposition, -1, 1);
  
  if ($match>0)
  {
    $db = new SQLite3('databases/CascadeEffect') or die('Unable to open database');  //open database "test"

    $tablename= $team . "_" . $comp;
    $result = $db->query('SELECT * FROM "COMPETITION_MATCH_DATA" WHERE Match="'.$match.'" AND Alliance="'.$alliance.'" AND Position="'.$position.'" AND Competition="'.$comp.'"') or die('Scouting data query failed');  //query database, return data
    $data = $result->fetchArray();

    $db->close();
  }
  else
  {
    for($i=0;$i<14;$i++)
    {
      $data[$i]=null;
    }
    $data[10]=-1;
  }
  //index values:
  // 0  = Match
  // 1  = Starting Position
  // 2  = Auto Floor
  // 3  = Auto Balls
  // 4  = Auto Rolling
  // 5  = Auto Center
  // 6  = Auto Park Goal
  // 7  = 30cm
  // 8  = 60cm
  // 9  = 90cm
  // 10 = Center Goal
  // 11 = Ramp End
  // 12 = Park End
  // 13 = Comments
  echo '
    <font size="5"> Autonomous: </font>  <!--Add "I Tried" options?-->
    <br/><br/>
    
    Starting Position:
    <input type="radio" name="start_pos" id="start_pos1" value="ramp" ';if($data['StartingPosition']=="ramp") echo 'checked="checked"';echo'>Ramp
    <input type="radio" name="start_pos" id="start_pos2" value="park" ';if($data['StartingPosition']=="park") echo 'checked="checked"';echo'>Parking Zone
    <br><br>
    
    <table cellspacing="1" cellpadding="1">
      <tr>
        <td>Drives from Ramp to Floor<td/>
        <td><input type="checkbox" name="auto_floor" value="true" ';if($data['AutoFloor']==1) echo 'checked';echo'><td/>
      </tr>
      <tr>
        <td>Knocks over Kickstand<td/>
        <td><input type="checkbox" name="auto_balls" value="true" ';if($data['AutoKickstand']==1) echo 'checked';echo'><td/>
      </tr>
      <tr>
        <td>Scores Balls in Rolling Goal<td/>
        <td><input type="checkbox" name="auto_rolling1" value="true" ';if($data['AutoRollingGoal']>=1) echo 'checked';echo'>
            <input type="checkbox" name="auto_rolling2" value="true" ';if($data['AutoRollingGoal']==2) echo 'checked';echo'>
        <td/>
      </tr>
      <tr>
        <td>Scores Balls in Center Goal<td/>
        <td><input type="checkbox" name="auto_center" value="true" ';if($data['AutoCenterGoal']==1) echo 'checked';echo'><td/>
      </tr>
      <tr>
        <td>Moves Goals to Parking Zone<td/>
        <td><input type="checkbox" name="auto_park_goal1" value="true" ';if($data['AutoParkGoal']>=1) echo 'checked';echo'>
            <input type="checkbox" name="auto_park_goal2" value="true" ';if($data['AutoParkGoal']>=2) echo 'checked';echo'>
            <input type="checkbox" name="auto_park_goal3" value="true" ';if($data['AutoParkGoal']==3) echo 'checked';echo'>
        <td/>
      </tr>
    </table>
    <br><br>

    
    
    <font size="5"> Tele-Op: </font>
    <br>
    
    <table cellspacing="2" cellpadding="3">
      <tr>
        <td> </td>
        <td> 0% <td/>
        <td> 25% <td/>
        <td> 50% <td/>
        <td> 75% <td/>
        <td> 100% <td/>
      </tr>
      <tr>
        <td> 30cm Goal </td>
        <td> <input type="radio" name="_30cm" value="0" ';if($data['30cm']==0) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_30cm" value="25" ';if($data['30cm']==25) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_30cm" value="50" ';if($data['30cm']==50) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_30cm" value="75" ';if($data['30cm']==75) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_30cm" value="100" ';if($data['30cm']==100) echo 'checked="checked"';echo'> <td/>
      </tr>
      <tr>
        <td> 60cm Goal </td>
        <td> <input type="radio" name="_60cm" value="0" ';if($data['60cm']==0) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_60cm" value="25" ';if($data['60cm']==25) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_60cm" value="50" ';if($data['60cm']==50) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_60cm" value="75" ';if($data['60cm']==75) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_60cm" value="100" ';if($data['60cm']==100) echo 'checked="checked"';echo'> <td/>
      </tr>
      <tr>
        <td> 90cm Goal </td>
        <td> <input type="radio" name="_90cm" value="0" ';if($data['90cm']==0) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_90cm" value="25" ';if($data['90cm']==25) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_90cm" value="50" ';if($data['90cm']==50) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_90cm" value="75" ';if($data['90cm']==75) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="_90cm" value="100" ';if($data['90cm']==100) echo 'checked="checked"';echo'> <td/>
      </tr>
    </table>
    
    <br><br>
    
    
    <font size="5"> End Game: </font>
    <br>
    
    <table cellspacing="2" cellpadding="3">
      <tr>
        <td> </td>
        <td> DNA <td/>
        <td> 0% <td/>
        <td> 25% <td/>
        <td> 50% <td/>
        <td> 75% <td/>
        <td> 100% <td/>
      </tr>
      <tr>
        <td> Center Goal </td>
        <td> <input type="radio" name="center_end" value="-1" ';if($data['CenterGoalEnd']==-1) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="center_end" value="0" ';if($data['CenterGoalEnd']==0) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="center_end" value="25" ';if($data['CenterGoalEnd']==25) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="center_end" value="50" ';if($data['CenterGoalEnd']==50) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="center_end" value="75" ';if($data['CenterGoalEnd']==75) echo 'checked="checked"';echo'> <td/>
        <td> <input type="radio" name="center_end" value="100" ';if($data['CenterGoalEnd']==100) echo 'checked="checked"';echo'> <td/>
      </tr>
    </table>
    <br>
    
    <table cellspacing="1" cellpadding="1">
      <tr>
        <td>Rolling Goals/Robots on Ramp<td/>
        <td><input type="checkbox" name="ramp_end1" value="true" ';if($data['RampEnd']>=1) echo 'checked';echo'>
            <input type="checkbox" name="ramp_end2" value="true" ';if($data['RampEnd']>=2) echo 'checked';echo'>
            <input type="checkbox" name="ramp_end3" value="true" ';if($data['RampEnd']>=3) echo 'checked';echo'>
            <input type="checkbox" name="ramp_end4" value="true" ';if($data['RampEnd']==4) echo 'checked';echo'>
        <td/>
      </tr>
      <tr>
        <td>Rolling Goals/Robots in Parking Zone<td/>
        <td><input type="checkbox" name="park_end1" value="true" ';if($data['ParkEnd']>=1) echo 'checked';echo'>
            <input type="checkbox" name="park_end2" value="true" ';if($data['ParkEnd']>=2) echo 'checked';echo'>
            <input type="checkbox" name="park_end3" value="true" ';if($data['ParkEnd']>=3) echo 'checked';echo'>
            <input type="checkbox" name="park_end4" value="true" ';if($data['ParkEnd']==4) echo 'checked';echo'>
        <td/>
      </tr>
    </table>
    <br><br>
    
    <font size="5"> Comments: </font>
    <br>
    <textarea name="comments" rows="10" cols="50">'.$data['Comments'].'</textarea>
    <br><br>
    
    <table cellspacing="0" cellpadding="0">
      <tr>
        <td> <div id="error"/> </td>
      </tr>
    </table>
    
    <!-- Submit data -->
    <button type="button" onclick="checksubmit()" >Submit</button>
  ';

?>