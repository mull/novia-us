<?php
$config = array(
	'periodsUrl'  =>	"https://intra.novia.fi/index.php?option=com_content&view=article&id=102&Itemid=511&lang=swe",
	'programUrl'  => 	"https://intra.novia.fi/lasordningar/Vasa/TEKNIK/brando_2011_[PERIOD].html",
	'scheduleUrl' =>  "https://intra.novia.fi/lasordningar/vasa/teknik/2011_[PERIOD]/Klasser_[PROGRAM].htm",
	'urlPrefix'   =>  "https://intra.novia.fi/",
	
	'days'        => 	array(
					            'Måndag', 'Tisdag', 'Onsdag', 'Torsdag', 'Fredag'
				            ),
	
	'classHours' => array(
	                    '8.00 - 8.45', '9.00 - 9.45', '10.00 - 10.45', '11.00 - 11.45', '11.45 - 12.30',
	                    '12.30 - 13.15', '13.30 - 14.15', '14.30 - 15.15', '15.30 - 16.15', '16.40 - 17.25',
	                    '17.35 - 18.20', '18.30 - 19.15', '19.25 - 20.10'
	                  ),
	                
	'lectureTypes' => array(
	                    'lecture' => 0, 'lunch' => 1, 'free' => 2
	                  )
);

function isCli() { 
  if (php_sapi_name() == 'cli' && empty($_SERVER['REMOTE_ADDR'])) 
    return true;
  else
    return false;
}

$debug = isCli();

function startTime($str)
{
  $arr = explode(" - ", $str);
  return $arr[0];
}

function endTime($str)
{
  $arr = explode(" - ", $str);
  return $arr[1];
}
?>