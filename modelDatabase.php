<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	
	/**
	* provide tools to use a reservation database
	*/
	class reservationDbUtility
	{
		/* current pdo connection informations */
		private static $pdo = NULL;
		private static $cur_host = NULL;
		private static $cur_pdodb_name = NULL;
		private static $cur_username = NULL;
		private static $cur_password = NULL;
		
		/**
		* establish and return a pdo connection and create a database if necessary
		* the database is set as used by default
		* if no parameter is provided, use the previous database, or create database "default" if none exists
		* @param str pdodb_name : name of the database idssued by the php data object
		* @param str host : hostname of the server hosting the database
		* @param str username : username (connecting the database)
		* @param str password : password (for the user "username" to connect the database)
		* @return PDO pdo : connection between php and database server)
		*/
		public static function connectPdodb($pdodb_name="default", $host="localhost", $username="", $password="")
		{
			try
			{
				if(self::$pdo == NULL or $pdodb_name != "default")
				{
					self::$cur_host = $host;
					self::$cur_pdodb_name = $pdodb_name;
					self::$cur_username = $username;
					self::$cur_password = $password;
					self::$pdo = new PDO("mysql:host = ".self::$cur_host, self::$cur_username, self::$cur_password);
					self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					self::$pdo->exec("CREATE DATABASE IF NOT EXISTS ".self::$cur_pdodb_name);
					self::$pdo->exec("USE ".self::$cur_pdodb_name);
					self::createReservationTable();
					self::createPeopleTable();
				}
				self::$pdo->exec("USE ".self::$cur_pdodb_name);
				return self::$pdo;
			}
			catch(Exception $e)
			{
				die('Erreur : '.$e->getMessage());
				return NULL;
			}
		}
		
		/**
		* simple mysql statement to create a table for multiple reservations
		* @return void
		*/
		private static function createReservationTable()
		{
			self::$pdo->exec(
				"CREATE TABLE IF NOT EXISTS reservations(
					no INT NOT NULL AUTO_INCREMENT,
					destination varchar(255) NOT NULL,
					place_number INT(3) NOT NULL,
					insurance TINYINT(1) NOT NULL DEFAULT 0,
					price INT(4) NOT NULL,
					PRIMARY KEY (no)
				) ENGINE = INNODB;"
			);
		}
		
		/**
		* simple mysql statement to create a table for multiple people (people in this table cannot exist without a reservation)
		* @return void
		*/
		private static function createPeopleTable()
		{
			self::$pdo->exec(
				"CREATE TABLE IF NOT EXISTS people(
					id INT NOT NULL AUTO_INCREMENT,
					name VARCHAR(255) NOT NULL,
					age INT(3) NOT NULL,
					reservation_no INT NOT NULL,
					PRIMARY KEY (id),
					FOREIGN KEY (reservation_no)
						REFERENCES reservations(no)
						ON UPDATE CASCADE
						ON DELETE CASCADE
				) ENGINE = INNODB;"
			);
		}
		
		/**
		* insert values in tables "reservations" and "people"
		* @return void
		*/
		public static function addReservation($destination, $place_number, $insurance, $price, $list_persons)
		{
			//insert data into "reservations"
			$statement = self::$pdo->prepare(
				"INSERT INTO reservations(no, destination, place_number, insurance, price)
					VALUES (NULL, :destination, :place_number, :insurance, :price);"
			);
			$statement->execute(array(
				'destination' => $destination,
				'place_number' => $place_number,
				'insurance' => $insurance,
				'price' => $price
			));
			
			//get the primary key from the last reservation
			$reservation_no = self::$pdo->query("SELECT LAST_INSERT_ID();");
			
			//insert data into "people" for this reservation
			$statement = self::$pdo->prepare(
				"INSERT INTO people(id, name, age, reservation_no)
					VALUES :people;"
			);
			$people = "";
			for($i = 0; $i < count($list_persons); $i++)
			{
				$people .= "(NULL,'".$list_persons[$i]->getName()."',".$list_persons[$i]->getAge().",".$reservation_no.")";
			}
			$statement->execute(array('people' => $people));
		}
		
		/**
		* delete the reservation with primary key "no"
		* @param int no : primary key of the desired reservation
		* @return void
		*/
		public static function removeReservation($no)
		{
			self::$pdo->exec("DELETE FROM reservations WHERE no = $no;") or exit(mysql_error());
		}
		
		/**
		* get data from reservations with primary key "no" 
		* or get all data from reservations
		* @param int no : primary key of the desired reservation
		* @return PDOStatement object
		*/
		public static function selectReservation($no=0)
		{
			if($no != 0)
			{
				return self::$pdo->query("SELECT destination, place_number, insurance, price FROM reservations WHERE no = $no LIMIT 1;");	
			}
			else
			{
				return self::$pdo->query("SELECT * FROM reservations;");
			}
		}
		
		/**
		* get data from people with foreign key "reservation_no"
		* @param int no : foreign key of the desired people (corresponding to the reservation "no")
		* @return PDOStatement object
		*/
		public static function selectReservationPeople($reservation_no=0)
		{
			if($reservation_no != 0)
			{
				return self::$pdo->query("SELECT id, name, age FROM people WHERE reservation_no = $reservation_no;");
			}
			else
			{
				return self::$pdo->query("SELECT * FROM people;");
			}
		}
		
		/**
		* update a row in reservations with primary key "no"
		* @param int no : primary key of the desired reservation
		* @param str destination : name of the destination
		* @param int place_number : nombre de participants
		* @param bool insurance : assurance annulation
		* @param int price : cost of the reservation
		* @return void
		*/
		public static function updateReservation($no, $destination, $place_number, $insurance, $price)
		{
			$statement = self::$pdo->prepare(
				"UPDATE reservations SET
					destination = :destination,
					place_number = :place_number,
					insurance = :insurance,
					price = :price WHERE no = $no;"
			);
			$statement->execute(array(
				'destination' => $destination,
				'place_number' => $place_number,
				'insurance' => $insurance,
				'price' => $price
			));
		}
		
		/**
		* update a row in people with primary key "id"
		* @param int id : primary key of the desired person
		* @param str name : name of the person
		* @param int age : age of the person
		* @return void
		*/
		public static function updatePeople($id, $name, $age)
		{
			$statement = self::$pdo->prepare(
				"UPDATE people SET
					name = :name,
					age = :age WHERE id = $id;"
			);
			$statement->execute(array(
				'name' => $name,
				'age' => $age
			));
		}
	}
?>