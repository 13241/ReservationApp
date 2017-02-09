<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	/* inclusions */
	include_once "modelReservation.php";
	include_once "modelPerson.php";
	include_once "modelDatabase.php";
	class_alias("reservationDbUtility", "rdu");
	
	/* default connection parameters */
	$pdodb_name = "myReservationApp";
	$host = "localhost";
	$username = "root";
	$password = "";
	
	/**
	* router : redirects to the different controllers
	*/
	
	if(!empty($_GET['handler']) && is_file("handle".$_GET['handler'].".php"))
	{
		include "handle".$_GET['handler'].".php";
	}
	else
	{
		include "handleReservation.php";
	}
?>