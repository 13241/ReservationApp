<?php
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
	
	if(isset($_POST['abort_reservation']))
	{
		if(session_id())
		{
			session_destroy();
			$reservation = new reservation;
		}
		include_once "Reservation.php";
	}
	elseif(isset($_POST['submit_reservation']))
	{
		$valid_encoding = 0b0;
		if(isset($_POST['destination']) and !empty($_POST['destination']))
		{
			$reservation->setDestination($_POST['destination']);
			$valid_encoding += 0b1;
		}
		else
		{
			$reservation->setDestination("");
		}
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
			include_once "Detail.php";
		}
		else
		{
			$showErrorMessage = 1;
			include_once "Reservation.php";
		}
	}
	elseif(isset($_POST['return_to_reservation']))
	{
		include_once "Reservation.php";
	}
	elseif(isset($_POST['submit_detail']))
	{
		$valid_encoding = 0b0;
		$count = null;
		if(isset($_POST['name']) and isset($_POST['age']) and count($_POST['name']) == count($_POST['age']))
		{
			$count = count($_POST['age']);
			$reservation->unsetListPersons();
			for($i = 0; $i < $count; $i++)
			{
				$person = new person;
				if(isset($_POST['name'][$i]) and !empty($_POST['name'][$i]))
				{
					$person->setName($_POST['name'][$i]);
					$valid_encoding += 0b1 * pow(4, $i);
				}
				else
				{
					$person->setName("");
				}
				if(isset($_POST['age'][$i]) and ctype_digit($_POST['age'][$i]) and 
					intval($_POST['age'][$i]) > 0)
				{
					$person->setAge(intval($_POST['age'][$i]));
					$valid_encoding += 0b10 * pow(4, $i);
				}
				else
				{
					$person->setAge(0);
				}
				$reservation->addPerson($person);
			}
		}
		if(isset($count) and $valid_encoding == pow(4, $count)-1)
		{
			include_once "Validation.php";
		}
		else
		{
			$showErrorMessage = 1;
			include_once "Detail.php";
		}
	}
	elseif(isset($_POST['submit_validation']))
	{
		include_once "Confirmation.php";
		session_destroy();
	}
	elseif(isset($_POST['return_to_detail']))
	{
		include_once "Detail.php";
	}
	else
	{
		include_once "Reservation.php";
	}
	
	if(isset($_SESSION['reservation']))
	{
		$_SESSION['reservation'] = serialize($reservation);
	}
//problematique du dropdatabase => htmlentities() et html_entity_decode() quand les utiliser?
//tester le dropdatabase en réel sans la protection et ensuite avec.
//a quoi sert session_commit()
//vue et message Objet
?>