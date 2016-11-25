<?php
	/**
	* @author Abeloos Damien 13241
	*/
	session_start();
	if(!empty($_GET['handler']) && is_file("handle".$_GET['handler']."php"))
	{
		include "handle".$_GET['handler']."php";
	}
	else
	{
		include "handleReservation.php";
	}
?>