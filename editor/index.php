<?php
define('IncludesAllowed', TRUE);
require_once("inc/db.php");

$sql = "SELECT * FROM `users` WHERE `disabled` = 0;";
$payments = "SELECT `user`, `type`, sum(`amount`)  FROM `payments` WHERE `date` BETWEEN '" . date('Y-m-01') . "' AND '" . date('Y-m-t') . "' GROUP BY `user`, `type`"
?>
<!DOCTYPE html>
<html>
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="width=device-width, initial-scale=1" name="viewport">

	<title>
		Payment Portal
	</title>
	<link href="css/pure-min.css" rel="stylesheet" type="text/css">
	<link href="css/jquery.mobile-1.4.4.css" rel="stylesheet" type="text/css">
	<link href="css/styles.css" rel="stylesheet" type="text/css">
	<script src="js/jquery-2.1.1.min.js" type="application/javascript"></script>
	<script src="js/jquery.mobile-1.4.4.min.js" type="application/javascript"></script>
</head>

<body>
	<div class="container">
		<div class="content">
			<ul data-inset="true" data-role="listview">
				<li data-role="list-divider">Users</li>
<?php

$totals = array();

if(!$result = $db->query($sql)){
	echo 'There was an error running the query [' . $db->error . ']';
}
else if (!$result->num_rows)
	echo "No users found!";
else
{
	if(!$paid = $db->query($payments)){
		echo 'There was an error running the query [' . $db->error . ']';
	}
	else
	{
		//echo '<pre>';
		while($payment = $paid->fetch_assoc())
		{
			$totals[$payment["user"]][$payment["type"]] = $payment["sum(`amount`)"];
			//totals[user][service] = amount;
		}
		//print_r($totals);
		//echo '</pre>';
	}
	while($row = $result->fetch_assoc())
	{
?>
				<li>
					<a href="edit.php?id=<?php echo $row["id"]; ?>" data-ajax="false">

						<h2><strong>User:</strong> <?php echo (!empty($row["displayName"]) ? $row["displayName"] : '#'.$row["id"]); ?></h2>

						<div class="section details">
							<strong>Totals</strong>
							<p><strong>Rent:</strong> <?php echo (!empty($row["rentAmount"]) ? "$".number_format($row["rentAmount"], 2) : 'None'); ?></p>
							<p><strong>Power:</strong> <?php echo (!empty($row["powerAmount"]) ? "$".number_format($row["powerAmount"], 2) : 'TBD'); ?></p>
							<p><strong>Internet:</strong> <?php echo (!empty($row["internetAmount"]) ? "$".number_format($row["internetAmount"], 2) : 'None'); ?></p>
						</div>

						<?php
							$difference = (float)$row["rentAmount"] - (float)$totals[$row["id"]]["rent"];
						?>

						<div class="section payment rent">
							<p><strong>Rent Due:</strong> <?php echo "$".number_format($difference, 2); ?></p>
						</div>

						<?php
							$difference = (float)$row["powerAmount"] - (float)$totals[$row["id"]]["power"];
						?>

						<div class="section payment power">
							<p><strong>Power Due:</strong> <?php echo "$".number_format($difference, 2); ?></p>
						</div>

						<?php
							$difference = (float)$row["internetAmount"] - (float)$totals[$row["id"]]["internet"];
						?>

						<div class="section payment internet <?php echo ($difference <= 0 ? 'paid' : ''); ?>">
							<p><?php echo ($difference <= 0 ? 'Internet paid' : "<strong>Internet Due:</strong> $".number_format($difference, 2) ); ?></p>
						</div>


						<p class="ui-li-aside"><strong>ID: <?php echo $row["id"]; ?></strong></p>
					</a>
				</li>
<?php
	}
}
$db->close();
?>
			</ul>
		</div><!-- end .container -->
	</div>
</body>
</html>
