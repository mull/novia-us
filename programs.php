<?php 
  $periodId = (string)$_GET['periodId'];
	require "functions/getPrograms.php";
	$programs = getPrograms($periodId);
?>
<meta charset="UTF-8">
<!DOCTYPE html> 
<html> 
	<head> 
	<title>Undervisningsschema</title> 
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<link rel="stylesheet" href="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.css" />
	<script type="text/javascript" src="http://code.jquery.com/jquery-1.6.4.min.js"></script>
	<script type="text/javascript" src="http://code.jquery.com/mobile/1.0/jquery.mobile-1.0.min.js"></script>
</head> 
<body> 

<div data-role="page">
	<div data-role="header">
		<h1>Välj Program</h1>
	</div><!-- /header -->

	<div data-role="content">	
		<ul data-role="listview" data-inset="true">
			<?php foreach ($programs as $program): ?>
			<li>
				<span><?=$program['name']?></span>
				<ul data-role="listview" data-inset="true">
					<?php foreach ($program['years'] as $year): ?>
					<li>
						<a href="./viewprogram.php?periodId=<?=$periodId?>&programId=<?=$year['programId']?>">
							<?= $year['innertext'] ?>
						</a>
					</li>
					<?php endforeach; ?>
				</ul>
			</li>
			<?php endforeach; ?>
		</ul>	
	</div><!-- /content -->

</div><!-- /page -->

</body>
</html>