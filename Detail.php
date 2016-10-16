<html>
	<head>
		<title> Detail </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
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
					for($i = 0; $i < $_SESSION['place_number']; $i++)
					{
						echo "
							name$i.value = ".((isset($_SESSION['name'][$i])) ?
								"'".strval($_SESSION['name'][$i])."';" : "''; name$i.className = 'errorField';")."
							age$i.value = ".((isset($_SESSION['age'][$i])) ?
								"'".strval($_SESSION['age'][$i])."';" : "''; age$i.className = 'errorField';");
					}
				?>
			}
			function keepReservation()
			{
				<?php 
					for($i = 0; $i < $_SESSION['place_number']; $i++)
					{
						echo "
							name$i.value = ".((isset($_SESSION['name'][$i])) ?
								"'".strval($_SESSION['name'][$i])."';" : "'';")."
							age$i.value = ".((isset($_SESSION['age'][$i])) ?
								"'".strval($_SESSION['age'][$i])."';" : "'';");
					}
				?>
			}
		</script>
		
		<form method = "POST" class = "indentedForm" action="handleReservation.php">
			<?php
				for($i = 0; $i < $_SESSION['place_number']; $i++)
				{
					echo "
						<table class = 'bordered'>
							<tr>
								<td>
									Nom
								</td>
								<td>
									<input type = 'text' name = 'name[]' id = 'name$i' />
								</td>
							</tr>
							<tr>
								<td>
									Age
								</td>
								<td>
									<input type = 'text' name = 'age[]' id = 'age$i' />
								</td>
							</tr>
						</table>
						";
				}
			?>
			<input type = "submit" name = 'submit_detail' value = "Etape suivante"/>
			<input type = "submit" name = 'return_to_reservation' value = "Retour a la page precedente"/>
			<input type = "submit" name = 'abort_reservation' value = "Annuler la reservation"/>
		</form>
	</body>
</html>