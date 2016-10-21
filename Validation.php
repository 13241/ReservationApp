<html>
	<head>
		<title> Reservation </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
	</head>
	
	<body class = "greyApp">
		<div class = "boldTitle">
			VALIDATION DES RESERVATIONS
		</div>
		
		<form method = "POST" class = "indentedForm" action="handleReservation.php">
			<table class = "bordered">
				<tr>
					<td>
						Destination
					</td>
					<td>
						<?php echo $reservation->getDestination(); ?>
					</td>
				</tr>
				<tr>
					<td>
						Nombre de places
					</td>
					<td>
						<?php echo $reservation->getPlaceNumber(); ?>
					</td>
				</tr>
				<?php
					$list_persons = $reservation->getListPersons();
					for($i = 0; $i < $reservation->getPlaceNumber(); $i++)
					{
						echo "
								<tr>
									<td>
										Nom
									</td>
									<td>
										".$list_persons[$i]->getName()."
									</td>
								</tr>
								<tr>
									<td>
										Age
									</td>
									<td>
										".$list_persons[$i]->getAge()."
									</td>
								</tr>
							";
					}
				?>
				<tr>
					<td>
						Assurance annulation
					</td>
					<td>
						<?php echo ($reservation->getInsurance()) ? "OUI" : "NON"; ?>
					</td>
				</tr>
			</table>
			<input type = "submit" name = 'submit_validation' value = "Confirmer"/>
			<input type = "submit" name = 'return_to_detail' value = "Retour a la page precedente"/>
			<input type = "submit" name = 'abort_reservation' value = "Annuler la reservation"/>
		</form>
	</body>
</html>