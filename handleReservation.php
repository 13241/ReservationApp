<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	
	/* default connection parameters */
	$pdodb_name = "myReservationApp";
	$host = "localhost";
	$username = "root";
	$password = "";
	
	/* initialization and/or session recovery */
	if(session_status() == PHP_SESSION_NONE)
	{
		session_start();
	}
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
	
	/* views handling */
	$case = "";
	if(isset($_GET['case']))
	{
		$case = $_GET['case'];
	}
	switch($case)
	{
		//abort reservation : reset reservation
		case "abort_reservation":
			session_destroy();
			$reservation = new reservation;
			include_once "Reservation.php";
			break;
			
		//first step form (Reservation) : destination, number of places, insurance
		case "submit_reservation":
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
			break;
		
		//return to first step : Reservation
		case "return_to_reservation":
			include_once "Reservation.php";
			break;
		
		//second step form (Detail) : n * (name, age)
		case "submit_detail":
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
			break;
		
		//third step form (validation)
		case "submit_validation":
			rdu::connectPdodb($pdodb_name, $host, $username, $password);
			if(isset($_SESSION['no']))
			{
				//update reservation table
				rdu::updateReservation($_SESSION['no'], $reservation->getDestination(), $reservation->getPlaceNumber(),
					$reservation->getInsurance(), $reservation->getPrice());
				
				//alter people table
				$data_people = rdu::selectReservationPeople($_SESSION['no']);
				$previous_people_nc = array();
				$previous_people_na = array();
				$previous_people = array();
				//create specific array with people previously in table "people"
				foreach($data_people as $row)
				{
					$person = array(
						'name' => $row['name']
					);
					$previous_people_nc[intval($row['id'])] = serialize($person);
					$person['count'] = count(array_keys($previous_people_nc, serialize($person)));
					$previous_people_na[intval($row['id'])] = serialize($person);
					$person['age'] = intval($row['age']);
					$previous_people[intval($row['id'])] = $person;
				}
				
				$list_persons = $reservation->getListPersons();
				$current_people_nc = array();
				$current_people_na = array();
				$current_people = array();
				//create specific array with people currently in the reservation
				foreach($list_persons as $row)
				{
					$person = array(
						'name' => $row->getName()
					);
					$current_people_nc[] = serialize($person);
					$person['count'] = count(array_keys($current_people_nc, serialize($person)));
					$current_people_na[] = serialize($person);
					$person['age'] = $row->getAge();
					$current_people[] = $person;
				}
				
				//use of the specific arrays to determine which people are modified, added, deleted
				$modified_keys = array_keys(array_intersect($previous_people_na, $current_people_na));
				$modified_tkeys = array_keys(array_intersect($current_people_na, $previous_people_na));
				$added_keys = array_keys(array_diff($current_people_na, $previous_people_na));
				$deleted_keys = array_keys(array_diff($previous_people_na, $current_people_na));
				
				//delete unused people from table "people"
				foreach($deleted_keys as $key)
				{
					rdu::removePeople($key);
				}
				
				//add new people in table "people"
				foreach($added_keys as $key)
				{
					rdu::addPeople($_SESSION['no'], $current_people[$key]['name'], $current_people[$key]['age']);
				}
				
				//update table "people" for each existing id reused
				foreach($modified_keys as $key)
				{
					foreach($modified_tkeys as $tkey)
					{
						if($previous_people_na[$key] == $current_people_na[$tkey])
						{
							$previous_people[$key]['age'] = $current_people[$tkey]['age'];
							$modified_tkeys = array_diff($modified_tkeys, array($tkey));
							break;
						}
					}
					rdu::updatePeople($key, $previous_people[$key]['name'], $previous_people[$key]['age']);
				}
				//go to database management
				$included = "handleDatabase.php";
			}
			else
			{
				//insert into reservation table
				rdu::addReservation($reservation->getDestination(), $reservation->getPlaceNumber(),
					$reservation->getInsurance(), $reservation->getPrice(), $reservation->getListPersons());
					
				//go to fourth step, terminate session
				$included = "Confirmation.php";
			}
			//go to fourth step, terminate session
			include_once $included;
			session_destroy();
			break;
		
		//return to second step (detail)
		case "return_to_detail":
			include_once "Detail.php";
			break;
		
		//default
		default:
			//go to first step
			include_once "Reservation.php";
	}
	
	/* save object reservation in session */
	if(isset($_SESSION['reservation']))
	{
		$_SESSION['reservation'] = serialize($reservation);
	}
?>