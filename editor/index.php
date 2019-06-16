<?php
define('IncludesAllowed', TRUE);
require_once("inc/db.php");

$sql = "SELECT * FROM `users` WHERE `disabled` = 0;";
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
if(!$result = $db->query($sql)){
	echo 'There was an error running the query [' . $db->error . ']';
}
else if (!$result->num_rows)
	echo "No users found!";
else
{
	while($row = $result->fetch_assoc())
	{
?>
				<li>
					<a href="edit.php?id=<?php echo $row["id"]; ?>" data-ajax="false">

						<h2><strong>User:</strong> <?php echo (!empty($row["displayName"]) ? $row["displayName"] : $row["id"]); ?></h2>
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
