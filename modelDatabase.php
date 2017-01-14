<?php
	/**
	* @author Abeloos Damien 13241
	*/
	
	
	/**
	* provide tools to use a reservation database
	*/
	class reservationDbUtility
	{
		private static $pdo = NULL;
		private static $cur_host = NULL;
		private static $cur_pdodb_name = NULL;
		private static $cur_username = NULL;
		private static $cur_password = NULL;
		
		/**
		* 
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
		* 
		*/
		public static function createReservationTable()
		{
			if($pdo != NULL)
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
		}
		
		/**
		*
		*/
		public static function createPeopleTable()
		{
			if($pdo != NULL)
			{
				$pdo->exec(
					"CREATE TABLE IF NOT EXISTS people(
						id INT NOT NULL AUTO_INCREMENT,
						name VARCHAR(255) NOT NULL,
						age VARCHAR(255) NOT NULL,
						reservation_no INT NOT NULL,
						PRIMARY KEY (id),
						FOREIGN KEY (reservation_no)
							REFERENCES reservations(no)
							ON DELETE CASCADE
					) ENGINE = INNODB;"
				);
			}
		}
	}
?>