<?php
// Required to update
if ($_POST["new"] == "false" && empty($_POST["id"]))
{
	header('Location: edit.php?id=new&r=2');
	exit;
}
// Always required
if (empty($_POST["workspace"]) || empty($_POST["project"]) || empty($_POST["title"]) || empty($_POST["assignee"]))
{
	
	if ($_POST["new"])
		$id = "new";
	else
		$id = $_POST["id"];
	header('Location: edit.php?id='.$id.'&r=3');
	exit;
}

//description

define('IncludesAllowed', TRUE);
require_once("inc/db.php");

foreach(array_keys($_POST) as $key)
{
	$clean[$key] = htmlspecialchars(mysqli_real_escape_string($db, $_POST[$key]));
}

if ($_POST["new"] == "true")
	$sql = "INSERT INTO `Barcodes` (`Workspace`, `Project`, `Title`, `Description`, `Assignee`, `Followers`) VALUES ('".$clean["workspace"]."', '".$clean["project"]."', '".$clean["title"]."', '".$clean["description"]."', '".$clean["assignee"]."', '".$clean["followers"]."');";
else
	$sql = "UPDATE `Barcodes` SET `Workspace` = '".$clean["workspace"]."', `Project` = '".$clean["project"]."', `Title` = '".$clean["title"]."', `Description` = '".$clean["description"]."', `Assignee` = '".$clean["assignee"]."', `Followers` = '".$clean["followers"]."' WHERE `id` = ".$clean["id"].";";
	
//die ($sql);


if(!$result = $db->query($sql)){
	echo 'There was an error running the query [' . $db->error . ']';
}
else
{
	if (empty($_POST["id"]))
	{
		$sql = "SELECT `id` FROM `Barcodes` WHERE `title` = '".$clean["title"]."' ORDER BY `id` DESC LIMIT 1";

		if(!$result = $db->query($sql)){
			echo 'There was an error running the query [' . $db->error . ']';
		}
		else if (!$result->num_rows)
			echo "No tasks found!";
		else
		{
			while($row = $result->fetch_assoc())
			{
				$_POST["id"] = $row["id"];
			}
		}
	}

	header('Location: edit.php?id=' . $_POST["id"] . ($_POST["new"] == "true" ? "&r=1" : "&r=0"));
}
$db->close();
?>