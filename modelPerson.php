<?php
	class person
	{
		private $name;
		private $age;
		
		public function __construct($name="", $age=0)
		{
			$this->name = $name;
			$this->age = $age;
		}
		public function getName()
		{
			if($this->name != "")
			{
				return $this->name;
			}
			return null;
		}
		public function getAge()
		{
			if($this->age > 0)
			{
				return $this->age;
			}
			return null;
		}
		public function setName($name)
		{
			$this->name = $name;
		}
		public function setAge($age)
		{
			$this->age = $age;
		}
	}
?>