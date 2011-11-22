<?php
require_once "functions/simple_html_dom.php";
require_once "config.php";

/*
 * If you're reading this code, please don't try to make sense of it.
 * If you do make sense of it, congratulations.
 * If you're judging my code for not making sense:
 * - Does the intra.novia.fi code make sense?
 * I just love it when people but tables in their tables so they can have
 * tables in their tables.
 */
function getPrograms($periodId)
{
  //$periodsUrl = $config["programUrl"];
  //$periodsUrl = str_replace("[PERIOD]", $periodId, $periodsUrl);
  $fileName = "static/perioder/$periodId.html";
  if (!file_exists($fileName))
    die(">:(");
  $domDoc = file_get_html($fileName);
  //$domDoc = file_get_html($periodsUrl);

  $table = $domDoc->find('table tbody', 0);

  $trs = $table->find('tr');
  array_shift($trs); // First <tr> is not important
  array_shift($trs); // Second <tr> is Program Åk1 Åk2 Åk3 Åk4
  array_shift($trs); // Third time's the charm! (third sounds almost like turd :])


  $programs = array();
  for ($i = 0; gettype($trs[$i]) === "object"; $i+=2)
  {
  	$tr = $trs[$i];
  	$topTds = $tr->find('td');
	
  	$program = array(
  		'name' => $topTds[1]->innertext,
  		'years' => array()
  	);
	
  	$programsTr = $topTds[2]->find('tr', 0);
  	foreach ($programsTr->find('a') as $a)
  	{
  		$year = array(
  			"href" => $config['urlPrefix'].$a->href,
  			"innertext" => $a->innertext,
  			"programId" => $a->innertext
  		);
  		array_push($program['years'], $year);
  	}
  	
  	array_push($programs, $program);
  }
  
  return $programs;
}
?>