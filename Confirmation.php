<html>
	<head>
		<title> Reservation </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
		<meta name = "author" content = "Abeloos Damien 13241"/>
	</head>
	
	<body class = "greyApp">
		<div class = "boldTitle">
			CONFIRMATION DES RESERVATIONS
		</div>
		
		<div class = "indentedParagraph">
			Votre demande a bien été enregistrée.
			<br>
			Merci de bien vouloir verser la somme de 45 euros sur le compte 000-000000-00
		</div>
		
		<form method = "POST" class = "indentedForm" action="handleReservation.php">
			<input type = "submit" name = 'end_reservation' value = "Retour a la page d'accueil"/>
		</form>
	</body>
</html>