<?php
require_once "config.php";
require_once "functions/getPeriods.php";
require_once "functions/getPrograms.php";
require_once "functions/getSchedule.php";

$neededDirs = array(
  'static',
  'static/perioder',
  'static/program',
);

foreach ($neededDirs as $dir)
{
  if (!is_dir($dir))
  {
    echo "Creating dir \"$dir\"... ";
    if (mkdir($dir))
      echo "OK!";
    else
      echo "FAILED!";
      
    echo "\n";
  }
}

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


echo "Getting periods...\n";
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

echo "Getting periods done.\n";

/*
 * Get each program's schedule for the LAST TWO PERIODS ONLY!
 * Otherwise this script will take forever :]
 * static/program/[PERIODID]_[PROGRAMIDENT].html
 */

echo "Getting program schedules...\n";

$periodIndex = count($periods)-2;
for (; $periodIndex < count($periods); $periodIndex++)
{
  $period = $periods[$periodIndex];
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
}

echo "Getting program schedules finished.\n";

?>