<?php
	/**
	* represents a person with its name and age
	*/
	class person
	{
		/* attributes */
		private $name;
		private $age;
		
		/**
		* constructor
		* @param str name : name of the person
		* @param int age : age of the person
		*/
		public function __construct($name="", $age=0)
		{
			$this->name = $name;
			$this->age = $age;
		}
		
		/**
		* get the value from attribute name, or null if not string || empty
		* @return str name || null
		*/
		public function getName()
		{
			if($this->name != "")
			{
				return $this->name;
			}
			return null;
		}
		
		/**
		* get the value from attribute age, or null if not int || empty
		* @return int age || null
		*/
		public function getAge()
		{
			if($this->age > 0)
			{
				return $this->age;
			}
			return null;
		}
		
		/**
		* set the value of attribute name
		* @param name : name of the person
		* @return void
		*/
		public function setName($name)
		{
			$this->name = $name;
		}
		
		/**
		* set the value of attribute age
		* @param age: age of the person
		* @return void
		*/
		public function setAge($age)
		{
			$this->age = $age;
		}
	}
?>