<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	include_once "modelReservation.php";
	include_once "modelPerson.php";
	include_once "modelDatabase.php";
	class_alias("reservationDbUtility", "rdu");
	
	if(!empty($_GET['handler']) && is_file("handle".$_GET['handler'].".php"))
	{
		include "handle".$_GET['handler'].".php";
	}
	else
	{
		include "handleReservation.php";
	}
?>