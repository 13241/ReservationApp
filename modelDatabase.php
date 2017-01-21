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
				if($pdo == NULL or $pdodb_name != "default")
				{
					$cur_host = $host;
					$cur_pdodb_name = $pdodb_name;
					$cur_username = $username;
					$cur_password = $password;
					$pdo = new PDO("mysql:host = $cur_host; charset = utf8", $cur_username, $cur_password);
					$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
					$pdo->exec("CREATE DATABASE IF NOT EXISTS $cur_pdodb_name");
				}
				$pdo->exec("USE $cur_pdodb_name");
				return $pdo;
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
		public static function createReservationTable()
		{
			$pdo->exec(
				"CREATE TABLE IF NOT EXISTS reservations(
					no INT NOT NULL AUTO_INCREMENT,
					destination varchar(255) NOT NULL,
					place_number INT(3) NOT NULL,
					insurance TINYINT(1) NOT NULL DEFAULT 0,
					price INT(4) NOT NULL,
					PRIMARY KEY (id)
				) ENGINE = INNODB;"
			);
		}
		
		/**
		* simple mysql statement to create a table for multiple people (people in this table cannot exist without a reservation)
		* @return void
		*/
		public static function createPeopleTable()
		{
			$pdo->exec(
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
			$statement = $pdo->prepare(
				"INSERT INTO reservations(id, destination, place_number, insurance, price)
					VALUES (NULL, :destination, :place_number, :insurance, :price);"
			);
			$statement->execute(array(
				'destination' => $destination,
				'place_number' => $place_number,
				'insurance' => $insurance,
				'price' => $price
			));
			
			//get the primary key from the last reservation
			$reservation_no = $pdo->query("SELECT LAST_INSERT_ID();");
			
			//insert data into "people" for this reservation
			$statement = $pdo->prepare(
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
		* return void
		*/
		public static function removeReservation($no)
		{
			$pdo->exec("DELETE FROM reservations WHERE no = $no;") or exit(mysql_error());
		}
	}
?>