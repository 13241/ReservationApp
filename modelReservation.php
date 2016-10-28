<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	
	/**
	* represents a reservation with each of its parameters
	*/
	class reservation
	{
		/* attributes */
		private $destination;
		private $place_number;
		private $insurance;
		private $list_persons;
		
		/**
		* constructor
		* @param str destination name of destination
		* @param int place_number : number of persons for this reservation
		* @param bool insurance : if the user wants an insurance
		* @param array(person) : list_persons contains objects representing a person concerned with this reservation
		*/
		public function __construct($destination="", $place_number=0, $insurance=0, $list_persons=array())
		{
			$this->destination = $destination;
			$this->place_number = $place_number;
			$this->insurance = $insurance;
			$this->list_persons = $list_persons;
		}
		
		/**
		* get the value from attribute destination, or null if not string || empty
		* @return str destination || null
		*/
		public function getDestination()
		{
			if($this->destination != "")
			{
				return $this->destination;
			}
			return null;
		}
		
		/**
		* get the value from attribute place_number, or null if not numeric || empty || negative
		* @return int place_number || null
		*/
		public function getPlaceNumber()
		{
			if($this->place_number > 0)
			{
				return $this->place_number;
			}
			return null;
		}
		
		/**
		* get the value from attribute insurance, or false if not boolean
		* @return bool insurance || false
		*/
		public function getInsurance()
		{
			if($this->insurance == 0 || $this->insurance == 1)
			{
				return $this->insurance;
			}
			return 0;
		}
		
		/**
		* get the pointer for attribute list_persons, or null if empty
		* @return array(person) list_persons || null
		*/
		public function getListPersons()
		{
			if(count($this->list_persons) > 0)
			{
				return $this->list_persons;
			}
			return null;
		}
		
		/**
		* set the value of attribute destination
		* @param str destination : name of destination
		* @return void
		*/
		public function setDestination($destination)
		{
			$this->destination = $destination;
		}
		
		/**
		* set the value of attribute place_number
		* @param int place_number : number of persons for this reservation
		* @return void
		*/
		public function setPlaceNumber($place_number)
		{
			$this->place_number = $place_number;
		}
		
		/**
		* set the value of attribute insurance
		* @param bool insurance : if the user wants an insurance
		* @return void
		*/
		public function setInsurance($insurance)
		{
			$this->insurance = $insurance;
		}
		
		/**
		* add a person into attribute list_persons
		* @param person person : a person concerned with this reservation
		* @return void
		*/
		public function addPerson($person)
		{
			$this->list_persons[] = $person;
		}
		
		/**
		* empty attribute list_persons
		* @return void
		*/
		public function unsetListPersons()
		{
			unset($this->list_persons);
			$this->list_persons = array();
		}
	}
?>