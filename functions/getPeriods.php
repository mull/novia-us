<?php
require_once "functions/simple_html_dom.php";
require_once "config.php";

function getPeriods()
{
  $domDoc = file_get_html("static/perioder.html");
    
  $artContent = $domDoc->find("div.article-content", 0);
  $td = $artContent->find("table tbody td", 0);

  $content = $td->children(1);
  $header = explode("\n", $content->plaintext);
  $header = $header[0];

  $periods = array();
  $i=1;
  foreach ($content->find('a') as $period)
  {
  	$curPeriod = array(
  		'text' => $period->innertext,
  		'link' => $config['urlPrefix'].$period->href,
  		'pId' => "P$i"
  	);
  	array_push($periods, $curPeriod);
  	$i++;
  }
  return $periods;
}
?>