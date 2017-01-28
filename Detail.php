<html>
	<head>
		<title> Detail </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
		<meta name = "author" content = "Abeloos Damien 13241"/>
	</head>
	
	<body class = "greyApp">
		<div class = "boldTitle">
			DETAIL DES RESERVATIONS
		</div>
		
		<div class = "errorMessage" id = 'errorDetail' hidden>
			Veuillez entrer un nom pour chaque personne
			<br>
			Veuillez entrer un age supérieur à 0
		</div>
		
		<script>
			function showErrorMessage()
			{
				errorDetail.hidden = false;
				<?php 
					$list_persons = $reservation->getListPersons();
					for($i = 0; $i < $reservation->getPlaceNumber(); $i++)
					{
						echo ((count($list_persons) == $reservation->getPlaceNumber() && 
							null == $list_persons[$i]->getName()) ? "name$i.className = 'errorField';" : "").
							((count($list_persons) == $reservation->getPlaceNumber() && 
							null == $list_persons[$i]->getAge()) ? "age$i.className = 'errorField';" : "");
					}
				?>
				return;
			}
		</script>
		
		<form method = "POST" class = "indentedForm" action="router.php">
			<?php
				$list_persons = $reservation->getListPersons();
				for($i = 0; $i < $reservation->getPlaceNumber(); $i++)
				{
					echo "
						<table class = 'bordered'>
							<tr>
								<td>
									Nom
								</td>
								<td>
									<input type = 'text' name = 'name[]' id = 'name$i' value = ".
										((count($list_persons) == $reservation->getPlaceNumber()
										or $i < count($list_persons)) ? "'".
										strval($list_persons[$i]->getName())."'" : "''")." />
								</td>
							</tr>
							<tr>
								<td>
									Age
								</td>
								<td>
									<input type = 'text' name = 'age[]' id = 'age$i' value = ".
										((count($list_persons) == $reservation->getPlaceNumber()
										or $i < count($list_persons)) ? "'".
										strval($list_persons[$i]->getAge())."'" : "''")." />
								</td>
							</tr>
						</table>
						";
				}
			?>
			<input type = "submit" name = 'submit_detail' formaction = "router.php?case=submit_detail" 
				value = "Etape suivante"/>
			<input type = "submit" name = 'return_to_reservation' formaction = "router.php?case=return_to_reservation"
				value = "Retour a la page precedente"/>
			<input type = "submit" name = 'abort_reservation' formaction = "router.php?case=abort_reservation"
				value = "Annuler la reservation"/>
		</form>
		
		<script>
			<?php echo ($showErrorMessage) ? "showErrorMessage();" : ""; ?>
		</script>
	</body>
</html>