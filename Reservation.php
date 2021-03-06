﻿<html>
	<head>
		<title> Reservation </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
		<meta name = "author" content = "Abeloos Damien 13241"/>
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
				<?php echo ((null == $reservation->getDestination()) ? "destination.className = 'errorField';" : "").
				((null == $reservation->getPlaceNumber()) ? "place_number.className = 'errorField';" : ""); ?>
				return;
			}
		</script>
		
		<div class = "indentedParagraph">
			Le prix de la place est de 10 euros jusqu'à 12 ans et ensuite de 15 euros.
			<br>
			Le prix de l'assurance annulation est de 20 euros quel que soit le nombre de voyageurs.
		</div>
		
		<form method = "POST" class = "indentedForm" action="router.php">
			<table class = "bordered">
				<tr>
					<td>
						Destination
					</td>
					<td>
						<input type = "text" name = 'destination' id = 'destination' 
							value = <?php echo (null != $reservation->getDestination()) ? 
							strval($reservation->getDestination()) : "''"; ?> />
					</td>
				</tr>
				<tr>
					<td>
						Nombre de places
					</td>
					<td>
						<input type = "text" name = 'place_number' id = 'place_number'
							value = <?php echo (null != $reservation->getPlaceNumber()) ?
							strval($reservation->getPlaceNumber()) : "''"; ?> />
					</td>
				</tr>
				<tr>
					<td>
						Assurance annulation
					</td>
					<td>
						<input type = "checkbox" name = 'insurance' id = 'insurance' value = '1' 
							<?php echo ($reservation->getInsurance()) ? "checked" : ""; ?> />
					</td>
				</tr>
			</table>
			<input type = "submit" name = 'submit_reservation' formaction = "router.php?case=submit_reservation"
				value = "Etape suivante"/>
			<input type = "submit" name = 'abort_reservation' formaction = "router.php?case=abort_reservation" 
				value = "Annuler la reservation"/>
		</form>
		
		<script>
			<?php echo ($showErrorMessage) ? "showErrorMessage();" : ""; ?>
		</script>
	</body>
</html>