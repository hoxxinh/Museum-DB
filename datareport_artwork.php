<!DOCTYPE html>
<html lang="en">
<header>
  	<h1>Natural Museum of Fine Arts - Artwork Search</h1>
</header>
<head>
    <meta charset="UTF-8">
    <title>Enter values and click search:</title>
	<link rel="stylesheet" href="style/datareport_styles.css" />
</head>
<body>
	<br><br>
    <div class="datareportinput">
		<h2>Artwork Search</h2>
		<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
			<label for="searchName">Name: </label><input type="text" id="searchName" name="searchName"><br>
			<label for="searchType">Type: </label><input type="text" id="searchType" name="searchType"><br>
			<label for="searchArtist">Artist: </label><input type="text" id="searchArtist" name="searchArtist"><br>
			<label for="searchBuilding">Building: </label><input type="text" id="searchBuilding" name="searchBuilding"><br>
			<label for="searchFloor">Floor: </label><input type="text" id="searchFloor" name="searchFloor"><br>
			<label for="searchID">ID: </label><input type="text" id="searchID" name="searchID"><br>
			<br>
			<button type="submit" name="search">Search</button>
			<br>
			<button type="submit" name="download" value="download">Download as CSV</button><br><br>
    	</form>
	</div>
	<br><br>
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$searchName = isset($_POST['searchName']) ? $_POST['searchName'] : "";
        $searchType = isset($_POST['searchType']) ? $_POST['searchType'] : "";
		$searchArtist = isset($_POST['searchTermArtist']) ? $_POST['searchTermArtist'] : "";
		$searchBuilding = isset($_POST['searchBuilding']) ? $_POST['searchBuilding'] : "";
		$searchFloor = isset($_POST['searchFloor']) ? $_POST['searchFloor'] : "";
		$searchID = isset($_POST['searchID']) ? $_POST['searchID'] : "";
		//MAYBE: Add address? Ask professor first

        // Database configuration
		$servername = "museum.cpm4eq2ycfx2.us-east-1.rds.amazonaws.com";
		$username = "admin";
		$password = "museumteam5";
		$dbname = "MfahDB";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        //$sql = "SELECT ArtworkName, Artist, ArtYear, AcquisitionDate, Building, RoomFloor, ArtworkID, ArtworkType FROM artwork, room WHERE room.RoomID=artwork.RoomID AND ArtworkName LIKE ? AND ArtworkType LIKE ? AND Artist LIKE ? AND (Building=-1 OR Building=?) AND (RoomFloor=-1 OR RoomFloor=?) AND (ArtworkID=-1 OR ArtworkID=?);";
		$sql = "SELECT ArtworkName, Artist, ArtYear, AcquisitionDate, Building, RoomFloor, ArtworkID, ArtworkType FROM artwork, room WHERE room.RoomID=artwork.RoomID AND ArtworkName LIKE ? AND ArtworkType LIKE ? AND Artist LIKE ? AND (Building=? OR ?=0) AND (RoomFloor=? OR ?=0) AND (ArtworkID=? OR ?=0);";
		$stmt = $conn->prepare($sql);
		$searchTermName = "%" . $searchName . "%";
		$searchTermType = "%" . $searchType . "%";
		$searchTermArtist = "%" . $searchArtist . "%";
		$noBuilding = 1;
		$noFloor = 1;
		$noID = 1;
		$searchTermBuilding = $searchBuilding; if($searchBuilding == ""){$searchTermBuilding = "-1"; $noBuilding = 0;};
		$searchTermFloor = $searchFloor; if($searchFloor == ""){$searchTermFloor = "-1"; $noFloor="0";}
		$searchTermID = $searchID; if($searchID == ""){$searchTermID = "-1"; $noID="0";}
        //$stmt->bind_param("sssiii", $searchTermName, $searchTermType, $searchTermArtist, $searchTermBuilding, $searchTermFloor, $searchTermID);
		$stmt->bind_param("sssiiiiii", $searchTermName, $searchTermType, $searchTermArtist, $searchBuilding, $noBuilding, $searchTermFloor, $noFloor, $searchTermID, $noID);
        $stmt->execute();
        $result = $stmt->get_result();
		
		if (isset($_POST['search'])) {
			if ($result->num_rows > 0) {
				echo "<table border='1'><tr><th>Name</th><th>Artist</th><th>Year</th><th>Acquisition Date</th><th>Building#</th><th>Floor</th><th>ID</th></tr>";
				// output data of each row
				while($row = $result->fetch_assoc()) {
					echo "<tr><td>".$row["ArtworkName"]."</td><td>".$row["Artist"]."</td><td>".$row["ArtYear"]."</td><td>".$row["AcquisitionDate"]."</td><td>".$row["Building"]."</td><td>".$row["RoomFloor"]."</td><td>".$row["ArtworkID"]."</tr>";
				}
				echo "</table>";
			} else {
				echo "0 results";
			}
		}
		elseif (isset($_POST['download'])) {
            header('Content-Type: text/csv');
            header('Content-Disposition: attachment; filename="employee_search_results.csv"');

            // Output the results to CSV
            $output = fopen("php://output", "w");
			ob_end_clean();
			fputcsv($output, ['ArtworkName', 'Artist', 'ArtYear', 'AcquisitionDate', 'Building','RoomFloor','ArtworkID']); // column headers

            while($row = $result->fetch_assoc()) {
                fputcsv($output, [$row['ArtworkName'], $row['Artist'], $row['ArtYear'], $row['AcquisitionDate'], $row['Building'], $row['RoomFloor'], $row['ArtworkID']]); // data rows
            }

            fclose($output);
            exit;
        }



		
        $stmt->close();
        $conn->close();
    }
    ?>
</body>
</html>
