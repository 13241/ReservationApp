﻿<html>
	<head>
		<title> Reservation </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
	</head>
	
	<body class = "greyApp">
		<div class = "boldTitle">
			RESERVATION
		</div>
		
		<div class = "errorMessage" id = 'errorReservation' hidden>
			Veuillez entrer une destination
			<br>
			Veuillez entrer un nombre supérieur à 0 et inférieur à 10
		</div>
		
		<script>
			function showErrorMessage()
			{
				errorReservation.hidden = false;
				destination.value = <?php echo (isset($_SESSION['destination'])) ? 
					"'".strval($_SESSION['destination'])."'" : "''; destination.className = 'errorField'"; ?>;
				place_number.value = <?php echo (isset($_SESSION['place_number'])) ? 
					"'".strval($_SESSION['place_number'])."'" : "''; place_number.className = 'errorField'"; ?>;
				insurance.checked = <?php echo (isset($_SESSION['insurance'])) ? 
					$_SESSION['insurance'] : false; ?>;
			}
			function keepReservation()
			{
				destination.value = <?php echo (isset($_SESSION['destination'])) ? 
					"'".strval($_SESSION['destination'])."'" : "'';"; ?>;
				place_number.value = <?php echo (isset($_SESSION['place_number'])) ? 
					"'".strval($_SESSION['place_number'])."'" : "'';"; ?>;
				insurance.checked = <?php echo (isset($_SESSION['insurance'])) ? 
					$_SESSION['insurance'] : false; ?>;
			}
		</script>
		
		<div class = "indentedParagraph">
			Le prix de la place est de 10 euros jusqu'à 12 ans et ensuite de 15 euros.
			<br>
			Le prix de l'assurance annulation est de 20 euros quel que soit le nombre de voyageurs.
		</div>
		
		<form method = "POST" class = "indentedForm" action="handleReservation.php">
			<table class = "bordered">
				<tr>
					<td>
						Destination
					</td>
					<td>
						<input type = "text" name = 'destination' id = 'destination'/>
					</td>
				</tr>
				<tr>
					<td>
						Nombre de places
					</td>
					<td>
						<input type = "text" name = 'place_number' id = 'place_number'/>
					</td>
				</tr>
				<tr>
					<td>
						Assurance annulation
					</td>
					<td>
						<input type = "checkbox" name = 'insurance' id = 'insurance' value = '1' />
					</td>
				</tr>
			</table>
			<input type = "submit" name = 'submit_reservation' value = "Etape suivante"/>
			<input type = "submit" name = 'abort_reservation' value = "Annuler la reservation"/>
		</form>
	</body>
</html>