<?php
require "functions/getSchedule.php";
$periodId = (string)$_GET['periodId'];
$programId = (string)$_GET['programId'];
$lectures = getSchedule($periodId, $programId);

?>
<!DOCTYPE html> 
<html>
  <head> 
  <title><?= $periodId ?></title> 
  <meta name="viewport" content="width=device-width, initial-scale=1"> 
  <meta charset="UTF-8">
  <link rel="stylesheet" href="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css" />
  <script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
  <script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>
  <style type="text/css" media="screen">
    div.lecture-2 { height: 20px; }
    div.lecture-4 { height: 60px; }
    div.lecture-8 { height: 100px; }
    div.lecture {display:table-cell; vertical-align:middle; text-align:center;}
  </style>
</head> 
<body> 

<div data-role="page">
  <div data-role="header">
    <h1><?= $periodId ?> -&gt; <?= $programId ?></h1>
  </div><!-- /header -->

  <div data-role="content"> 
    <ul data-role="listview">
      <?php foreach($config['days'] as $dayIndex => $day): ?>      
        <li data-role="list-divider" role="heading"><?= $day ?></li>
        <?php 
        $hourPos = 0;
        foreach($lectures[$dayIndex] as $lectureIndex => $lecture): 
          $lectureLength = $lecture['length']/2;
          $startsAt = startTime($config['classHours'][$hourPos]);
          $endsAt = endTime($config['classHours'][$hourPos+$lectureLength-1]);
          $hourPos += $lectureLength;
          $lunch = false;
          if ($lecture['type'] == $config['lectureTypes']['lunch'])
            $lunch = true;
        ?>
          <?php if ($lunch): ?>
          <li data-theme="d">
            <center>
              <span><?= $startsAt ?> - <?= $endsAt ?><br /></span>
              <span>LUNCH</span>
            </center>
          </li>
          <?php else: ?>
          <li data-theme="c">
            <center>
            <div class="lecture-<?=$lecture['length']?> lecture">
              <span><?= $startsAt ?> - <?= $endsAt ?><br /></span>
              <?php foreach ($lecture['subject'] as $index => $subject): ?>
                <span><?= $lecture['subject'][$index] ?> (<?=$lecture['teachers'][$index]?> - <?=$lecture['rooms'][$index] ?>)<br /></span>
              <?php endforeach; ?>
            </div>
            </center>
          </li>
          <?php endif; ?>
        <?php endforeach; ?>
      <?php endforeach; ?>
    </ul>
  </div><!-- /content -->

</div><!-- /page -->

</body>
</html>