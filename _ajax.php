<?php

session_start();

require_once(__DIR__ . "/config.php");
require_once(__DIR__ . "/functions.php");
require_once(__DIR__ . "/Todo.php");

$todoApp = new \MyApp\Todo();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
	try {
		$res = $todoApp->post();
		header("Content-type: application/json");
		echo json_encode($res);
		exit;
	} catch (Execption $e){
		header($_SERVER["SERVER_PROTOCOL"] . "500 Internal Server Error", True, 500);
		echo $e->getMessage();
		exit;
	}
}

?>