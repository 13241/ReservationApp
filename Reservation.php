<html>
	<head>
		<title> Reservation </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
	</head>
	
	<body>
		<div class = "boldTitle">
			RESERVATION
		</div>
		
		<div class = "indentedParagraph">
			Le prix de la place est de 10 euros jusqu'à 12 ans et ensuite de 15 euros.
			<br>
			Le prix de l'assurance annulation est de 20 euros quel que soit le nombre de voyageurs.
		</div>
		
		<form method = "POST" class = "indentedParagraph" action="handleReservation.php">
			<table>
				<tr>
					<td>
						Destination
					</td>
					<td>
						<input type = "text" name = "destination"/>
					</td>
				</tr>
				<tr>
					<td>
						Nombre de places
					</td>
					<td>
						<input type = "text" name = "place_number"/>
					</td>
				</tr>
				<tr>
					<td>
						Assurance annulation
					</td>
					<td>
						<input type = "checkbox" name= "insurance"/>
					</td>
				</tr>
			</table>
			<input type = "submit" name = "submit_reservation" value = "Etape suivante"/>
			<input type = "submit" name = "abort_reservation" value = "Annuler la reservation"/>
		</form>
	</body>
</html>