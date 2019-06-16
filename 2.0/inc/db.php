<?php
if(!defined('IncludesAllowed'))
	die('Direct access not permitted');

header('Content-Type: text/html; charset=utf-8');

$database=" ";
$user=" ";
$password=" ";
$host="localhost";

ini_set("date.timezone", "US/Central");

$db = new mysqli($host, $user, $password, $database);
if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}
