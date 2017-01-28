<html>
	<head>
		<title> Administration </title>
		<LINK rel = stylesheet href = "style.css"/>
		<meta charset = "utf8"/>
		<meta name = "author" content = "Abeloos Damien 13241"/>
	</head>
	
	<body class = "greyApp">
		<div class = "boldTitle">
			LISTE DES RESERVATIONS
		</div>
		
		<form method = "POST" class = "indentedForm" action = "router.php">
			<input type = "submit" name = 'add_reservation' formaction = "router.php?handler=Database&case=add_reservation"
				value = "Ajouter une réservation"/>
			<input type = "submit" id = 'remove_reservation' name = 'remove_reservation' 
				formaction = "router.php?handler=Database&case=remove_reservation" 
				value = "Supprimer la réservation" hidden />
			<input type = "submit" id = 'edit_reservation' name = 'edit_reservation' 
				formaction = "router.php?handler=Database&case=edit_reservation"
				value = "Editer la réservation" hidden />
			<?php
				//upon clicking on a reservation row, display a new board containing the people in this reservation
				
				$data = rdu::selectReservation();
				$col = $data->columnCount();
				$pk = array();
				echo "<table id='reservationTable' class = 'bordered'><tr class = 'lightBordered'>";
				for($i = 0; $i < $col; $i++)
				{
					$header = $data->getColumnMeta($i)["name"];
					echo "<th>".rdu::getHeaderAlias($header)."</th>";
				}
				echo "</tr>";
				while($row = $data->fetch(PDO::FETCH_ASSOC))
				{
					$pk[] = $row['no'];
					echo "<tr id='".$row['no']."' class = 'lightBordered'>";
					for($i=0; $i<$col; $i++)
					{
						$header = $data->getColumnMeta($i)["name"];
						echo "<td>".$row[$header]."</td>";
					}
					echo "</tr>";
				}
				echo "</table>";
			?>
			<div id = 'info' class = "subTitle" hidden>
				LISTE DES PERSONNES POUR LA RESERVATION N°
				<input class = "subTitle" type = "text" name = 'no' id = 'no' value = "" size = "1" readonly />
			</div>
			<?php
				if(count($pk)>0)
				{
					$sdata = rdu::selectReservationPeople($pk[0]);
					$scol = $sdata->columnCount();
				}
				else
				{
					$scol = 0;
				}
				echo "<table id='peopleTable' class = 'bordered' hidden><tr class = 'lightBordered'>";
				for($i = 0; $i < $scol; $i++)
				{
					$sheader = $sdata->getColumnMeta($i)["name"];
					echo "<th>".rdu::getHeaderAlias($sheader)."</th>";
				}
				echo "</tr>";
				for($i = 0; $i < count($pk); $i++)
				{
					echo "<tbody id='pk_".$pk[$i]."' hidden>";
					$sdata = rdu::selectReservationPeople($pk[$i]);
					while($row = $sdata->fetch(PDO::FETCH_ASSOC))
					{
						echo "<tr class = 'lightBordered'>";
						for($j=0; $j<$scol; $j++)
						{
							$sheader = $sdata->getColumnMeta($j)["name"];
							echo "<td>".$row[$sheader]."</td>";
						}
						echo "</tr>";
					}
					echo "</tbody>";
				}
				echo "</table>";
			?>
		</form>
		
		<script language="JavaScript">
			var previousClicked = null;
			function rowClick(strid)
			{
				var item = document.getElementById(strid);
				if(item.className!='clicked')
				{
					document.getElementById('pk_'+strid).hidden = false;
					document.getElementById('no').value = strid;
					item.className = 'clicked';
					if(previousClicked != null)
					{
						previousClicked.className = 'lightBordered';
						document.getElementById('pk_'+previousClicked.id).hidden = true;
					}
					else
					{
						document.getElementById('peopleTable').hidden = false;
						document.getElementById('edit_reservation').hidden = false;
						document.getElementById('remove_reservation').hidden = false;
						document.getElementById('info').hidden = false;
					}
					previousClicked = item;
				}
				return;
			}
			function rowMouseOver(strid)
			{
				var item = document.getElementById(strid);
				if(item.className == 'lightBordered')
				{
					item.className = 'mouseOver';
				}
				return;
			}
			function rowMouseOut(strid)
			{
				var item = document.getElementById(strid);
				if(item.className == 'mouseOver')
				{
					item.className = 'lightBordered';
				}
			}
			var table = document.getElementById('reservationTable');
			for(var i=1; i<table.rows.length; i++)
			{
				//create a scope for each iteration of the loop for the current value of i=k
				(function(k){
					table.rows[k].addEventListener('click', function(){
						rowClick(table.rows[k].id);
					}, false);
					table.rows[k].addEventListener('mouseover', function(){
						rowMouseOver(table.rows[k].id);
					}, false);
					table.rows[k].addEventListener('mouseout', function(){
						rowMouseOut(table.rows[k].id);
					}, false);
				})(i);
			}
		</script>
	</body>
</html>