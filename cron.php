<?php
require_once "config.php";
require_once "functions/getPeriods.php";
require_once "functions/getPrograms.php";
require_once "functions/getSchedule.php";

/*
 * Update perioder.html
 */
$periodsUrl = "https://intra.novia.fi/index.php?option=com_content&view=article&id=102&Itemid=511&lang=swe";
$periodsStr = file_get_contents($periodsUrl);

$ret = file_put_contents("static/perioder.html", $periodsStr);
if ($ret === FALSE)
{
  echo "Getting perioder.html failed!";
  exit(1);
}


/*
 * Get periods from static/perioder.html
 */
$periods = getPeriods();
foreach ($periods as $period)
{
  $realUrl = $config['urlPrefix'].$period['link'];
  $periodId = $period['pId'];
  
  echo "Fetching $realUrl ...";
  
  $periodStr = file_get_contents($realUrl);
  if ($periodStr === false)
  {
    echo "\nGetting period ($pId: $realUrl) failed!";
    exit(1);
  } else {
    echo " OK!\n";
  }
  
  $fileName = "static/perioder/$periodId.html";
  
  $ret = file_put_contents($fileName, $periodStr);
  if ($ret === false)
  {
    echo "Writing period ($pId: $realUrl) failed!";
    exit(1);
  }
}

/*
 * Get each program's schedule for the LAST PERIOD ONLY!
 * Otherwise this script will take forever :]
 * static/program/[PERIODID]_[PROGRAMIDENT].html
 */

$period = $periods[count($periods)-1];
$periodId = $period['pId'];
$programs = getPrograms($periodId);

foreach ($programs as $program)
{
  foreach ($program['years'] as $year)
  {
    $realUrl = $config['urlPrefix'].$year['href'];
    $programId = $year['programId'];
    
    echo "Fetching $realUrl...";
    $yearStr = file_get_contents($realUrl);
    if ($yearStr === false)
    {
      echo "\nGetting schedule for $programId ($realUrl) failed!";
      exit(1);
    } else {
      echo " OK!\n";
    }
    
    $fileName = "static/program/".$periodId."_".$programId.".html";
    $ret = file_put_contents($fileName, $yearStr);
    if ($ret === false)
    {
      echo "Writing schedule for $programId ($realUrl) failed!";
      exit(1);
    }
  }
}

?>