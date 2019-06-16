<?php
if (empty($_GET["qr"]))
	die("false");

define('IncludesAllowed', TRUE);
require_once("inc/db.php");

$id = intval($_GET["qr"]);

$sql = "SELECT * FROM `Barcodes` WHERE `id` = '$id' AND `disabled` = 0;";
if(!$result = $db->query($sql)){
	die('There was an error running the query [' . $db->error . ']');
}

if (!$result->num_rows)
	die("This QR code doesn't exist");

$rows = [];
while($row = $result->fetch_assoc())
{
	$rows[] = $row;
}

$db->close();

require_once('inc/asana.php');

$asana = new Asana([
	'personalAccessToken' => "yesgood",
]);
$error = "";

//$asana->advDebug = true;
//$asana->debug = true;

foreach ($rows as &$row)
{
	$followers = array();
	$followers[] = $row["Assignee"];
	$followers = array_merge($followers, str_getcsv($row["Followers"]));
	$followers = array_filter($followers);

	$followerIDs = array();
	if (!empty($followers))
	{
		foreach ($followers as &$user)
		{
			//print " ".$user;
			$result = $asana->getUserInfo(trim($user));

			if ($asana->hasError())
				$error .= "<br />Error getting user ID for: " . $user . " Code: " . $asana->responseCode;

			$resultJson = json_decode($result);
			$followerIDs[] = $resultJson->data->id; // Userid
		}
	}
	$result = $asana->createTask(array(
		'workspace' =>  $row["Workspace"],
		'name' =>  html_entity_decode($row["Title"]), // Name of task
		"html_notes" => "<body>" . html_entity_decode($row["Description"]) . "</body>",
		'assignee' => $row["Assignee"] // Assign task ...
		//'projects' => array($row["Project"]), //Specifiying a project here no longer works
		//'due_on' => date("Y-m-d"),
		//'followers' => $followers //moved below
	));

	$taskId = $asana->getData()->id; // Get the id of the new task

	// Task created? If so, add to project
	if ($asana->hasError())
	{
		$error .= "<br />Error while creating task, response code: " . $asana->responseCode;
	}
	else
		$asana->addProjectToTask($taskId, $row["Project"]);  //Add task to project

	//Project added? If so, add followers
	if ($asana->hasError())
		$error .= "<br />Error adding task to project, response code: " . $asana->responseCode;
	else if (!empty($followerIDs))
		$asana->addFollowersToTask($taskId, $followerIDs);  //Add followers

	if ($asana->hasError())
		$error .= "<br />Error while adding followers, response code: " . $asana->responseCode;

	//$error .= implode(', ', $followerIDs);
}

if (empty($error))
	echo "true";
else
	echo "false".$error;
//print_r($followers);
//print_r($followerIDs);
?>
