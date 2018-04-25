<?php
	// Model-View-Controller implementation of Task Manager
	
	require('GolfController.php');

	$controller = new GolfController();
	$controller->run();
?>