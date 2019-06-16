<?php
define('IncludesAllowed', TRUE);
require_once("inc/db.php");

$sql = "SELECT * FROM `Barcodes` WHERE `disabled` = 0;";
?>
<!DOCTYPE html>
<html>
<head>
	<meta content="text/html; charset=utf-8" http-equiv="Content-Type">
	<meta content="width=device-width, initial-scale=1" name="viewport">

	<title>QR Code Editor</title>
	<link href="css/pure-min.css" rel="stylesheet" type="text/css">
	<link href="css/jquery.mobile-1.4.4.css" rel="stylesheet" type="text/css">
	<link href="css/styles.css" rel="stylesheet" type="text/css">
	<script src="js/jquery-2.1.1.min.js" type="application/javascript"></script>
	<script src="js/jquery.mobile-1.4.4.min.js" type="application/javascript"></script>
</head>

<body>
	<div class="container">
		<div class="content">
			<ul data-inset="true" data-role="listview" style="margin-bottom: 20px;">
				<li data-role="list-divider">Create New Task</li>
				<li>
					<a href="edit.php?id=new" data-ajax="false">
						<h2>Create a New Task</h2>

						<p>Create a new barcode button</p>
					</a>
				</li>
			</ul>
			<ul data-inset="true" data-role="listview">
				<li data-role="list-divider">Enabled Barcodes <!--span class="ui-li-count">2</span--></li>
<?php
if(!$result = $db->query($sql)){
	echo 'There was an error running the query [' . $db->error . ']';
}
else if (!$result->num_rows)
	echo "No tasks found!";
else
{
	while($row = $result->fetch_assoc())
	{
		$row["Followers"] = str_replace ( ",", ", ", $row["Followers"])
?>
				<li>
					<a href="edit.php?id=<?php echo $row["id"]; ?>" data-ajax="false">
						<h2><?php echo $row["Title"]; ?></h2>

						<p><strong>Assigned to:</strong> <?php echo (!empty($row["Assignee"]) ? $row["Assignee"] : 'Unassigned'); ?></p>
						<p><strong>Description:</strong> <?php echo (!empty($row["Description"]) ? $row["Description"] : 'None'); ?></p>
						<p><strong>Followers:</strong> <?php echo (!empty($row["Followers"]) ? $row["Followers"] : 'None'); ?></p>
						<p class="ui-li-aside"><strong>TaskID: <?php echo $row["id"]; ?></strong></p>
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