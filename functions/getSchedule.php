<?php
require_once "functions/simple_html_dom.php";
require_once "config.php";

function getSchedule($periodId, $programId)
{
  global $config;
  global $debug;

  $scheduleUrl = $config['scheduleUrl'];
  $scheduleUrl = str_replace("[PERIOD]", $periodId, $scheduleUrl);
  $scheduleUrl = str_replace("[PROGRAM]", $programId, $scheduleUrl);

  //$domDoc = file_get_html("static/it2.html");
  //$domDoc = file_get_html($scheduleUrl);
  $fileName = "static/program/".$periodId."_".$programId.".html";
  if (!file_exists($fileName))
    die(">:(");
  $domDoc = file_get_html($fileName);

  $masterTable = $domDoc->find('table tbody', 0);

  // 1 array for each day
  $lectures = array(
  	array(),
  	array(),
  	array(),
  	array(),
  	array()
  );

  /*
   * We store the length of the previous lecture here, so that we can skip trying to parse
   * the lecture for that day, as there won't be a <tr> for it anyways.
   * If skipcount > 2 : remove 2 and skip
   */
  $skipCount = array(
  	0, 0, 0, 0, 0
  );

  // Think of curTr as each box on the page that tells the time... (going down)
  // Those are the <tr>s we're looping through
  // each <tr> is for some lameass reason followed by an empty <tr></tr> so we're skipping by 1
  for ($trIndex = 1; ($curTr = $masterTable->children($trIndex)) !== NULL; $trIndex+=2)
  {
  	$skipped = 0;
  	if ($debug) 
  	  echo "trIndex: ".$trIndex."\n";
  	  
	
  	// this first tr contains eg. 8.00 - 8.45, so we start the count from 1
  	for ($index = 1; $index < 6; $index++)
  	{
  		$day = $index-1;
  		if ($skipCount[$day] > 2)
  		{
  			if ($debug) 
  			  echo "Skipping at day $day (skipcount: ".$skipCount[$day].")\n";
			  
  			$skipCount[$day]-=2;
  			$skipped++;
  		} else {
  			$childIndex = $index-$skipped;
	
  			$curTd = $curTr->children($childIndex);
  			$innerTable = $curTd->find('table', 0);

  			$lecture = array(
  				'length'	=> $curTd->rowspan,
  				'subject'   => array(),
  				'teachers'	=> array(),
  				'rooms'		=> array(),
  				'type'		=> $config['lectureTypes']['lecture']
  			);
	
  			$skipCount[$day] = $curTd->rowspan;
        if ($trIndex == 9) // 9th <tr> is always lunch :]
        {
          $lecture['type'] = $config['lectureTypes']['lunch'];
        }
        
  			// If we can find only 1 td we can be sure it's a free lecture (no class)
  			else if (count($innerTable->find('td')) == 1)
  				$lecture['type'] = $config['lectureTypes']['free'];

  			if ($lecture['type'] == $config['lectureTypes']['lecture'])
  			{
  				// first tr are the subjects, always inside <b> tags
  				$subjects = $innerTable->children(0);
  				foreach ($subjects->find('b') as $subject)
  				{
  				  $subject = $subject->innertext;
  				  if ($subject[strlen($subject)-1] == ".")
  				    $subject = substr($subject, 0, -1);
  					array_push($lecture['subject'], $subject);
  				}

  				// second tr is the4 abbreviation of the teacher's name
  				foreach ($innerTable->children(1)->find('td') as $teacherTd)
  				{
  					$teacher = trim($teacherTd->plaintext);
  					array_push($lecture['teachers'], $teacher);
  				}

  				// third tr are the rooms
  				/*
  				 * This if is actually a pretty crappy solution to handle
  				 * for example PRAKTIK YA-lessons, where there's no teacher
  				 * and the second one WAS the room
  				 */
  				if ($innerTable->children(2) !== null)
  				{
  				  foreach ($innerTable->children(2)->find('td') as $roomTd)
    				{
    					$room = trim($roomTd->plaintext);
    					array_push($lecture['rooms'], $room);
    				}
  				}
  			}
  			//$lectures[$day][count($lectures[$day])] = $lecture;
  			array_push($lectures[$day], $lecture);
  			if ($debug)
  			{
    			print_r($lectures[$day]);
    			print_r($skipCount);
  			}
  		}
  	}
  }

  // Finally, loop the array form ending to start and trim free lessons off
  foreach ($lectures as $key => $day)
  {
    if ($debug) echo "Day: $key\n";
    $totalElements = count($day)-1;
    for ($i = $totalElements; $i >= 0; $i--)
    {
      if ($day[$i]['type'] == $config['lectureTypes']['free'])
        unset($lectures[$key][$i]);
      else
        break;
    }
  }
  
  return $lectures;
}

/*if ($debug)
{
  $periodId = "P4";
  $programId = "IT1S";
  
  getSchedule($periodId, $programId);
}*/
?>