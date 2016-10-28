<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	
	/* initialization and/or session recovery */
	include_once "modelReservation.php";
	include_once "modelPerson.php";
	session_start();
	$showErrorMessage = 0;
	if(isset($_SESSION['reservation']))
	{
		$reservation = unserialize($_SESSION['reservation']);
	}
	else
	{
		$reservation = new reservation;
		$_SESSION['reservation'] = serialize($reservation);
	}
	
	/* views handlings */
	//abort reservation : reset reservation
	if(isset($_POST['abort_reservation']))
	{
		if(session_id())
		{
			session_destroy();
			$reservation = new reservation;
		}
		include_once "Reservation.php";
	}
	//first step form (Reservation) : destination, number of places, insurance
	elseif(isset($_POST['submit_reservation']))
	{
		//2 bits validity check
		$valid_encoding = 0b0;
		
		//validity check for input destination (bit index 0)
		if(isset($_POST['destination']) and !empty($_POST['destination']))
		{
			$reservation->setDestination($_POST['destination']);
			$valid_encoding += 0b1;
		}
		else
		{
			$reservation->setDestination("");
		}
		//validity check for input number of places (bit index 1)
		if(isset($_POST['place_number']) and ctype_digit($_POST['place_number']) and
			intval($_POST['place_number']) > 0 and intval($_POST['place_number']) < 10)
		{
			$reservation->setPlaceNumber(intval($_POST['place_number']));
			$valid_encoding += 0b10;
		}
		else
		{
			$reservation->setPlaceNumber(0);
		}
		$reservation->setInsurance((isset($_POST['insurance'])) ? 1 : 0);
		if($valid_encoding == 0b11)
		{
			//go to second step
			include_once "Detail.php";
		}
		else
		{
			$showErrorMessage = 1;
			include_once "Reservation.php";
		}
	}
	//return to first step : Reservation
	elseif(isset($_POST['return_to_reservation']))
	{
		include_once "Reservation.php";
	}
	//second step form (Detail) : n * (name, age)
	elseif(isset($_POST['submit_detail']))
	{
		//2 * n bits validity check
		$valid_encoding = 0b0;
		
		//n : number of desired persons
		$count = null;
		
		if(isset($_POST['name']) and isset($_POST['age']) and count($_POST['name']) == count($_POST['age']))
		{
			$count = count($_POST['age']);
			
			//reset persons in reservation
			$reservation->unsetListPersons();
			
			//for each person i
			for($i = 0; $i < $count; $i++)
			{
				$person = new person;
				
				//validity check for input name (bit index 2 * i)
				if(isset($_POST['name'][$i]) and !empty($_POST['name'][$i]))
				{
					$person->setName($_POST['name'][$i]);
					$valid_encoding += 0b1 * pow(0b100, $i);
				}
				else
				{
					$person->setName("");
				}
				//validity check for input age (bit index 2 * i + 1)
				if(isset($_POST['age'][$i]) and ctype_digit($_POST['age'][$i]) and 
					intval($_POST['age'][$i]) > 0)
				{
					$person->setAge(intval($_POST['age'][$i]));
					$valid_encoding += 0b10 * pow(0b100, $i);
				}
				else
				{
					$person->setAge(0);
				}
				$reservation->addPerson($person);
			}
		}
		if(isset($count) and $valid_encoding == pow(0b100, $count)-1)
		{
			//go to third step
			include_once "Validation.php";
		}
		else
		{
			$showErrorMessage = 1;
			include_once "Detail.php";
		}
	}
	//third step form (validation)
	elseif(isset($_POST['submit_validation']))
	{
		//go to fourth step, terminate session
		include_once "Confirmation.php";
		session_destroy();
	}
	//return to second step (detail)
	elseif(isset($_POST['return_to_detail']))
	{
		include_once "Detail.php";
	}
	//default
	else
	{
		//go to first step
		include_once "Reservation.php";
	}
	
	/* save object reservation in session */
	if(isset($_SESSION['reservation']))
	{
		$_SESSION['reservation'] = serialize($reservation);
	}
//problematique du dropdatabase => htmlentities() et html_entity_decode() quand les utiliser?
//tester le dropdatabase en réel sans la protection et ensuite avec.
//a quoi sert session_commit()
//vue et message Objet
?>