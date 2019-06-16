<?php
if (empty($_GET["qr"]))
	die("QR Code ID not specified");

define('IncludesAllowed', TRUE);	
require_once("inc/db.php");

$id = intval($_GET["qr"]);

$sql = "SELECT `Title` FROM `Barcodes` WHERE `id` = '$id' AND `disabled` = 0;";
if(!$result = $db->query($sql)){
	die('There was an error running the query [' . $db->error . ']');
}

if (!$result->num_rows)
	die("This QR code doesn't exist");

$title = "";
while($row = $result->fetch_assoc())
{
	$title = $row["Title"];
}

$db->close();
?>

<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Asana Button</title>
		<link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" />
		<script src="js/jquery-1.11.1.min.js" type="application/javascript"></script>
		<script src="js/modernizr.custom.js" type="application/javascript"></script>
		
		<script type="application/javascript">
			$( document ).ready(function()
			{
				$( "#order" ).click(function( event ) 
				{
					$("#order").text("Creating task...");
					$("#order").css("background", "none repeat scroll 0 0 purple");
					$("#order").css("pointer-events", "none");
					
					
					event.preventDefault();
					var url = "api.php?qr=<?php echo $id; ?>";
					
					$.get( url, function( data ) 
					{
						if (data == "true")
						{
							$("#order").css("background", "none repeat scroll 0 0 green");
							$("#order").css("pointer-events", "none");
							$("#order").text("Task created!");
							$("#order").off("click");
						}
						else
						{
							$("#order").css("background", "none repeat scroll 0 0 red");
							$("#order").css("pointer-events", "auto");
							$("#order").text("Failed!");
						}
					});
				});
			});
		</script>
		
	</head>
	<body>
		<div class="container">
			<button id="order" class="btn btn-3 btn-3e icon-arrow-right"><?php echo $title; ?></button>
		</div>
	</body>
</html>