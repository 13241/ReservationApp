<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	
	include_once "modelReservation.php";
	include_once "modelPerson.php";
	include_once "modelDatabase.php";
	
	/* default connection parameters */
	class_alias("reservationDbUtility", "rdu");
	$pdodb_name = "myReservationApp";
	$host = "localhost";
	$username = "";
	$password = "";
	
	try
	{
		//create database & connection to it
		$pdo = rdu::connectPdodb($pdodb_name, $host, $username, $password);
		
		/* views handling */
		$case = "";
		if(isset($_GET['case']))
		{
			$case = $_GET['case'];
		}
		switch($case)
		{
			//remove reservation with primary key "no"
			case "remove_reservation":
				rdu::removeReservation($_POST['no']);
				include_once "Administration.php";
				break;
				
			//switch to handleReservation to create a new reservation
			case "add_reservation":
				include_once "router.php?handler=Reservation";
				break;
				
			//create a completed session with the data for the reservation to edit
			//Stock the primary key of the reservation in the session
			case "edit_reservation":
				session_start();
				$reservation = new reservation;
				$_SESSION['no'] = $_POST['no'];
				$data_reservation = rdu::selectReservation($_POST['no']);
				
				$_SESSION['reservation'] = serialize($reservation);
				break;
			
			//upon clicking on a reservation row, display a new board containing the people in this reservation
			case "select_reservation":
				
				break;
			default:
				include_once "Administration.php";
		}
	}
	catch(Exception $e)
	{
		die('Erreur : '$e->getMessage());
	}
?>












