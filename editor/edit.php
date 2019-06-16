<?php
define('IncludesAllowed', TRUE);	
require_once("inc/db.php");

if (!empty($_GET["id"]) && $_GET["id"] != "new")
{
	$id = intval($_GET["id"]);
	$sql = "SELECT * FROM `Barcodes` WHERE `id` = '$id' AND `disabled` = 0 LIMIT 1;";

	if(!$result = $db->query($sql)){
		die('There was an error running the query [' . $db->error . ']');
	}
}

$reply = array("Task updated!",
						"Task created!",
						"Invalid data!",
						"Project, Title, & Assignee are required!");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

	<title>QR Code Editor</title>
	<link href="css/pure-min.css" rel="stylesheet" type="text/css">
	<link href="css/styles.css" rel="stylesheet" type="text/css" />
	
	<script src="js/jquery-2.1.1.min.js" type="application/javascript"></script>
	<script src="js/shorten.js" type="application/javascript"></script>
	<script type="text/javascript">
		$(document).ready(function() {
			$("iframe").load(function() {
					var h = $(this).contents().find("button").outerHeight() + 10;
					$(this).height( h );
				});
		});
	</script>
</head>

<body>

<div class="container">
  <div class="content">
<?php
if ($_GET["id"] != "new" && (!$result || !mysqli_num_rows($result)))
	echo "Invalid task code specified.";
else if ($_GET["id"] == "new" || $row = $result->fetch_assoc())
{
?>
	<div>
		<?php 
		if ($_GET["id"] != "new")
		{
		?>
		<iframe id="buttonFrame" frameborder="0" src="https://levylabssl.org/api/barcodeToTask/2.0/?qr=<?php echo $row["id"]; ?>" style="width: 100%;"></iframe>
		<form class="pure-form pure-form-aligned buttonlink">
			<input id="url" readonly type="text" value="https://levylabssl.org/api/barcodeToTask/2.0/?qr=<?php echo $row["id"]; ?>">
		</form>
		
		<div class="spacer"></div>
		<?php 
		}
		?>
		
		<form class="pure-form pure-form-aligned" action="submit.php" method="post">
			<fieldset>
				<div class="pure-control-group">
					<label for="barcodeID">Barcode ID</label>
					<input name="id" id="barcodeID" class="pure-u-2-3" placeholder="Barcode ID" readonly type="text" value="<?php echo ($_GET["id"] != "new" ? $row["id"] : ''); ?>">
				</div>

				<div class="pure-control-group">
					<label for="workspace">Workspace</label>
					<select name="workspace" id="workspace" class="pure-u-1-5" style="margin-right: 21px;">
						<option value="914676468468" <?php echo ($_GET["id"] != "new" && $row["Workspace"] == "914676468468" ? "selected" : ''); ?>>LevyLab.org</option>
						<option value="11986188125965" <?php echo ($_GET["id"] != "new" && $row["Workspace"] == "11986188125965" ? "selected" : ''); ?>>PQI.org</option>
					</select>
				
					<label for="project">Project ID</label>
					<input name="project" id="project" class="pure-u-1-5" placeholder="Project ID" type="text" value="<?php echo ($_GET["id"] != "new" ? $row["Project"] : ''); ?>">
				</div>

				<div class="pure-control-group">
					<label for="title">Task Title</label>
					<input name="title" id="title" class="pure-u-2-3" placeholder="Task Title" type="text" value="<?php echo ($_GET["id"] != "new" ? $row["Title"] : ''); ?>">
				</div>

				<div class="pure-control-group">
					<label for="description">Task Description</label> 
					<textarea name="description" id="description" class="pure-u-2-3" placeholder="Task Description" style="height: 90px; resize: vertical;"><?php echo ($_GET["id"] != "new" ? $row["Description"] : ''); ?></textarea>
				</div>

				<div class="pure-control-group">
					<label for="assignee">Assignee</label>
					<input name="assignee" id="assignee" class="pure-u-2-3" placeholder="Assignee Email Address" type="email" value="<?php echo ($_GET["id"] != "new" ? $row["Assignee"] : ''); ?>">
				</div>

				<div class="pure-control-group">
					<label for="followers">Followers</label>
					<input name="followers" id="followers" class="pure-u-2-3" placeholder="Comma Separated Emails" type="text" value="<?php echo ($_GET["id"] != "new" ? $row["Followers"] : ''); ?>">
				</div>

				<div class="pure-controls">
					<a href="index.php" class="pure-button pure-button-primary" style="color: white; margin-left: 25px;">Back</a>
					<button class="pure-button pure-button-primary" type="submit"><?php echo ($_GET["id"] == "new" ? "Create" : "Update"); ?></button>
					<?php 
					if (isset($_GET["r"]) && !empty($reply[$_GET["r"]]))
					{
						echo '<span class="arrow_box">'.$reply[$_GET["r"]].'</span>';
					}
					?>
				</div>
			</fieldset>
			
			 <input name="new" type="hidden" value="<?php echo ($_GET["id"] == "new" ? "true" : "false"); ?>"> 
		</form>
	</div>
<?php
}

$db->close();
?>
	</div>
</div>
</body>
</html>
