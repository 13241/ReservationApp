<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	
	/* default connection parameters */
	$pdodb_name = "myReservationApp";
	$host = "localhost";
	$username = "root";
	$password = "";
	
	try
	{
		//create database & connection to it
		rdu::connectPdodb($pdodb_name, $host, $username, $password);
		
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
				include_once "handleReservation.php";
				break;
				
			//create a completed session with the data for the reservation to edit
			//Stock the primary key of the reservation in the session
			case "edit_reservation":
				if(session_status() == PHP_SESSION_NONE)
				{
					session_start();
				}
				$reservation = new reservation;
				$_SESSION['no'] = $_POST['no'];
				$data_reservation = rdu::selectReservation($_POST['no']);
				//this loop only has one element, always
				foreach($data_reservation as $row)
				{
					$reservation->setDestination($row['destination']);
					$reservation->setPlaceNumber($row['place_number']);
					$reservation->setInsurance($row['insurance']);
					$data_people = rdu::selectReservationPeople($_POST['no']);
					foreach($data_people as $srow)
					{
						$person = new person;
						$person->setName($srow['name']);
						$person->setAge($srow['age']);
						$reservation->addPerson($person);
					}
				}
				$_SESSION['reservation'] = serialize($reservation);
				include_once "handleReservation.php";
				break;
			//display data from the database
			default:
				include_once "Administration.php";
		}
	}
	catch(Exception $e)
	{
		die('Erreur : '.$e->getMessage());
	}
?>












