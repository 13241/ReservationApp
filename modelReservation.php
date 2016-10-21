<?php
	class reservation
	{
		private $destination;
		private $place_number;
		private $insurance;
		private $list_persons;
		
		public function __construct($destination="", $place_number=0, $insurance=0, $list_persons=array())
		{
			$this->destination = $destination;
			$this->place_number = $place_number;
			$this->insurance = $insurance;
			$this->list_persons = $list_persons;
		}
		public function getDestination()
		{
			if($this->destination != "")
			{
				return $this->destination;
			}
			return null;
		}
		public function getPlaceNumber()
		{
			if($this->place_number > 0)
			{
				return $this->place_number;
			}
			return null;
		}
		public function getInsurance()
		{
			if($this->insurance == 0 || $this->insurance === 1)
			{
				return $this->insurance;
			}
			return 0;
		}
		public function getListPersons()
		{
			if(count($this->list_persons) > 0)
			{
				return $this->list_persons;
			}
			return null;
		}
		public function setDestination($destination)
		{
			$this->destination = $destination;
		}
		public function setPlaceNumber($place_number)
		{
			$this->place_number = $place_number;
		}
		public function setInsurance($insurance)
		{
			$this->insurance = $insurance;
		}
		public function addPerson($person)
		{
			$this->list_persons[] = $person;
		}
		public function unsetListPersons()
		{
			unset($this->list_persons);
			$this->list_persons = array();
		}
	}
?>