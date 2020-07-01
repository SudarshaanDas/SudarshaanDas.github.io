<?php 
	$cell = $_GET['cell'];
	$B1_id = $_GET['id1'];
	$B2_id = $_GET['id2'];
	$B3_id = $_GET['id3'];
	$B = $_GET['B'];
	$mysqli = new mysqli("localhost", "root", "", "Battleship");
	$mysqli->query("USE Battleship");
		if($B == 2)
			$result = $mysqli->query("SELECT Ship FROM BoardUsing_" . $B2_id . " WHERE Positions LIKE \"%$cell%\"");
		if($B == 3)
			$result = $mysqli->query("SELECT Ship FROM BoardComputerUsing_" . $B3_id . " WHERE Positions LIKE \"%$cell%\"");
		if($result->num_rows > 0)
			{
				$row = $result->fetch_assoc();
						echo $row["Ship"];
					if($row["Ship"] == 0)
						$h_or_m = 0;
					else
						$h_or_m = $row["Ship"];
					
			}
	$Shots = "Shots_" . $B2_id . "_" . $B1_id . "_" . $B3_id;
	$Turns = "Turns_" . $B2_id . "_" . $B1_id . "_" . $B3_id;
	$check_Shots = True;
	$check_Turns = True;
	$result = $mysqli->query("SELECT TABLE_NAME FROM INFORMATION_SCHEMA.TABLES WHERE TABLE_TYPE = \"BASE TABLE\" AND TABLE_SCHEMA=\"Battleship\"");
		if($result->num_rows > 0)
			{
				$x=0;
					while($row = $result->fetch_assoc())
					{
						// $x=$x+$row["num"];
						echo $row["TABLE_NAME"] . "<br>";
						if($Shots == $row["TABLE_NAME"])
							$check_Shots = False;
						if($Turns == $row["TABLE_NAME"])
							$check_Turns = False;
					}
			}
	if($check_Shots)
	{
		$result = $mysqli->query("SELECT Board, Cell FROM Shots_" . $B1_id . "_" . $B2_id . "_" . $B3_id);
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					echo $row['Board'], "&nbsp", $row['Cell'], "<br>";
					if($row['Board'] == $B2_id && $row['Cell'] == $cell && $B == 2)
					{
						echo "ENTER12<br>";
						goto last;
					}
					if($row['Board'] == $B3_id && $row['Cell'] == $cell && $B == 3)
					{
						echo "ENTER13<br>";
						goto last;
					}
				}
			}
			if($B == 2)
				$mysqli->query("INSERT INTO Shots_" . $B1_id . "_" . $B2_id . "_" . $B3_id . " (Board , Cell, Hits) VALUES ('" . $B2_id . "' , '" . $cell . "' , '" . $h_or_m . "')");
			if($B == 3)
				$mysqli->query("INSERT INTO Shots_" . $B1_id . "_" . $B2_id . "_" . $B3_id . " (Board , Cell, Hits) VALUES ('" . $B3_id . "' , '" . $cell . "' , '" . $h_or_m . "')");
	}
	else
	{
		$result = $mysqli->query("SELECT Board, Cell FROM Shots_" . $B2_id . "_" . $B1_id . "_" . $B3_id);
			if($result->num_rows > 0)
			{
				while($row = $result->fetch_assoc())
				{
					echo $row['Board'], "&nbsp", $row['Cell'], "<br>";
					if($row['Board'] == $B2_id && $row['Cell'] == $cell && $B == 2)
					{
						echo "ENTER22<br>";
						goto last;
					}
					if($row['Board'] == $B3_id && $row['Cell'] == $cell && $B == 3)
					{
						echo "ENTER23<br>";
						goto last;
					}
				}
			}
			if($B == 2)
				$mysqli->query("INSERT INTO Shots_" . $B2_id . "_" . $B1_id . "_" . $B3_id . " (Board , Cell, Hits) VALUES ('" . $B2_id . "' , '" . $cell . "' , '" . $h_or_m . "')");
			if($B == 3)
				$mysqli->query("INSERT INTO Shots_" . $B2_id . "_" . $B1_id . "_" . $B3_id . " (Board , Cell, Hits) VALUES ('" . $B3_id . "' , '" . $cell . "' , '" . $h_or_m . "')");
	}
	if($check_Turns)
	{
        $result = $mysqli->query("SELECT * FROM Turns_" . $B1_id . "_" . $B2_id . "_" . $B3_id);
            if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                	if($row['Turn'] == $B1_id)
						$mysqli->query("UPDATE Turns_" . $B1_id . "_" . $B2_id . "_" . $B3_id . " SET Turn='" . $B2_id . "'");
                	else if($row['Turn'] == $B2_id)
						$mysqli->query("UPDATE Turns_" . $B1_id . "_" . $B2_id . "_" . $B3_id . " SET Turn='" . $B3_id . "'");
				}
			}
	}
	else
	{
        $result = $mysqli->query("SELECT * FROM Turns_" . $B2_id . "_" . $B1_id . "_" . $B3_id);
        	if($result->num_rows > 0)
            {
                while($row = $result->fetch_assoc())
                {
                	if($row['Turn'] == $B2_id)
						$mysqli->query("UPDATE Turns_" . $B2_id . "_" . $B1_id . "_" . $B3_id . " SET Turn='" . $B1_id . "'");
                	else if($row['Turn'] == $B1_id)
						$mysqli->query("UPDATE Turns_" . $B2_id . "_" . $B1_id . "_" . $B3_id . " SET Turn='" . $B3_id . "'");
				}
			}
	}
	last:
	header('Location: play_area.php?id1='. $B1_id . '&id2=' . $B2_id . '&id3=' . $B3_id);
?>